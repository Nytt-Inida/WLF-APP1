<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\User;
use App\Models\ArticleProgress;
use App\Models\QuizProgress; 
use App\Models\UserProgress; 
use App\Models\Certificate;
use App\Models\LessonProgress;
use App\Models\CourseDetail;
use App\Models\Question;
use App\Models\Review;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Validator;

class ApiCourseDetailController extends Controller
{
   
        public function __construct()
        {
            Log::info('CourseDetailController instantiated');
        }
    
        public function store(Request $request)
        {
    
            $request->validate([
                'title' => 'required|string|max:255',
                'course_id' => 'required|integer|exists:courses,id',
                'chapter_title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'video_url' => 'nullable|url',
                'user_id' => 'required|integer|exists:users,id',
            ]);
    
            // Check if the user ID matches the authenticated user ID
            if ($request->user()->id !== (int) $request->user_id) {
                Log::warning('Unauthorized user ID: ', ['user_id' => $request->user_id]);
                return response()->json(['message' => 'Unauthorized user ID.'], 403);
            }
    
            // Attempt to create the course detail
            try {
                $courseDetail = CourseDetail::create($request->all());
            } catch (\Exception $e) {
                Log::error('Error creating course detail: ', ['error' => $e->getMessage()]);
                return response()->json(['message' => 'Error creating course detail.'], 500);
            }
    
            return response()->json([
                'message' => 'Course detail created successfully.',
                'course_detail' => $courseDetail
            ], 201);
        }
        
public function show(Request $request)
{
    // Validate the incoming request - user_id is optional for guest access
    $validator = Validator::make($request->all(), [
        'user_id' => 'nullable|integer', // Optional - don't check existence, handle in code
        'course_id' => 'required|exists:courses,id', // Validate course_id as required and it must exist in the courses table
    ]);

    // Check if the validation fails
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user_id = $request->input('user_id');
    $course_id = $request->input('course_id');
    
    // Verify user exists if user_id is provided, otherwise treat as guest
    if ($user_id) {
        $userExists = \Illuminate\Support\Facades\DB::table('users')->where('id', $user_id)->exists();
        if (!$userExists) {
            $user_id = null; // Treat as guest if user doesn't exist
        }
    }


    // Fetch the course with details, regardless of whether they match the user_id or not
    $course = Course::with(['details.lessons'])->find($course_id);

    // Check if the course exists (just to be safe)
    if (!$course) {
        return response()->json(['message' => 'Course not found'], 404);
    }

    // Check if the course is marked as favorite by the user (only if user_id is provided)
    $isFavourite = 0;
    if ($user_id) {
        $isFavourite = DB::table('favorites')
            ->where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->exists() ? 1 : 0;
    }

    // Check if the user has purchased the course using the UserProgress table (only if user_id is provided)
    $isPurchase = false;
    if ($user_id) {
        $isPurchase = UserProgress::where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->where('payment_status', 1) // Assuming 1 indicates that the payment was successful
            ->exists();
    }

    // Fetch course ratings and reviews
    $reviews = Review::where('course_id', $course_id)
        ->where('is_approved', true)
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->get();
    
    $totalReviews = $reviews->count();
    $averageRating = $totalReviews > 0 
        ? round($reviews->avg('rating'), 1) 
        : 0.0;
    
    // Calculate rating distribution
    $ratingDistribution = [
        5 => $reviews->where('rating', 5)->count(),
        4 => $reviews->where('rating', 4)->count(),
        3 => $reviews->where('rating', 3)->count(),
        2 => $reviews->where('rating', 2)->count(),
        1 => $reviews->where('rating', 1)->count(),
    ];
    
    // Get recent reviews (limit to 5 for display)
    $recentReviews = $reviews->take(5)->map(function ($review) {
        return [
            'id' => $review->id,
            'user_name' => $review->name ?? ($review->user->name ?? 'Anonymous'),
            'rating' => $review->rating,
            'message' => $review->message,
            'created_at' => $review->created_at->format('Y-m-d H:i:s'),
        ];
    });

    // Get related courses (same age group, excluding current course, limit 3)
    $relatedCourses = Course::where('age_group', $course->age_group)
        ->where('id', '!=', $course_id)
        ->limit(3)
        ->get()
        ->map(function ($relatedCourse) use ($user_id) {
            $isFavourite = 0;
            if ($user_id) {
                $isFavourite = DB::table('favorites')
                    ->where('user_id', $user_id)
                    ->where('course_id', $relatedCourse->id)
                    ->exists() ? 1 : 0;
            }
            
            return [
                'id' => $relatedCourse->id,
                'title' => $relatedCourse->title,
                'thumbnail' => $relatedCourse->image ?? "",
                'price' => $relatedCourse->price,
                'age_group' => $relatedCourse->age_group,
                'isFavourite' => $isFavourite,
            ];
        });

    // Build course tags/categories from age group
    $tags = [];
    if ($course->age_group) {
        $tags[] = [
            'name' => 'Age ' . $course->age_group,
            'slug' => strtolower(str_replace(' ', '-', $course->age_group)),
        ];
    }
    
    // Add general tags based on course title/description
    $titleLower = strtolower($course->title);
    if (stripos($titleLower, 'farming') !== false) {
        $tags[] = ['name' => 'Farming', 'slug' => 'farming'];
    }
    if (stripos($titleLower, 'agriculture') !== false) {
        $tags[] = ['name' => 'Agriculture', 'slug' => 'agriculture'];
    }
    if (stripos($titleLower, 'robotics') !== false) {
        $tags[] = ['name' => 'Robotics', 'slug' => 'robotics'];
    }
    if (stripos($titleLower, 'ai') !== false || stripos($titleLower, 'artificial') !== false) {
        $tags[] = ['name' => 'AI', 'slug' => 'ai'];
    }
    
    // Instructor information (general team info)
    $instructors = [
        [
            'name' => 'Expert Team',
            'role' => 'Little Farmers Academy',
            'description' => 'Our courses are developed by experts in food science, agriculture, child education, and sustainability. Lessons are simplified and tested with real students before being launched.',
        ],
    ];

    // Build the response including course details and lessons
    $response = [
        'course_id' => $course->id,
        'title' => $course->title,
        'thumbnail' => $course->image ?? "",
        'price' => $course->price, // INR price for Indian users
        'price_usd' => $course->price_usd ?? '29', // USD price for international users
        'isFavourite' => $isFavourite, // Show 1 if favorite, otherwise 0
        'isPurchase' => $isPurchase, // Show true if purchased, otherwise false
        'payment_status' => $user_id ? \App\Models\User::find($user_id)->payment_status : 0,
        'pending_course_id' => $user_id ? \App\Models\User::find($user_id)->pending_course_id : null,
        'ratings' => [
            'average' => $averageRating,
            'total' => $totalReviews,
            'distribution' => $ratingDistribution,
        ],
        'reviews' => $recentReviews,
        'related_courses' => $relatedCourses,
        'tags' => $tags,
        'instructors' => $instructors,
        'age_group' => $course->age_group ?? '',
        'number_of_classes' => $course->number_of_classes ?? 0,
        'details' => $course->details->map(function ($detail) {
            return [
                'id' => $detail->id,
                'title' => $detail->title,
                'chapter_title' => $detail->chapter_title,
                'description' => $detail->description,
                'video_url' => $detail->video_url,
                'lessons' => $detail->lessons->map(function ($lesson) {
                    // Check if duration is numeric and convert if it is
                    if (is_numeric($lesson->duration)) {
                        $minutes = floor($lesson->duration / 60);
                        $seconds = $lesson->duration % 60;
                        $formatted_duration = sprintf('%02d:%02d', $minutes, $seconds);
                    } else {
                        // If duration is not numeric, leave it as is or handle accordingly
                        $formatted_duration = $lesson->duration;
                        Log::warning('Non-numeric duration found for lesson ID: ' . $lesson->id);
                    }

                    return [
                        'id' => $lesson->id,
                        'course_id' => $lesson->course_id,
                        'course_detail_id' => $lesson->course_detail_id,
                        'title' => $lesson->title,
                        'duration' => $formatted_duration,
                    ];
                }),
            ];
        }),
    ];

    return response()->json($response, 200);
}

