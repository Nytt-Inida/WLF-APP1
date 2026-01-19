<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\UserRegistrationController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\CourseController;
use App\Http\Controllers\Auth\LessonController;
use App\Http\Controllers\Auth\PaymentController;
use App\Http\Controllers\Auth\HomeController;
use App\Http\Controllers\Auth\QuizQuestionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\BookingController;
use App\Http\Controllers\Auth\VideoStreamController;
use App\Http\Controllers\Auth\ReviewController;

use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminCertificateController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ManualPaymentController;
use App\Http\Controllers\SubtitleController;
use App\Http\Controllers\Admin\AdminReferralController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminEnrollmentController;



Route::get('/about-us', function () { return redirect('/about', 301); });
Route::get('/contact-us', function () { return redirect('/contact', 301); });
Route::get('/blog', function () { return redirect()->route('blogs.index', [], 301); });
Route::get('/course', function () { return redirect()->route('courses.index', [], 301); });

Route::get('/', [HomeController::class, 'index'])->name('home');

// Redirects for duplicate home paths
Route::get('/home', function () {
    return redirect('/', 301);
});
Route::get('/index', function () {
    return redirect('/', 301);
});
Route::get('/index.html', function () {
    return redirect('/', 301);
});

// Registration routes
Route::get('/register', [UserRegistrationController::class, 'showSignupForm'])->name('register.form');
Route::post('/register', [UserRegistrationController::class, 'signup'])->name('register.submit');


