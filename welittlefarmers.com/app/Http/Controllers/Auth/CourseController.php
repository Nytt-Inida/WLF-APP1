<?php

namespace App\Http\Controllers\Auth;

use App\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LessonProgress;
use App\Models\UserProgress;
use App\Models\UserCertificate;
use App\Models\QuizProgress;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Models\SeoMeta;
use App\Models\ReferralSetting;
use SEO;


class CourseController extends Controller
{
    // Fetch courses by age group
    public function fetchCoursesByAgeGroup(Request $request)
    {
        $age_group = $request->input('age_group');

        if ($age_group === 'any') {
            $courses = Course::all();
        } else {
            $courses = Course::where('age_group', $age_group)->get();
        }

        // Add full URL to image and formatted price
        $courses->each(function ($course) {
            $course->image = $course->image ? asset($course->image) : asset('path/to/default-image.jpg');
            $course->formatted_price = convertPrice($course->price, true, $course->price_usd);
        });

        return response()->json($courses);
    }

    // Method to display a list of courses
    public function index()
    {
        // Fetch all courses from the database
        $courses = Course::all();

        // Pass the courses to the view
        return view('courses.index', compact('courses'));
    }


    //SEO FOR ABOUT PAGE
    public function about()
    {
        // Getting SEO tags details
        $seo = SEOMeta::where('page_type', 'about')->first();

        if ($seo) {
            SEO::setTitle($seo->title);
            SEO::setDescription($seo->description);
            if ($seo->keywords) {
                SEO::metatags()->addKeyword(explode(',', $seo->keywords));
            }
        } else {
            // Fallback SEO
            SEO::setTitle('Little Farmers Academy - Online Farming Courses for Kids');
            SEO::setDescription('Little Farmers Academy offers online farming courses for kids, providing essential skills in agriculture, food science, and sustainable practices.');
            SEO::metatags()->addKeyword(['online education', 'farming courses for kids', 'agriculture education', 'kids farming skills', 'Little Farmers Academy']);
        }

        return view('about', compact('seo'));
    }

    // Show course details
    public function show($courseId)
    {
        // Fetch the course based on the ID
        $course = Course::findOrFail($courseId);

        // Ensure the user is authenticated
        $user = auth()->user();

        // Check if the user has completed all lessons
        $completedLessons = DB::table('lesson_progress')
            ->where('user_id', $user->id)
            ->where('is_completed', 1)
            ->pluck('lesson_id')
            ->toArray();

        // Check if all lessons for this course have been completed
        $allLessonsCompleted = $course->sections->flatMap->lessons->pluck('id')->diff($completedLessons)->isEmpty();

        // If the user has completed all lessons, show the certificate
        if ($allLessonsCompleted) {
            return view('certificate.index', compact('user', 'course'));
        }

        // If not all lessons are completed, redirect to course details with an error
        return redirect()->route('course.details', ['id' => $course->id])->with('error', 'You must complete all lessons to view the certificate.');
    }


    public function perform()
    {
        Auth::logout(); // Log the user out of the application

        // Optionally, you can clear the session
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // Redirect to an external URL after logout
        return redirect('/');
    }

    public function checkCompletionStatus(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) return response()->json(['error' => 'Unauthenticated'], 401);
        // $userId = $request->input('user_id');
        $lessonId = $request->input('lesson_id');

        // Check if the lesson is completed
        $isCompleted = LessonProgress::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->exists();