        public function destroy($id)
        {
    
            $courseDetail = CourseDetail::find($id);
    
            if (!$courseDetail) {
                Log::warning('Course detail not found: ', ['id' => $id]);
                return response()->json(['message' => 'Course detail not found'], 404);
            }
    
            try {
                $courseDetail->delete();
            } catch (\Exception $e) {
                Log::error('Error deleting course detail: ', ['error' => $e->getMessage()]);
                return response()->json(['message' => 'Error deleting course detail.'], 500);
            }
    
            return response()->json(['message' => 'Course detail deleted successfully']);
        }
        public function postLesson(Request $request)
    {

        $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'course_detail_id' => 'required|integer|exists:course_details,id',
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer', // duration in seconds
        ]);

        // Attempt to create the lesson
        try {
            $lesson = Lesson::create($request->all());
        } catch (\Exception $e) {
            Log::error('Error creating lesson: ', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error creating lesson.'], 500);
        }

        return response()->json([
            'message' => 'Lesson created successfully.',
            'lesson' => $lesson
        ], 201);
    }
    
 public function fetchCourseSectionsWithLessons(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'user_id' => 'nullable|integer', // Optional - don't check existence, handle in code
    ]);

    $course_id = $request->input('course_id');
    $user_id = $request->input('user_id');
    
    // Verify user exists if user_id is provided, otherwise treat as guest
    if ($user_id) {
        $userExists = \Illuminate\Support\Facades\DB::table('users')->where('id', $user_id)->exists();
        if (!$userExists) {
            $user_id = null; // Treat as guest if user doesn't exist
        }
    }

    // Fetch course with sections and lessons, articles, and quizzes (questions)
    $course = Course::with(['sections.lessons', 'sections.articles', 'sections.questions'])->find($course_id);

    if (!$course) {
        return response()->json(['code' => 404, 'message' => 'Course not found'], 404);
    }

    // Check if user has purchased the course
    $hasPurchased = false;
    if ($user_id) {
        $hasPurchased = \Illuminate\Support\Facades\DB::table('user_progress')
            ->where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->where('payment_status', 1)
            ->exists();
    }

    // Find the first lesson ID for preview access
    $firstSection = $course->sections()->orderBy('id', 'asc')->first();
    $firstLessonId = null;
    if ($firstSection) {
        $firstLesson = $firstSection->lessons()->orderBy('id', 'asc')->first();
        $firstLessonId = $firstLesson ? $firstLesson->id : null;
    }

    // Format the response
    $response = [
        'course_title' => $course->title,
        'sections' => $course->sections->map(function ($section) use ($user_id, $hasPurchased, $firstLessonId, $course_id) {
            return [
                'section_title' => $section->title,
                'items' => $section->lessons->map(function ($lesson) use ($user_id, $hasPurchased, $firstLessonId, $course_id) {
                    // Ensure duration is numeric before conversion
                    if (is_numeric($lesson->duration)) {
                        $minutes = floor($lesson->duration / 60);
                        $seconds = $lesson->duration % 60;
                        $formatted_duration = sprintf('%02d:%02d mins', $minutes, $seconds);
                    } else {
                        // Handle non-numeric duration
                        $formatted_duration = $lesson->duration;
                        Log::warning('Non-numeric duration found for lesson ID: ' . $lesson->id);
                    }

                    // Check if the lesson is complete (only for authenticated users)
                    $is_complete = false;
                    if ($user_id) {
                        $is_complete = DB::table('lesson_progress')
                            ->where('lesson_id', $lesson->id)
                            ->where('user_id', $user_id)
                            ->exists();
                    }

                    // Determine if lesson is accessible (first lesson is always accessible, others require purchase)
                    $isAccessible = false;
                    if ($lesson->id === $firstLessonId) {
                        $isAccessible = true; // First lesson is free preview
                    } elseif ($hasPurchased) {
                        $isAccessible = true; // Purchased courses have access to all lessons
                    }

                    return [
                        'lesson_id' => $lesson->id, // Include lesson_id
                        'itemName' => $lesson->title,
                        'itemType' => 'video',
                        'lesson_duration' => $formatted_duration,
                        'lesson_video_url' => $lesson->video_url,
                        'isComplete' => $is_complete,
                        'isAccessible' => $isAccessible, // New field to indicate if lesson can be accessed
                        'isVideo' => true,
                        'isArticle' => false,
                        'isQuiz' => false,
                    ];
                })->merge($section->articles
                    ->filter(function ($article) {
                        // Filter out "sample" articles - not shown on website
                        $title = strtolower($article->title ?? '');
                        return stripos($title, 'sample') === false;
                    })
                    ->map(function ($article) use ($user_id, $hasPurchased, $course_id) {
                    // Articles require purchase
                    $isAccessible = $hasPurchased;
                    
                    // Check if an article is completed by the user
                    $is_complete = false;
                    if ($user_id) {
                        $is_complete = DB::table('article_progress')
                            ->where('article_id', $article->id)
                            ->where('user_id', $user_id)
                            ->exists();
                    }

                    return [
                        'article_id' => $article->id, // Include article_id
                        'itemName' => $article->title,
                        'itemType' => 'article',
                        'article_url' => $article->link ?? "",  // Add article URL here
                        'lesson_duration' => "",
                        'lesson_video_url' => "",
                        'isComplete' => $is_complete,
                        'isAccessible' => $isAccessible,
                        'isVideo' => false,
                        'isArticle' => true,
                        'isQuiz' => false,
                    ];
                }))->merge($section->questions
                    ->filter(function ($question) {
                        // Filter out "sample test" - not shown on website
                        $title = strtolower($question->quiz_title ?? '');
                        return stripos($title, 'sample') === false;
                    })
                    ->values() // Re-index the collection after filtering
                    ->sortBy('id') // Sort by ID to maintain order
                    ->take(1)
                    ->map(function ($question) use ($user_id, $hasPurchased, $course_id) {
                    // Quizzes require purchase
                    $isAccessible = $hasPurchased;
                    
                    $is_complete = false;
                    if ($user_id) {
                        $is_complete = DB::table('quiz_progress')
                            ->where('question_id', $question->id)
                            ->where('user_id', $user_id)
                            ->exists();
                    }

                    return [
                        'question_id' => $question->id, // Include question_id
                        'itemName' => $question->quiz_title,
                        'itemType' => 'quiz',
                        'lesson_duration' => "",
                        'lesson_video_url' => "",
                        'isComplete' => $is_complete,
                        'isAccessible' => $isAccessible,
                        'isVideo' => false,
                        'isArticle' => false,
                        'isQuiz' => true,
                    ];
                })),
            ];
        }),
    ];

    return response()->json($response, 200);
}