// Login routes
Route::get('/login', [UserRegistrationController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [UserRegistrationController::class, 'login'])->name('login');
Route::post('/send-otp', [UserRegistrationController::class, 'sendOtp'])->name('send.otp');

Route::get('/getTest/{test_id}', [CourseController::class, 'getTest'])->name('getTest');
Route::post('/submitTest', [CourseController::class, 'submitTest'])->name('submitTest');
Route::get('/downloadCertificate/{courseId}', [CourseController::class, 'downloadCertificate'])->name('downloadCertificate');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//Lesson progress
Route::middleware(['auth'])->post('/lessons/progress', [LessonController::class, 'getLessonProgress'])->name('lessons.progress');

Route::post('/lesson/complete', [LessonController::class, 'complete'])->name('lesson.complete');
Route::post('/quiz/submit', [QuizQuestionController::class, 'submitQuiz'])->name('quiz.submit');
Route::get('/quiz/questions', [QuizQuestionController::class, 'getQuizQuestions'])->name('quiz.getQuestions');
Route::post('/quiz/fetch', [QuizQuestionController::class, 'fetchQuizQuestions'])->name('quiz.fetch');
Route::post('/quiz/save-progress', [QuizQuestionController::class, 'saveProgress'])->name('quiz.saveProgress');

// Review routes
Route::middleware(['auth'])->group(function () {
    Route::get('/review/{courseId}/check', [ReviewController::class, 'checkCompletion'])->name('review.check');
    Route::get('/review/{courseId}/questions', [ReviewController::class, 'getQuestions'])->name('review.questions');
    Route::post('/review/{courseId}/submit', [ReviewController::class, 'submitAnswers'])->name('review.submit');
});


// web.php
Route::post('/lesson/check-completion-status', [CourseController::class, 'checkCompletionStatus'])->name('lesson.checkCompletionStatus');
Route::get('/course_details/{course}', [CourseController::class, 'showCourseDetails'])->name('course.details');
Route::get('/course/{courseId}/certificate/download', [CourseController::class, 'downloadCertificate'])->name('certificate.download');

Route::get('/my-certificate', [CourseController::class, 'show'])->name('my.certificate');

// In web.php
Route::get('/my-certificate', [CourseController::class, 'showCertificates'])->name('my.certificate');






Route::post('generate', [CourseController::class, 'Certificate']);

Route::post('/article/complete', [LessonController::class, 'complete'])->name('article.complete');

Route::post('/lessons/unlocked', [LessonController::class, 'getUnlockedLessons'])->name('lessons.unlocked');
Route::post('/certificate/check-completion', [LessonController::class, 'checkCompletion'])->name('certificate.checkCompletion');

Route::get('/generate-certificate', [LessonController::class, 'generateCertificate'])->name('auth.generateCertificate');


Route::post('/tests/completed', [LessonController::class, 'getCompletedTests'])->name('tests.completed');
Route::get('/fetch-courses-by-age-group', [LessonController::class, 'fetchCoursesByAgeGroup'])->name('fetch.courses');
Route::get('/profile', [CourseController::class, 'profile'])->name('profile')->middleware('auth');

Route::post('/logout', [CourseController::class, 'perform'])->name('logout');
// Other routes
Route::get('/fetch-courses', [CourseController::class, 'fetchCoursesByAgeGroup'])->name('courses.fetchByAgeGroup');
Route::get('/signup', [UserRegistrationController::class, 'showSignupForm'])->name('signup.form');
Route::post('/signup', [UserRegistrationController::class, 'signup'])->name('signup');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/course/{id}', [CourseController::class, 'show'])->name('course.details');
Route::get('/instructor/{id}', [CourseController::class, 'show'])->name('instructor.details');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.all');
// Route::get('/about', [CourseController::class, 'index'])->name('about');
Route::get('/about', [CourseController::class, 'about'])->name('about');


Route::get('/course_details/{id}', [CourseController::class, 'show'])->name('course.details');
// Test route for web
Route::get('/web-test', function () {
    return response()->json(['message' => 'Web route is working']);
});


Route::get('/generate-certificate', [LessonController::class, 'generateCertificate'])->name('auth.generateCertificate');


Route::post('/articles/unlocked', [LessonController::class, 'fetchUnlockedArticles'])->name('articles.unlocked');
Route::post('/article/complete', [LessonController::class, 'completeArticle'])->name('article.complete');


Route::post('/certificates/fetch', [CourseController::class, 'showCertificates'])->name('certificates.fetch');
Route::get('/certificate/download/{course}', [CourseController::class, 'downloadCertificate'])->name('certificate.download');


Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('profile.update');
Route::get('/updateprofile', [AuthController::class, 'showUpdateProfileForm'])->name('profile.edit');
Route::post('/section/completed', [LessonController::class, 'checkSectionCompletion'])->name('section.completed');


Route::post('/certificates/store', [LessonController::class, 'store'])->name('certificates.store');

Route::get('/my-certificates', [LessonController::class, 'fetchCompletedCourses'])->name('certificate.fetch');



Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::post('/lessons/completed', [LessonController::class, 'getCompletedLessons'])->name('lessons.completed');
Route::get('/quiz/progress', [QuizQuestionController::class, 'getProgress'])->name('quiz.getProgress');
Route::post('/quiz/previous-answers', [QuizQuestionController::class, 'fetchPreviewsAnswers'])->name('quiz.previousAnswers');
// Define the route to fetch completed articles progress
Route::get('/articles/progress', [QuizQuestionController::class, 'getCompletedArticles'])->name('articles.getProgress');





Route::middleware(['video.security'])->group(function () {
    Route::post('/video/generate-url/{lessonId}', [VideoStreamController::class, 'generateSignedUrl'])
        ->name('video.generateUrl');
    Route::get('/video/stream/{token}', [VideoStreamController::class, 'streamVideo'])
        ->name('video.stream');
});


Route::post('/lessons/save-position', [LessonController::class, 'savePosition'])
    ->name('lessons.savePosition');

Route::get('/lessons/get-position/{lessonId}', [LessonController::class, 'getPosition'])
    ->name('lessons.getPosition');

Route::post('/lessons/mark-complete', [LessonController::class, 'markComplete'])
    ->name('lessons.markComplete');

Route::get('/lessons/next/{lessonId}', [LessonController::class, 'nextLesson'])
    ->name('lessons.next');

Route::get('/lessons/last-watched/{courseId}', [LessonController::class, 'getLastWatched'])
    ->name('lessons.lastWatched');

Route::view('/ai-agriculture-course', 'ai-agriculture-course')->name('ai-agriculture-course');
Route::view('/robotics-agriculture-course', 'robotics-agriculture-course')->name('robotics-agriculture-course');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Live course booking request route
Route::post('/submit-booking', [BookingController::class, 'submitBooking'])->name('booking.submit');

Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/delivery', 'delivery')->name('delivery');
Route::view('/refund', 'policy')->name('refund');

Route::view('/faq', 'faq')->name('faq');


// Admin Blog Management Routes
// Admin authentication routes (NO middleware - anyone can access)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AdminAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('register.post');

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});



Route::post('/subtitles/generate-single', [SubtitleController::class, 'generateSingle'])->name('subtitles.generate.single');
Route::post('/subtitles/generate-bulk', [SubtitleController::class, 'generateBulk'])->name('subtitles.generate.bulk');
Route::post('/subtitles/retry-failed', [SubtitleController::class, 'retryFailed'])->name('subtitles.retry.failed');
Route::get('/subtitles/status/{lesson}', [SubtitleController::class, 'checkStatus'])->name('subtitles.status');
Route::get('/subtitles/stats/{course}', [SubtitleController::class, 'getCourseStats'])->name('subtitles.stats');



// Frontend blog routes
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blogs/tag/{slug}', [BlogController::class, 'byTag'])->name('blogs.tag');


// Main payment route - automatically detects country and redirects
Route::middleware(['auth', 'payment.country'])->group(function () {
    // Universal payment URL - detects IP and routes accordingly
    Route::get('/payment/{course_id}', [PaymentController::class, 'show'])
        ->name('payment.show');
});