        return response()->json(['is_completed' => $isCompleted]);
    }



    public function showCourseDetails($courseId)
    {
        // Eager load sections, lessons, articles, and user's payment progress for this course
        $course = Course::with(['sections.lessons', 'sections.articles', 'userProgresses' => function($q) {
            $q->where('user_id', auth()->id());
        }])->find($courseId);

        // Initialize variables
        $completedLessons = [];
        $completedArticles = [];
        $allLessonsCompleted = false;
        $hasPaid = false;
        
        // Calculate total lessons from eager loaded sections (Avoids extra DB query)
        $totalLessons = $course->sections->sum(function($section) {
            return $section->lessons->count();
        });

        // Initialize default values for guests
        $completedLessons = [];
        $completedArticles = [];
        $completedTestSections = [];
        $hasPaid = false;

        if (auth()->check()) {
            $userId = auth()->user()->id;
            
            // Check payment status from eager loaded relation (Avoids DB query)
            $hasPaid = $course->userProgresses->where('payment_status', 1)->isNotEmpty();

            // Fetch the IDs of the lessons the authenticated user has completed in THIS course
            $completedLessons = DB::table('lesson_progress')
                ->join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
                ->join('sections', 'lessons.section_id', '=', 'sections.id')
                ->where('lesson_progress.user_id', $userId)
                ->where('lesson_progress.is_completed', 1)
                ->where('sections.course_id', $course->id)
                ->pluck('lesson_progress.lesson_id')
                ->toArray();

            // Fetch ALL unlocked lesson IDs (including in-progress) in THIS course
            $unlockedLessonIds = DB::table('lesson_progress')
                ->join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
                ->join('sections', 'lessons.section_id', '=', 'sections.id')
                ->where('lesson_progress.user_id', $userId)
                ->where('sections.course_id', $course->id)
                ->pluck('lesson_progress.lesson_id')
                ->toArray();

            // Fetch the IDs of the articles the authenticated user has completed
            $completedArticles = DB::table('article_progress')
                ->where('user_id', $userId)
                ->where('is_completed', 1)
                ->pluck('article_id')
                ->toArray();

            // Calculate Completed Test Sections (Server-Side Calculation)
            $completedTestSections = [];
            foreach ($course->sections as $section) {
                $questionIds = DB::table('questions')->where('section_id', $section->id)->pluck('id');
                
                if ($questionIds->isEmpty()) continue;

                $answeredCount = DB::table('quiz_progress')
                    ->where('user_id', $userId)
                    ->whereIn('question_id', $questionIds)
                    ->where('is_correct', 1)
                    ->count();

                if ($answeredCount >= $questionIds->count()) {
                    $completedTestSections[] = $section->id;
                }
            }

            // Check if all lessons AND all quizzes are completed
            $allLessonsCompleted = $course->sections->flatMap->lessons->pluck('id')->diff($completedLessons)->isEmpty()
                && count($completedTestSections) >= $course->sections->count();
        }

        // Prepare Initial State for Frontend (Removes need for AJAX on load)
        $initialState = [
            'userId' => auth()->id(),
            'courseId' => $course->id,
            'hasPaid' => $hasPaid,
            'totalLessons' => $totalLessons,
            'watchedLessonsCount' => count($completedLessons),
            'watchedLessonIds' => $completedLessons,
            'unlockedLessonIds' => $unlockedLessonIds ?? [], 
            'completedArticles' => $completedArticles,
            'completedTestSections' => $completedTestSections,
        ];

        // Retrive SEO meta data
        $seo = SeoMeta::where('page_type', 'course_details')->first();

        // Set SEO Metadata
        if ($seo) {
            SEO::SetTitle("{$course->title} - {$seo->title}");
            SEO::setDescription($seo->description);
            if ($seo->keywords) {
                SEO::metatags()->addKeyWord(explode(',', $seo->keywords));
            }
        } else {
            // Fallback SEO
            SEO::setTitle("{$course->title} - Little Farmers Academy");
            SEO::setDescription("We Little Farmer is an educational platform for kids, teaching them how to grow plants and understand the importance of nature.");
            SEO::metatags()->addKeyword(['online', 'education', 'e-learning', 'coaching', 'education', 'teaching', 'learning']);
        }


        // Return the view with the required data
        return view('course.details', compact('course', 'completedLessons', 'allLessonsCompleted', 'completedArticles', 'totalLessons', 'hasPaid', 'initialState'));
    }


    public function downloadCertificate($courseId)
    {
        try {
            Log::info("Starting certificate download for course: $courseId");
            
            $user = auth()->user();
            if (!$user) {
                Log::error("User not authenticated during certificate download");
                return redirect()->route('login');
            }
            Log::info("User authenticated: " . $user->id);

            $course = Course::find($courseId);
            if (!$course) {
                Log::error("Course not found: $courseId");
                return abort(404, 'Course not found');
            }
            Log::info("Course found: " . ($course->title ?? 'NO TITLE'));

            // SECURITY CHECK: Verify actual course completion (lessons + quizzes + reviews)
            $isCourseCompleted = $this->checkCourseCompletion($user->id, $courseId);
            
            if (!$isCourseCompleted) {
                Log::warning("Course Not Completed: User {$user->id} tried to download certificate for Course {$courseId} without completing it.");
                return response()->json(['error' => 'You have not completed this course yet.'], 403);
            }

            // Load the certificate view and pass data to it
            // Use 'certificate' view (ensure checking if it needs layout)
            $pdf = Pdf::loadView('certificate', compact('user', 'course'));

            // Set paper size and orientation
            $pdf->setPaper('A4', 'landscape');

            // Generate the file name with the course title
            $title = $course->title ?? 'Certificate';
            $fileName = 'certificate_' . str_replace(' ', '_', $title) . '.pdf';

            // Ensure certificate record exists and update download timestamp
            $this->trackCertificateDownload($user->id, $course);

            Log::info("Generating PDF download...");
            // Return the PDF as a download with the course title in the file name
            return $pdf->download($fileName);

        } catch (\Exception $e) {
            Log::error("Error in downloadCertificate: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Check if user has completed the course (lessons + quizzes + reviews)
     */
    protected function checkCourseCompletion($userId, $courseId): bool
    {
        // Check lessons completion
        $lessonStats = $this->getLessonStats($userId, $courseId);
        $allLessonsDone = $lessonStats['completed_count'] >= $lessonStats['total_count'] && $lessonStats['total_count'] > 0;
        
        // Check quizzes completion
        $quizStats = $this->getQuizStats($userId, $courseId);
        $allQuizzesDone = $quizStats['completed_count'] >= $quizStats['total_count'] && $quizStats['total_count'] > 0;
        
        // Check review completion (if required)
        $reviewStats = $this->getReviewStats($userId, $courseId);
        $reviewCompleted = $reviewStats['completed'];
        $reviewRequired = $reviewStats['required'];
        
        // Course is complete only if lessons, quizzes, and review (if required) are done
        return $allLessonsDone && $allQuizzesDone && (!$reviewRequired || $reviewCompleted);
    }

    /**
     * Get lesson statistics for a user and course
     */
    private function getLessonStats(int $userId, int $courseId): array
    {
        $totalLessons = DB::table('lessons')
            ->join('sections', 'lessons.section_id', '=', 'sections.id')
            ->where('sections.course_id', $courseId)
            ->count();
        
        $completedLessons = 0;
        
        if ($totalLessons > 0) {
            $completedLessons = DB::table('lesson_progress')
                ->join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
                ->join('sections', 'lessons.section_id', '=', 'sections.id')
                ->where('sections.course_id', $courseId)
                ->where('lesson_progress.user_id', $userId)
                ->where('lesson_progress.is_completed', 1)
                ->count();
        }

        return [
            'total_count' => $totalLessons,
            'completed_count' => $completedLessons
        ];
    }

    /**
     * Get quiz statistics for a user and course
     */
    private function getQuizStats(int $userId, int $courseId): array
    {
        // Collect sections for course
        $sections = DB::table('sections')->where('course_id', $courseId)->pluck('id');
        
        $totalQuizzes = 0;
        $completedQuizzes = 0;

        foreach ($sections as $sectionId) {
            $questionIds = DB::table('questions')
                ->where('section_id', $sectionId)
                ->pluck('id');

            // Only count sections that actually have questions
            if ($questionIds->count() === 0) {
                continue;
            }
            
            $totalQuizzes++;

            $answeredCount = DB::table('quiz_progress')
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

    /**
     * Get review statistics for a user and course
     */
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

    protected function trackCertificateDownload($userId, $course)
    {
        try {
            // Check if certificate record already exists
            $certificate = UserCertificate::where('user_id', $userId)
                ->where('course_id', $course->id)
                ->first();

            if ($certificate) {
                // Update the timestamp
                $certificate->touch();
            } else {
                // Create new certificate record
                $certificate = UserCertificate::create([
                    'user_id' => $userId,
                    'course_id' => $course->id,
                    'title' => $course->title,
                    'image' => 'assets/img/certificate_background.png',
                ]);
            }

            return $certificate;
        } catch (\Exception $e) {
            Log::error('Failed to track certificate download', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'course_id' => $course->id
            ]);
        }
    }



    public function showCertificates()
    {
        $userId = auth()->id();

        // Fetch courses where all lessons are completed by the user
        $completedCourses = Course::whereHas('lessons', function ($query) use ($userId) {
            $query->whereHas('progress', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('is_completed', true);
            });
        })->get();

        return view('certificate.index', ['courses' => $completedCourses]);
    }



    public function Certificate(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user_id = auth()->id();
        if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
        // $user_id = $request->input('user_id');

        // Fetch all courses
        $courses = Course::all();

        $completedCourses = [];

        foreach ($courses as $course) {
            $allLessonsCompleted = true;

            // Fetch all lessons for the course
            $lessons = $course->sections->flatMap->lessons;

            // If no lessons are found for a course, consider it incomplete
            if ($lessons->isEmpty()) {
                $allLessonsCompleted = false;
            }

            foreach ($lessons as $lesson) {
                // Check if the lesson is completed by the user
                $lessonCompleted = $lesson->progress()
                    ->where('user_id', $user_id)
                    ->where('is_completed', true)
                    ->exists();

                if (!$lessonCompleted) {
                    $allLessonsCompleted = false;
                    break;
                }
            }

            if ($allLessonsCompleted) {
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
    public function fetchCourses(Request $request)
    {
        $ageGroup = $request->query('age_group', 'any');

        if ($ageGroup === 'any') {
            $courses = Course::all();
        } else {
            $courses = Course::where('age_group', $ageGroup)->get();
        }

        // Apply standardization logic matching HomeController & AppServiceProvider
        $courses = $courses->map(function ($course) {
             $status = $course->getDynamicStatus();
             $course->cta_text = $status['cta_text'];
             $course->is_paid = $status['is_paid'];
             $course->price_usd = $course->price_usd; // Explicitly load
             
             // Check if price_usd is valid
             $course->formatted_price = convertPrice($course->price, true, $course->price_usd);
             return $course;
        });

        // Return courses as a JSON response
        return response()->json(['code' => 200, 'courses' => $courses]);
    }
    public function showCourses($ageGroup = 'any')
    {
        if ($ageGroup == 'any') {
            $courses = Course::all();
        } else {
            $courses = Course::where('age_group', $ageGroup)->get();
        }

        return view('courses.index', compact('courses'));
    }

    public function profile()
    {

        $user = Auth::user();

        // Fetch the purchased courses using the user progress modal
        $purchasedCourses = UserProgress::with('course')
            ->where('user_id', auth()->id())
            ->where('payment_status', 1)
            ->get()
            ->pluck('course');

        // Retrieve SEO meta data
        $seo = SeoMeta::where('page_type', 'profile')->first();

        // Set SEO metadata
        if ($seo) {
            SEO::SetTitle("{$user->name}'s {$seo->title}");
            SEO::setDescription("View {$user->name}'s {$seo->description}");
            if ($seo->keywords) {
                SEO::metatags()->addKeyword(explode(',', $seo->keywords));
            }
        } else {
            // Fallback seo
            SEO::setTitle("{$user->name}'s Profile - Little Farmers Academy");
            SEO::setDescription("View {$user->name}'s profile, course history, and progress in Little Farmers Academy.");
            SEO::metatags()->addKeyword(['student profile', 'farming courses', 'learning progress']);
        }

        // Fetch active discount/reward codes for the user
        $userRewards = $user->discountCodes()
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->get();

        // Check if referral system is enabled
        $isReferralEnabled = ReferralSetting::getValue('is_enabled', '1') == '1';

        // Pass the courses to the view
        $response = response()->view('profile', compact('purchasedCourses', 'user', 'seo', 'userRewards', 'isReferralEnabled'));

        // Using for the cache removeing from the browser
        return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}