public function markLessonAsCompleted(Request $request)
{
    // Advanced validation using Validator facade
    $validator = Validator::make($request->all(), [
        'user_id' => [
            'required',
            'exists:users,id',
            function ($attribute, $value, $fail) {
                if ($value <= 0) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            },
        ],
        'lesson_id' => [
            'nullable',
            'exists:lessons,id',
            function ($attribute, $value, $fail) {
                if ($value !== null && $value <= 0) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            },
        ],
        'article_id' => [
            'nullable',
            'exists:articles,id',
            function ($attribute, $value, $fail) {
                if ($value !== null && $value <= 0) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            },
        ],
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user_id = $request->input('user_id');
    $lesson_id = $request->input('lesson_id');
    $article_id = $request->input('article_id');

    // Mark the lesson as completed, if lesson_id is provided
    if ($lesson_id) {
        LessonProgress::updateOrCreate(
            ['user_id' => $user_id, 'lesson_id' => $lesson_id],
            ['is_completed' => true]
        );
    }

    // Mark the article as completed, if article_id is provided
    if ($article_id) {
        ArticleProgress::updateOrCreate(
            ['user_id' => $user_id, 'article_id' => $article_id],
            ['is_completed' => true]
        );
    }

    return response()->json(['message' => 'Item marked as completed'], 200);
}

public function getCourseDetails(Request $request)
{
    // Validate the input
    $validatedData = $request->validate([
        'user_id' => 'required|exists:users,id',
        'course_id' => 'required|exists:courses,id',
    ]);

    $user_id = $validatedData['user_id'];
    $course_id = $validatedData['course_id'];

    // Fetch the course with sections and lessons, including progress
    $course = Course::with(['sections.lessons' => function ($query) use ($user_id) {
        $query->with(['lessonProgress' => function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        }]);
    }])->find($course_id);

    // If the course is not found, return an error response
    if (!$course) {
        return response()->json(['message' => 'Course not found'], 404);
    }

    // Format the response
    $response = [
        'course_title' => $course->title,
        'sections' => $course->sections->map(function ($section) use ($user_id) {
            return [
                'section_title' => $section->title,
                'lessons' => $section->lessons->map(function ($lesson) {
                    $duration = gmdate("i:s", $lesson->duration); // Convert duration to mins and secs
                    $is_completed = $lesson->lessonProgress ? (bool) $lesson->lessonProgress->is_completed : false;
                    return [
                        'lesson_title' => $lesson->title,
                        'lesson_duration' => $duration . ' mins',
                        'lesson_video_url' => $lesson->video_url,
                        'is_completed' => $is_completed,
                    ];
                }),
            ];
        }),
    ];

    return response()->json($response, 200);
}

public function generateCertificate(Request $request)
{
   // Validate the incoming request
$validator = Validator::make($request->all(), [
    'user_id' => 'required|exists:users,id',
]);

if ($validator->fails()) {
    return response()->json(['errors' => $validator->errors()], 422);
}

$user_id = $request->input('user_id');

// Fetch all courses
$courses = Course::all();

$completedCourses = [];

foreach ($courses as $course) {
    $allLessonsCompleted = true;

    // Fetch all lessons for the course
    $lessons = Lesson::whereHas('section', function($query) use ($course) {
        $query->where('course_id', $course->id);
    })->get();

    // If no lessons are found for a course, consider it incomplete
    if ($lessons->isEmpty()) {
        $allLessonsCompleted = false;
    }

    foreach ($lessons as $lesson) {
        // Check if the lesson is completed by the user
        $lessonCompleted = LessonProgress::where('lesson_id', $lesson->id)
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
    return response()->json(['message' => 'No completed'], 404);
}

return response()->json([
    'message' => 'Completed courses fetched successfully',
    'completed_courses' => $completedCourses
], 200);
}

public function getLessonsByCourse(Request $request)
    {
        // Validate the incoming request to ensure 'course_id' is provided
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course_id = $request->input('course_id');

        // Fetch lessons associated with the given course_id
        $lessons = Lesson::where('course_id', $course_id)->get();

        // Return the lessons as a JSON response
        return response()->json($lessons);
    }
    
    public function updateUserProgress($user_id, $course_id)
{

    // Check if all lessons for this course are completed by the user
    $totalLessons = DB::table('lessons')
        ->where('course_id', $course_id)
        ->count();

    $completedLessons = DB::table('lesson_progress')
        ->where('user_id', $user_id)
        ->where('is_completed', 1)
        ->count();

    // Check if all quizzes for this course are completed by the user
    $totalQuestions = DB::table('questions')
        ->where('course_id', $course_id)
        ->count();

    $correctQuizzes = DB::table('quiz_progress')
        ->where('user_id', $user_id)
        ->where('is_correct', 1)
        ->count();

    // If all lessons and quizzes are completed, mark the course as completed
    if ($totalLessons == $completedLessons && $totalQuestions == $correctQuizzes) {
        DB::table('user_progress')
            ->where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->update(['is_completed' => 1]);

    }
}

    }