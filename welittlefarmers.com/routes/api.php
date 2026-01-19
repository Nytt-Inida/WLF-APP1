<?php

use Illuminate\Http\Request;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ApiCourseDetailController;
use App\Http\Controllers\Auth\TestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiPaymentController;
use App\Http\Controllers\Api\ApiTestController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\ApiVideoStreamController;
use App\Http\Controllers\ManualPaymentController;
use App\Http\Controllers\Api\ApiBookingController;
use App\Http\Controllers\Api\ApiBlogController;
use App\Http\Controllers\Api\ApiCourseCompletionController;
use App\Http\Controllers\Api\ApiReviewController;
use App\Http\Controllers\Api\ApiPayPalController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
use App\Http\Controllers\API\RegisterController;



Route::post('register', [AuthController::class, 'register']);
Route::post('send-otp', [AuthController::class, 'sendOtp']);
Route::post('login', [AuthController::class, 'login']);
Route::post('signup', [AuthController::class, 'signup']);

// Blog Routes
Route::get('blogs', [ApiBlogController::class, 'index']);
Route::get('blogs/{id}', [ApiBlogController::class, 'show']);
Route::get('blogs/tag/{slug}', [ApiBlogController::class, 'byTag']);
Route::get('blogs/tags/all', [ApiBlogController::class, 'getAllTags']);

// Test route for Blog API verification
Route::get('blogs-test', function() {
    return response()->json([
        'success' => true,
        'message' => 'Blog API endpoint is accessible',
        'timestamp' => now()
    ]);
});

// Video streaming endpoints

Route::get('video/stream/{token}', [ApiVideoStreamController::class, 'streamVideo']);

// Review Routes for Mobile App - Uses token-based authentication (Sanctum)
// These routes handle authentication manually inside the controller
Route::get('/review/questions/{courseId}', [ApiReviewController::class, 'getQuestions']);
Route::post('/review/submit/{courseId}', [ApiReviewController::class, 'submitAnswers']);
Route::get('/review/check/{courseId}', [ApiReviewController::class, 'checkCompletion']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses', [CourseController::class, 'show']);
    Route::post('courses', [CourseController::class, 'store']);
    Route::post('courses/submit-test', [CourseController::class, 'submitTest']);

    Route::post('course-details', [ApiCourseDetailController::class, 'store']);
    Route::get('course-details', [ApiCourseDetailController::class, 'show']);
    Route::delete('course-details/{id}', [ApiCourseDetailController::class, 'destroy']);
    
    Route::post('video/generate-url/{lessonId}', [ApiVideoStreamController::class, 'generateSignedUrl']);

Route::post('/process-payment', [ApiPaymentController::class, 'process']);
    
    Route::post('/tests/show', [ApiTestController::class, 'show'])->name('api.tests.show');
    Route::post('/questions/fetch', [ApiTestController::class, 'fetchQuestions'])->name('api.questions.fetch');

    
    Route::post('/tests', [ApiTestController::class, 'storeTest']);
    Route::post('/questions', [ApiTestController::class, 'storeQuestion']);
    Route::post('/tests/by-course', [ApiTestController::class, 'getTestsByCourse']);
    Route::post('/questions/by-test', [ApiTestController::class, 'getQuestionsByTest']);



    Route::post('/fetch-courses-by-exact-age-group', [CourseController::class, 'fetchCoursesByAgeGroup']);
  Route::post('/fetch-popular-courses', [CourseController::class, 'fetchPopularCourses']);
  
  Route::post('/courses/favorite', [CourseController::class, 'updateFavoriteStatus']);
Route::post('/courses/favorites', [CourseController::class, 'fetchFavoriteCourses']);
Route::post('/courses/purchased', [CourseController::class, 'fetchPurchasedCourses']);
Route::get('/profile', [AuthController::class, 'getProfile']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/courses/rate', [CourseController::class, 'giveRating']);
Route::post('/course-details/lessons', [ApiCourseDetailController::class, 'postLesson']);
Route::post('/course/show', [ApiCourseDetailController::class, 'show']);
  Route::post('/course/sections', [ApiCourseDetailController::class, 'fetchCourseSectionsWithLessons']);

    // Mark a lesson as completed
    Route::post('/lesson/complete', [ApiCourseDetailController::class, 'markLessonAsCompleted']);
    // Fetch detailed course information with sections and lessons
Route::get('/course-details', [ApiCourseDetailController::class, 'getCourseDetails']);


  Route::post('/testresult', [QuizController::class, 'viewQuizResults']);
    Route::post('/fetch-tests', [QuizController::class, 'getTestsForCourse']);
    Route::post('/submit-test', [QuizController::class, 'submitTest']);
    Route::get('/search/courses', [SearchController::class, 'searchCourses']);
    Route::get('/search/suggestions', [SearchController::class, 'getSuggestions']);
     Route::get('/courses/related', [SearchController::class, 'getRelatedCourses']);
      Route::get('/completed-items', [CertificateController::class, 'getCompletedItems']);
      Route::post('lessons-by-course', [ApiCourseDetailController::class, 'getLessonsByCourse']);
     Route::post('mark-lesson-completed', [ApiCourseDetailController::class, 'markLessonAsCompleted']);
           Route::post('generate-certificate', [ApiCourseDetailController::class, 'generateCertificate']);
           Route::post('upload-result', [QuizController::class, 'uuploadResultAfterLastQuestion']);
           Route::post('/update-profile', [AuthController::class, 'updateProfile']);
Route::post('/purchase-course', [ApiPaymentController::class, 'purchaseWithoutPayment']);
Route::post('/payment/manual-complete', [ManualPaymentController::class, 'completeOrder']);
    Route::post('/book-live-course', [ApiBookingController::class, 'submitBooking']);

    // Certificate Check Completion - API specific (excludes sample tests to match app display)
    Route::post('/certificate/check-completion', [ApiCourseCompletionController::class, 'checkCompletion']);
    
    // Check coupon code (with authentication)
    Route::post('/check-coupon', [ApiPaymentController::class, 'checkCoupon']);
    
    // Get discounted price (including referral discount from signup)
    Route::post('/get-discounted-price', [ApiPaymentController::class, 'getDiscountedPrice']);
    
    // Lesson position and navigation routes
    Route::post('/lessons/save-position', [\App\Http\Controllers\Auth\LessonController::class, 'savePosition']);
    Route::get('/lessons/get-position/{lessonId}', [\App\Http\Controllers\Auth\LessonController::class, 'getPosition']);
    Route::get('/lessons/last-watched/{courseId}', [\App\Http\Controllers\Auth\LessonController::class, 'getLastWatched']);
    Route::get('/lessons/next/{lessonId}', [\App\Http\Controllers\Auth\LessonController::class, 'nextLesson']);

    // PayPal Payment Routes (International Users - USD)
    Route::post('/paypal/create-order', [ApiPayPalController::class, 'createOrder']);
    Route::post('/paypal/verify-payment', [ApiPayPalController::class, 'verifyPayment']);
    Route::post('/paypal/capture-order', [ApiPayPalController::class, 'captureOrder']);

});