<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\ArticleProgress;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\UserCertificate;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class LessonController extends Controller
{
    public function complete(Request $request)
    {
        $lessonId = $request->input('lesson_id');
        $lesson = Lesson::find($lessonId);
        
        if (!$lesson) {
            return response()->json(['status' => 'error', 'message' => 'Lesson not found'], 404);
        }

        $courseId = $lesson->section->course_id;

        if (!auth()->check()) {
            // For guests, we return guest_success and the course_id so frontend can handle cookies
            return response()->json([
                'status' => 'guest_success',
                'course_id' => $courseId,
                'message' => 'Guest progress acknowledged'
            ]);
        }

        $userId = auth()->user()->id;
        $status = $request->input('status');

        $progress = LessonProgress::firstOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lessonId]
        );

        // SEQUENCE VALIDATION: Check if previous lesson is completed
        // 1. Find previous lesson in same section
        $prevLesson = Lesson::where('section_id', $lesson->section_id)
            ->where('id', '<', $lesson->id)
            ->orderBy('id', 'desc')
            ->first();

        // 2. If no previous lesson in this section, check the last lesson of the previous section
        if (!$prevLesson) {
             $prevSection = DB::table('sections')
                ->where('course_id', $courseId)
                ->where('id', '<', $lesson->section_id)
                ->orderBy('id', 'desc')
                ->first();
             
             if ($prevSection) {
                 $prevLesson = Lesson::where('section_id', $prevSection->id)
                    ->orderBy('id', 'desc')
                    ->first();
             }
        }

        // 3. If a previous lesson exists, check if it is completed
        if ($prevLesson) {
             $isPrevCompleted = LessonProgress::where('user_id', $userId)
                ->where('lesson_id', $prevLesson->id)
                ->where('is_completed', 1)
                ->exists();
             
             if (!$isPrevCompleted) {
                 return response()->json(['status' => 'error', 'message' => 'You must complete the previous lesson first.'], 403);
             }
        }

        // Mark as completed if requested AND not already completed
        if ($status === 'completed' && !$progress->is_completed) {
            $progress->is_completed = 1;
            $progress->completed_at = now();
        }

        $progress->updated_at = now();
        $progress->save();

        return response()->json(['status' => 'success', 'course_id' => $courseId]);
    }


    // Get the lesson progress
    public function getLessonProgress(Request $request)
    {
        try {
            $courseId = $request->input('course_id');
            // [FIX] IDOR: Use authenticated user ID
            $userId = auth()->id();
            
            if (!$userId) {
                // Handle guest or unauthenticated case if valid, otherwise error
                // Assuming this endpoint requires auth based on context
                 return response()->json(['error' => 'Unauthenticated'], 401);
            }

            // $userId = $request->input('user_id');


            // Get total lessons count for the specific course
            $totalLessons = DB::table('lessons')
                ->where('course_id', $courseId)
                ->count();

            // Get watched lessons count for the user in the specific course
            $watchedLessons = DB::table('lesson_progress')
                ->join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
                ->where('lesson_progress.user_id', $userId)
                ->where('lessons.course_id', $courseId)
                ->where('lesson_progress.is_completed', 1)
                ->count();

            // Get lesson details with progress for the given course
            $lessons = DB::table('lessons')
                ->leftJoin('lesson_progress', function ($join) use ($userId) {
                    $join->on('lessons.id', '=', 'lesson_progress.lesson_id')
                        ->where('lesson_progress.user_id', '=', $userId);
                })
                ->where('lessons.course_id', $courseId)
                ->select('lessons.*', DB::raw('IFNULL(lesson_progress.is_completed, 0) as is_completed'))
                ->get();

            return response()->json([
                'course_id' => $courseId,
                'total_lessons' => $totalLessons,
                'watched_lessons' => $watchedLessons,
                'lessons' => $lessons,
            ]);
        } catch (\Exception $e) {
            \Log::error('Lesson Progress Error:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function markLessonCompleted(Request $request)
    {
        $request->validate([
            // 'user_id' => 'required|exists:users,id', // REMOVED IDOR VULNERABILITY
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        //check if the lesson is already marked as completed
        $exists = DB::table('lesson_progress')
            ->where('user_id', $userId)
            ->where('lesson_id', $request->lesson_id)
            ->exists();

        if (!$exists) {
            DB::table('lesson_progress')->insert([
                'user_id' => $userId,
                'lesson_id' => $request->lesson_id,
                'is_completed' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function showCertificates(Request $request)
    {
        $userId = $request->input('user_id', auth()->id());

        // Simulating API call with user_id
        $response = Http::post('https://insightech.cloud/lfwebsite/public/api/generate-certificate', [
            'user_id' => $userId
        ]);

        $completedCourses = $response->json()['completed_courses'] ?? [];

        // Fetch completed lessons for the user
        $completedLessons = DB::table('lesson_progress')
            ->where('user_id', $userId)
            ->where('is_completed', 1)
            ->pluck('lesson_id')
            ->toArray();

        // Add 'all_lessons_completed' to each course
        $completedCourses = collect($completedCourses)->map(function ($course) use ($completedLessons) {
            // Fetch all lessons for this course
            $allLessonsForCourse = DB::table('lessons')
                ->join('sections', 'lessons.section_id', '=', 'sections.id')
                ->where('sections.course_id', $course['id'])
                ->pluck('lessons.id')
                ->toArray();

            // Check if all lessons are completed
            $course->all_lessons_completed = !collect($allLessonsForCourse)->diff($completedLessons)->count() ? 1 : 0;

            return $course;
        });

        return view('certificate.index', compact('completedCourses'));
    }
    // Example controller method to handle lesson progress saving
    public function completeLesson(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        // $userId = $request->input('user_id'); // VULNERABILITY FIX
        $lessonId = $request->input('lesson_id');
        $status = $request->input('status');

        // Save or update the lesson progress in the database
        $lessonProgress = LessonProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'lesson_id' => $lessonId
            ],
            [
                'is_completed' => $status === 'unlocked' ? 1 : 0,
            ]
        );

        return response()->json(['message' => 'Lesson progress saved successfully']);
    }


    public function getUnlockedLessons(Request $request)
    {
        // [FIX] IDOR: Use authenticated user ID
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        // $userId = $request->input('user_id'); // REMOVED VULNERABILITY
        $courseId = $request->input('course_id');

        // Fetch unlocked lesson IDs for the user and course
        $unlockedLessons = LessonProgress::join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
            ->join('sections', 'lessons.section_id', '=', 'sections.id')
            ->where('lesson_progress.user_id', $userId)
            ->where('sections.course_id', $courseId)
            ->pluck('lesson_progress.lesson_id')
            ->toArray();


        return response()->json(['unlocked_lessons' => $unlockedLessons]);
    }

    public function generateCertificate(Request $request)
    {
        // Allow GET/POST; default to authenticated user when user_id not provided
        $user_id = $request->input('user_id', auth()->id());
        if (!$user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch all courses
        $courses = Course::all();

        $completedCourses = [];

        foreach ($courses as $course) {
            // Check 1: Does a certificate record already exist?
            // This is the most reliable check if they have already downloaded it.
            $hasCertificateRecord = UserCertificate::where('user_id', $user_id)
                ->where('course_id', $course->id)
                ->exists();

            if ($hasCertificateRecord) {
                 $completedCourses[] = [
                    'course_id' => $course->id,
                    'title' => $course->title,
                    'image' => $course->image,
                ];
                continue;
            }

            // Check 2: Calculate completion dynamically
            // Use existing helper methods (getLessonStats, getQuizStats)
            $lessonStats = $this->getLessonStats($user_id, $course->id);
            $quizStats = $this->getQuizStats($user_id, $course->id);

            $allLessonsDone = $lessonStats['completed_count'] >= $lessonStats['total_count'] && $lessonStats['total_count'] > 0;
            $allQuizzesDone = $quizStats['completed_count'] >= $quizStats['total_count'] && $quizStats['total_count'] > 0;

            if ($allLessonsDone && $allQuizzesDone) {
                $completedCourses[] = [
                    'course_id' => $course->id,
                    'title' => $course->title,
                    'image' => $course->image,
                ];
            }
        }

        if (empty($completedCourses)) {
            return response()->json(['message' => 'No completed courses found'], 404);
        }

        return response()->json([
            'message' => 'Completed courses fetched successfully',
            'completed_courses' => $completedCourses
        ], 200);
    }

    public function getCompletedTests(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) return response()->json(['error' => 'Unauthenticated'], 401);
        // $userId = $request->input('user_id');
        $courseId = $request->input('course_id');
        
        $sections = \DB::table('sections')->where('course_id', $courseId)->pluck('id');
        $completedSections = [];

        foreach ($sections as $sectionId) {
            $questionIds = \DB::table('questions')
                ->where('section_id', $sectionId)
                ->pluck('id');

            if ($questionIds->count() === 0) continue;

            $answeredCount = \DB::table('quiz_progress')
                ->where('user_id', $userId)
                ->whereIn('question_id', $questionIds)
                ->where('is_correct', 1) // Strict check
                ->count();

            if ($answeredCount >= $questionIds->count()) {
                $completedSections[] = $sectionId;
            }
        }

        return response()->json([
            'completed_test_sections' => $completedSections
        ]);
    }

    // New: unified check completion endpoint used by frontend fallbacks
    public function checkCompletion(Request $request)
    {
        $request->validate([
            // 'user_id' => 'required|integer|exists:users,id',
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        $userId = auth()->id();
        if (!$userId) return response()->json(['error' => 'Unauthenticated'], 401);
        // $userId = $request->input('user_id');
        $courseId = $request->input('course_id');

        $lessonStats = $this->getLessonStats($userId, $courseId);
        $quizStats = $this->getQuizStats($userId, $courseId);
        $reviewStats = $this->getReviewStats($userId, $courseId);

        $allLessonsDone = $lessonStats['completed_count'] >= $lessonStats['total_count'] && $lessonStats['total_count'] > 0;
        $allQuizzesDone = $quizStats['completed_count'] >= $quizStats['total_count'] && $quizStats['total_count'] > 0;
        $reviewCompleted = $reviewStats['completed'];
        $reviewRequired = $reviewStats['required'];

        // Course is complete only if lessons, quizzes, and review (if required) are done
        $courseCompleted = $allLessonsDone && $allQuizzesDone && (!$reviewRequired || $reviewCompleted);

        return response()->json([
            'completed' => $courseCompleted,
            'lessons_completed' => $allLessonsDone,
            'quizzes_completed' => $allQuizzesDone,
            'review_completed' => $reviewCompleted,
            'review_required' => $reviewRequired,
            // Detailed stats for progress bars
            'lesson_stats' => $lessonStats,
            'quiz_stats' => $quizStats,
            'review_stats' => $reviewStats
        ]);
    }

    private function getReviewStats(int $userId, int $courseId): array
    {
        $totalQuestions = \App\Models\ReviewQuestion::where('course_id', $courseId)
            ->where('is_active', true)
            ->count();
        
        if ($totalQuestions === 0) {
            return [
                'total_count' => 0,
                'completed_count' => 0,
                'completed' => true,
                'required' => false
            ];
        }

        $answeredQuestions = \App\Models\ReviewAnswer::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->count();

        return [
            'total_count' => $totalQuestions,
            'completed_count' => $answeredQuestions,
            'completed' => $answeredQuestions >= $totalQuestions,
            'required' => true
        ];
    }

    private function getLessonStats(int $userId, int $courseId): array
    {
        $totalLessons = \DB::table('lessons')->where('course_id', $courseId)->count();
        $completedLessons = 0;
        
        if ($totalLessons > 0) {
            $completedLessons = \DB::table('lesson_progress')
                ->join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
                ->where('lessons.course_id', $courseId)
                ->where('lesson_progress.user_id', $userId)
                ->where('lesson_progress.is_completed', 1)
                ->count();
        }

        return [
            'total_count' => $totalLessons,
            'completed_count' => $completedLessons
        ];
    }

    private function getQuizStats(int $userId, int $courseId): array
    {
        // Collect sections for course
        $sections = \DB::table('sections')->where('course_id', $courseId)->pluck('id');
        
        $totalQuizzes = 0;
        $completedQuizzes = 0;

        foreach ($sections as $sectionId) {
            $questionIds = \DB::table('questions')
                ->where('section_id', $sectionId) // Assuming questions are linked to section, ignoring course_id on questions table for safety if it varies
                ->pluck('id');

            // Only count sections that actually have questions
            if ($questionIds->count() === 0) {
                continue;
            }
            
            $totalQuizzes++;

            $answeredCount = \DB::table('quiz_progress')
                ->where('user_id', $userId)
                ->whereIn('question_id', $questionIds)
                ->where('is_correct', 1) // Enforce ALL Correct answers
                ->count();

            // Check if user answered ALL questions CORRECTLY in this section
            if ($answeredCount >= $questionIds->count()) {
                $completedQuizzes++;
            }
        }

        return [
            'total_count' => $totalQuizzes,
            'completed_count' => $completedQuizzes
        ];
    }
    public function fetchUnlockedArticles(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            // 'user_id' => 'required|exists:users,id',
        ]);

        $user_id = auth()->id();
        if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
        // $user_id = $request->input('user_id');
        $course_id = $request->input('course_id');

        // Fetch all articles for the given course
        $articleIds = Article::whereHas('section', function ($query) use ($course_id) {
            $query->where('course_id', $course_id);
        })->pluck('id')->toArray();

        // Fetch unlocked articles for the user in the specified course
        $unlockedArticles = \DB::table('article_progress')
            ->where('user_id', $user_id)
            ->whereIn('article_id', $articleIds)
            ->where('is_completed', true)
            ->pluck('article_id')
            ->toArray();

        return response()->json([
            'unlocked_articles' => $unlockedArticles
        ]);
    }

    public function completeArticle(Request $request)
    {
        // [FIX] IDOR: Use authenticated user ID
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        // $userId = $request->input('user_id'); // REMOVED VULNERABILITY
        $articleId = $request->input('article_id');

        // Save or update the article progress in the database
        $articleProgress = ArticleProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'article_id' => $articleId
            ],
            [
                'is_completed' => 1,
            ]
        );

        return response()->json(['message' => 'Article progress saved successfully']);
    }
    public function checkSectionCompletion(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) return response()->json(['error' => 'Unauthenticated'], 401);
        // $userId = $request->user_id;
        $sectionId = $request->section_id;

        // Fetch all lessons in the section
        $lessonsInSection = Lesson::where('section_id', $sectionId)->pluck('id');

        // Fetch completed lessons by the user in this section
        $completedLessons = DB::table('lesson_progress')
            ->where('user_id', $userId)
            ->whereIn('lesson_id', $lessonsInSection)
            ->where('is_completed', 1)
            ->pluck('lesson_id');

        // Check if all lessons are completed
        if ($completedLessons->count() == $lessonsInSection->count()) {
            return response()->json(['section_completed' => true]);
        } else {
            return response()->json(['section_completed' => false]);
        }
    }
    public function showCertificatePage()
    {
        $course = Course::where('user_id', auth()->user()->id)->get(); // Fetch the user's courses
        return view('certificate.index', compact('course')); // Pass $course to the view
    }
    public function store(Request $request)
    {
        $request->validate([
            // 'user_id' => 'required|integer|exists:users,id',
            'course_id' => 'required|integer|exists:courses,id',
            'title' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
        ]);

        $userId = auth()->id();
        if (!$userId) return response()->json(['error' => 'Unauthenticated'], 401);

        // Create a new certificate entry
        UserCertificate::create([
            'user_id' => $userId,
            'course_id' => $request->course_id,
            'title' => $request->title,
            'image' => $request->image,
        ]);

        return response()->json(['message' => 'Certificate saved successfully'], 200);
    }

    public function fetchCoursesByAgeGroup(Request $request)
    {
        // Validate the age group input
        $request->validate([
            'age_group' => 'required|string',
        ]);

        // Get the authenticated user's ID
        $user_id = Auth::id(); // No need to pass user_id in request

        $age_group = $request->input('age_group');


        // Fetch courses based on age_group
        if (strtolower($age_group) == 'any age' || strtolower($age_group) == 'any') {
            $courses = Course::all();
        } else {
            $courses = Course::where('age_group', $age_group)->get();
        }

        // Check if each course is marked as favorite by the user and calculate CTA
        $courses = $courses->map(function ($course) use ($user_id) {
            $course->isFavourite = 0;
            
            // Get dynamic status from Course model (handles both guests and auth users)
            $user = Auth::user(); 
            $dynamicStatus = $course->getDynamicStatus($user);
            
            $course->is_paid = $dynamicStatus['is_paid'];
            $course->has_progress = $dynamicStatus['has_progress'];
            $course->cta_text = $dynamicStatus['cta_text'];

            if ($user_id) {
                // Check Favourite
                $course->isFavourite = DB::table('favorites')
                    ->where('user_id', $user_id)
                    ->where('course_id', $course->id)
                    ->exists() ? 1 : 0;
            }
            
            // Format price for display
            $course->formatted_price = convertPrice($course->price, true, $course->price_usd);

            return $course->makeHidden('description');
        });

        if ($courses->isEmpty()) {
            return response()->json(['code' => 404, 'message' => 'No courses found', 'courses' => []], 404);
        }

        return response()->json(['code' => 200, 'courses' => $courses, 'is_logged_in' => !!$user_id], 200);
    }

    /**
     *  getLastWatched method in LessonController with this:
     */
    public function getLastWatched(Request $request, $courseId)
    {
        $user = auth()->user();

        // Guest users start from first lesson
        if (!$user) {
            $firstLesson = $this->getFirstLessonOfCourse($courseId);
            return response()->json([
                'lesson_id' => $firstLesson ? $firstLesson->id : null,
                'position_seconds' => 0,
                'last_position_seconds' => 0,
                'is_guest' => true,
                'is_completed' => false
            ]);
        }

        // Get last watched lesson with position for authenticated users
        $lastWatched = LessonProgress::where('user_id', $user->id)
            ->whereHas('lesson.section', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            })
            ->orderBy('updated_at', 'desc')
            ->first();

        if ($lastWatched) {
            $positionSeconds = $lastWatched->last_position_seconds ?? 0;

            return response()->json([
                'lesson_id' => $lastWatched->lesson_id,
                'position_seconds' => $positionSeconds,
                'last_position_seconds' => $positionSeconds, // Duplicate for compatibility
                'is_completed' => $lastWatched->is_completed,
                'is_guest' => false
            ]);
        }

        // No progress - start from first lesson
        $firstLesson = $this->getFirstLessonOfCourse($courseId);
        return response()->json([
            'lesson_id' => $firstLesson ? $firstLesson->id : null,
            'position_seconds' => 0,
            'last_position_seconds' => 0,
            'is_guest' => false,
            'is_completed' => false
        ]);
    }

    /**
     * Save video position every 10 seconds
     */
    private function getFirstLessonOfCourse($courseId)
    {
        $firstSection = DB::table('sections')
            ->where('course_id', $courseId)
            ->orderBy('id', 'asc')  // Use 'id' instead of non-existent 'order'
            ->first();

        if (!$firstSection) return null;

        // Get first lesson by ID, not by 'order' column
        return Lesson::where('section_id', $firstSection->id)
            ->orderBy('id', 'asc')  // CHANGED: Use 'id' instead of 'order'
            ->first();
    }

    /**
     * Mark lesson as completed (video ended)
     */
    public function markComplete(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Guest users cannot save progress'], 401);
        }

        $request->validate(['lesson_id' => 'required|integer|exists:lessons,id']);

        LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $request->lesson_id
            ],
            [
                'is_completed' => 1,
                'last_position_seconds' => 0, // Reset position after completion
                'completed_at' => now()
            ]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Get next lesson in sequence
     */
    public function nextLesson($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        // Try to find next lesson in same section (by ID, not order)
        $next = Lesson::where('section_id', $lesson->section_id)
            ->where('id', '>', $lesson->id)  // CHANGED: Use 'id' instead of 'order'
            ->orderBy('id', 'asc')
            ->first();

        // If no next lesson in section, try next section
        if (!$next) {
            $nextSection = DB::table('sections')
                ->where('course_id', $lesson->section->course_id)
                ->where('id', '>', $lesson->section_id)
                ->orderBy('id', 'asc')
                ->first();

            if ($nextSection) {
                $next = Lesson::where('section_id', $nextSection->id)
                    ->orderBy('id', 'asc')  // CHANGED: Use 'id' instead of 'order'
                    ->first();
            }
        }

        return response()->json([
            'next_lesson_id' => $next ? $next->id : null,
            'auto_open' => true
        ]);
    }

    /**
     * Save video position every 10 seconds
     * Add this method to App\Http\Controllers\Auth\LessonController
     */
    public function savePosition(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Guest users cannot save progress'], 401);
        }

        $request->validate([
            'lesson_id' => 'required|integer|exists:lessons,id',
            'position_seconds' => 'required|numeric|min:0'
        ]);

        $lessonId = $request->lesson_id;
        $positionSeconds = max(0, floor($request->position_seconds));

        // Update or create progress record
        LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lessonId
            ],
            [
                'last_position_seconds' => $positionSeconds,
                'updated_at' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'position_seconds' => $positionSeconds
        ]);
    }

    public function getPosition($lessonId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'position_seconds' => 0,
                'last_position_seconds' => 0,
                'is_guest' => true
            ]);
        }

        $progress = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lessonId)
            ->first();

        $positionSeconds = $progress ? ($progress->last_position_seconds ?? 0) : 0;

        return response()->json([
            'position_seconds' => $positionSeconds,
            'last_position_seconds' => $positionSeconds,
            'is_completed' => $progress ? $progress->is_completed : false,
            'lesson_id' => $lessonId,
            'is_guest' => false
        ]);
    }

    /**
     * Helper: Get first lesson of a course
     */
    private function getFirstLesson($courseId)
    {
        $firstSection = DB::table('sections')
            ->where('course_id', $courseId)
            ->orderBy('id')
            ->first();

        if (!$firstSection) return null;

        return Lesson::where('section_id', $firstSection->id)
            ->orderBy('order')
            ->first();
    }
}