// PayPal Payment Processing (for international users)
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])
        ->name('payment.process');

    Route::post('/payment/create-order', [PaymentController::class, 'createPayPalOrder'])
        ->name('payment.createOrder');

    Route::match(['get', 'post'], '/payment-status', [PaymentController::class, 'checkStatus'])
        ->name('payment.status');
        
    Route::get('/api/check-coupon', [PaymentController::class, 'checkCoupon'])
        ->name('payment.checkCoupon');
        
    Route::post('/payment/free-claim', [PaymentController::class, 'processFreeClaim'])
        ->name('payment.freeClaim');
});

// Manual Payment (QR Code) - For Indian users
Route::middleware(['auth'])->group(function () {
    // Show payment page with QR code
    Route::get('/payment/manual/{course_id}', [ManualPaymentController::class, 'show'])
        ->name('payment.manual');

    // Handle "Complete Order" button submission
    Route::post('/payment/complete', [ManualPaymentController::class, 'completeOrder'])
        ->name('payment.complete');
});

// Admin protected routes (ONLY authenticated admins can access)
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');

    // Blog management
    Route::resource('blogs', AdminBlogController::class);
    Route::post('blogs/upload-image', [AdminBlogController::class, 'uploadImage'])
        ->name('blogs.upload-image');

    // User management
    Route::resource('users', AdminUserController::class)->only(['index', 'show', 'edit', 'update']);

    // Payment management (for manual payments only)
    Route::post('/payment/verify/{user_id}', [ManualPaymentController::class, 'verifyPayment'])
        ->name('payment.verify');

    Route::post('/payment/reject/{user_id}', [ManualPaymentController::class, 'rejectPayment'])
        ->name('payment.reject');

    Route::get('/subtitles', function () {
        $courses = \App\Models\Course::all();
        return view('admin.subtitles', compact('courses'));
    })->name('admin.subtitles');

    // Referral System Routes
    Route::controller(AdminReferralController::class)->prefix('referrals')->name('referrals.')->group(function () {
        Route::get('/', 'index')->name('settings');
        Route::post('/update', 'updateSettings')->name('settings.update');
        
        Route::get('/discounts', 'discounts')->name('discounts');
        Route::post('/discounts', 'storeDiscount')->name('discounts.store');
        Route::get('/discounts/{id}/toggle', 'toggleDiscount')->name('discounts.toggle');
        Route::get('/discounts/{id}/delete', 'deleteDiscount')->name('discounts.delete');
        
        Route::get('/logs', 'logs')->name('logs');
        // Temporary Backfill Route
    });

    // Site Settings Routes
    Route::get('/settings', [\App\Http\Controllers\Admin\AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\AdminSettingController::class, 'update'])->name('settings.update');

    // Certificate Management Routes
    Route::controller(AdminCertificateController::class)->prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/issue', 'issueCertificate')->name('issue');
        Route::delete('/{certificate}/revoke', 'revokeCertificate')->name('revoke');
        Route::get('/users/{course}', 'getUsersWithoutCertificate')->name('getUsersWithoutCertificate');
        Route::get('/{certificate}/download', 'downloadCertificate')->name('download');
    });

    // Review Management Routes
    Route::controller(AdminReviewController::class)->prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/courses', 'courses')->name('courses');
        Route::get('/{courseId}', 'index')->name('index');
        Route::get('/{courseId}/create', 'create')->name('create');
        Route::post('/{courseId}/store', 'store')->name('store');
        Route::get('/{courseId}/{id}/edit', 'edit')->name('edit');
        Route::put('/{courseId}/{id}/update', 'update')->name('update');
        Route::delete('/{courseId}/{id}/delete', 'destroy')->name('destroy');
        Route::get('/{courseId}/{id}/toggle', 'toggle')->name('toggle');
    });

    // Enrollment Management Routes
    Route::controller(AdminEnrollmentController::class)->prefix('enrollments')->name('enrollments.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{enrollment}', 'show')->name('show')->where('enrollment', '[0-9]+');
        Route::post('/{enrollment}/status', 'updateStatus')->name('updateStatus');
        Route::post('/{enrollment}/notes', 'addNotes')->name('addNotes');
        Route::delete('/{enrollment}', 'destroy')->name('destroy');
        Route::get('/export', 'export')->name('export');
        Route::get('/status/{status}', 'filterByStatus')->name('filter');
        Route::get('/reminders', 'reminderPage')->name('reminders');
        Route::post('/reminders/send/{user}', 'sendReminderToNonInquirer')->name('reminders.send');
        Route::post('/reminders/bulk', 'sendBulkRemindersToNonInquirers')->name('reminders.bulk');
    });
});

// Fallback route for 404s - redirects to home to preserve SEO juice
Route::fallback(function () {
    return redirect()->route('home', [], 301);
});


