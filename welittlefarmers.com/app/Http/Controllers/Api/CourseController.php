<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Rating;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

// Load helper functions for currency conversion
if (file_exists(app_path('Helpers/helpers.php'))) {
    require_once app_path('Helpers/helpers.php');
}

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            // 'user_id' => 'required|exists:users,id',
        ]);

        $user_id = auth()->id();
        if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
        // $user_id = $request->input('user_id');
        $courses = Course::where('user_id', $user_id)->get();
        return response()->json($courses);
    }

    public function show(Request $request)
    {
        $request->validate([
            // 'user_id' => 'required|exists:users,id',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $user_id = auth()->id();
        if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
        // $user_id = $request->input('user_id');
        $course_id = $request->input('course_id');

        if ($course_id) {
            $course = Course::where('id', $course_id)->where('user_id', $user_id)->firstOrFail();
            return response()->json($course);
        } else {
            $courses = Course::where('user_id', $user_id)->get();
            return response()->json($courses);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'age_group' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'number_of_classes' => 'required|integer',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        // Handle the file upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $baseUrl = 'https://insightech.cloud/lfwebsite/storage/app/public/';
            $data['image'] = $baseUrl . $imagePath;
        }

        $course = Course::create($data);

        return response()->json($course, 201);
    }

    public function submitTest(Request $request)
    {
        $request->validate([
            'test_id' => 'required|integer',
            'answer' => 'required|string',
        ]);

        // Validate the test answer here
        // For simplicity, assume answer 2 is correct
        if ($request->input('answer') == 2) {
            // Unlock the next lesson (for demo purposes, actual implementation may vary)
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

public function fetchCoursesByAgeGroup(Request $request)
{
    $request->validate([
        // 'user_id' => 'required|exists:users,id',
        'age_group' => 'required|string',
    ]);

    $user_id = auth()->id();
    if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
    // $user_id = $request->input('user_id');
    $age_group = $request->input('age_group');


    // Fetch courses based on age_group without filtering by user_id
    if (strtolower($age_group) == 'any age' || strtolower($age_group) == 'all') {
        $courses = Course::all();
    } else {
        $courses = Course::where('age_group', $age_group)->get();
    }

    // Get currency based on IP - use helper or detect directly
    $currency = $this->detectCurrency($request);
    
    // Check if each course is marked as favorite by the user, hide description, and add isPurchase flag
    $courses = $courses->map(function ($course) use ($user_id, $currency) {
        // Check if the course is marked as favorite
        $course->isFavourite = DB::table('favorites')
            ->where('user_id', $user_id)
            ->where('course_id', $course->id)
            ->exists() ? 1 : 0;

        // Check if the user has purchased or completed the course
        $progress = DB::table('user_progress')
            ->where('user_id', $user_id)
            ->where('course_id', $course->id)
            ->first();
        
        // Set isPurchase to true if payment_status is 1 (purchased) or is_completed is 1 (completed)
        $course->isPurchase = ($progress && $progress->payment_status == 1) ? true : false;
        
        // Add currency information
        $priceInUSD = $course->price_usd ?? ($currency === 'USD' ? $course->price : null);
        $formattedPrice = function_exists('convertPrice') 
            ? convertPrice($course->price, true, $priceInUSD) 
            : ($currency === 'USD' && $priceInUSD ? '$' . number_format($priceInUSD, 2) : '₹' . number_format($course->price, 0));
        
        $course->price_usd = $priceInUSD;
        $course->price_formatted = $formattedPrice;
        $course->currency = $currency;
        
        return $course->makeHidden('description');
    });


    if ($courses->isEmpty()) {
        return response()->json(['code' => 404, 'message' => 'No courses found', 'courses' => []], 404);
    }

    return response()->json(['code' => 200, 'courses' => $courses], 200);
}

public function fetchPopularCourses(Request $request)
{
    // Validate the input
    $request->validate([
        // 'user_id' => 'required|exists:users,id',
    ]);

    $user_id = auth()->id();
    if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
    // $user_id = $request->input('user_id');

    // Fetch the top 10 most purchased courses
    $courses = Course::whereHas('userProgresses', function($query) {
            $query->where('payment_status', 1); // 1 indicates payment is complete
        })
        ->withCount(['userProgresses' => function ($query) {
            $query->where('payment_status', 1); // 1 indicates payment is complete
        }])
        ->orderBy('user_progresses_count', 'desc')
        ->take(10)
        ->get();

    // Get currency based on IP - use helper or detect directly
    $currency = $this->detectCurrency($request);
    
    // Check if each course is marked as favorite by the user and add isPurchase flag
    $courses = $courses->map(function ($course) use ($user_id, $currency) {
        // Check if the course is marked as favorite
        $course->isFavourite = DB::table('favorites')
            ->where('user_id', $user_id)
            ->where('course_id', $course->id)
            ->exists() ? 1 : 0;

        // Check if the user has purchased the course
        $progress = DB::table('user_progress')
            ->where('user_id', $user_id)
            ->where('course_id', $course->id)
            ->first();
        
        // Set isPurchase to true if payment_status is 1 (purchased)
        $course->isPurchase = ($progress && $progress->payment_status == 1) ? true : false;

        // Add currency information
        $priceInUSD = $course->price_usd ?? ($currency === 'USD' ? $course->price : null);
        $formattedPrice = function_exists('convertPrice') 
            ? convertPrice($course->price, true, $priceInUSD) 
            : ($currency === 'USD' && $priceInUSD ? '$' . number_format($priceInUSD, 2) : '₹' . number_format($course->price, 0));
        
        $course->price_usd = $priceInUSD;
        $course->price_formatted = $formattedPrice;
        $course->currency = $currency;

        // Hide unnecessary fields
        return $course->makeHidden(['description', 'user_progresses_count']);
    });

    if ($courses->isEmpty()) {
        return response()->json(['code' => 404, 'message' => 'No popular courses found', 'courses' => []], 404);
    }

    return response()->json(['code' => 200, 'courses' => $courses], 200);
}



public function updateFavoriteStatus(Request $request)
{
    $request->validate([
        // 'user_id' => 'required|exists:users,id',
        'course_id' => 'required|exists:courses,id',
        'isFavourite' => 'required|boolean',
    ]);

    $userId = auth()->id();
    if (!$userId) return response()->json(['error' => 'Unauthenticated'], 401);

    $user = User::findOrFail($userId);
    // $user = User::findOrFail($request->input('user_id'));
    $course = Course::findOrFail($request->input('course_id'));

    if ($request->input('isFavourite')) {
        // Check if the course is already in favorites
        if (!$user->favorites()->where('course_id', $course->id)->exists()) {
            $user->favorites()->attach($course->id);
            $message = 'Course added to favorites';
        } else {
            $message = 'Course is already in favorites';
        }
    } else {
        // Check if the course is actually in favorites before detaching
        if ($user->favorites()->where('course_id', $course->id)->exists()) {
            $user->favorites()->detach($course->id);
            $message = 'Course removed from favorites';
        } else {
            $message = 'Course was not in favorites';
        }
    }

    return response()->json(['code' => 200, 'message' => $message], 200);
}


    
public function fetchFavoriteCourses(Request $request)
{
    $request->validate([
        // 'user_id' => 'required|exists:users,id',
    ]);

    $user_id = auth()->id();
    if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
    // $user_id = $request->input('user_id');

    // Fetch the favorite courses for the user
    $user = User::findOrFail($user_id);
    $courses = $user->favorites()->get()->map(function ($course) use ($user_id) {

        // Check if the course is a favorite for the user
        $is_favorite = DB::table('favorites')
            ->where('user_id', $user_id)
            ->where('course_id', $course->id)
            ->exists();

        // Check if the user has purchased the course
        $progress = DB::table('user_progress')
            ->where('user_id', $user_id)
            ->where('course_id', $course->id)
            ->first();

        // Set isPurchase to true if payment_status is 1 (purchased)
        $isPurchase = ($progress && $progress->payment_status == 1) ? true : false;

        // Get currency based on IP - use helper or detect directly
        $currency = $this->detectCurrency($request);
        $priceInUSD = $course->price_usd ?? ($currency === 'USD' ? $course->price : null);
        $formattedPrice = function_exists('convertPrice') 
            ? convertPrice($course->price, true, $priceInUSD) 
            : ($currency === 'USD' && $priceInUSD ? '$' . number_format($priceInUSD, 2) : '₹' . number_format($course->price, 0));
        
        return [
            'id' => $course->id,
            'user_id' => $course->user_id,
            'title' => $course->title,
            'age_group' => $course->age_group,
            'price' => $course->price, // Keep original INR price
            'price_usd' => $priceInUSD, // USD price
            'price_formatted' => $formattedPrice, // Formatted price with currency symbol
            'currency' => $currency, // Currency code (INR or USD)
            'course_id' => $course->id,
            'number_of_classes' => $course->number_of_classes,
            'image' => $course->image,
            'isFavourite' => $is_favorite,  // Use the result from the check
            'isPurchase' => $isPurchase,    // Add the isPurchase flag
        ];
    });

    if ($courses->isEmpty()) {
        return response()->json(['code' => 404, 'message' => 'No favorite courses found', 'courses' => []], 404);
    }

    return response()->json(['code' => 200, 'courses' => $courses], 200);
}


public function fetchPurchasedCourses(Request $request)
{
    $request->validate([
        // 'user_id' => 'required|exists:users,id',
    ]);

    $user_id = auth()->id();
    if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
    // $user_id = $request->input('user_id');

    // Fetch the purchased courses for the user
    $purchasedCourses = UserProgress::where('user_id', $user_id)
        ->where('payment_status', 1)
        ->with('course')
        ->get()
        ->filter(function ($progress) {
            return $progress->course !== null; // Filter out any null courses
        })
        ->map(function ($progress) use ($user_id, $request) {
            $course = $progress->course;

            // Check if the course is marked as favorite by the user
            $isFavourite = DB::table('favorites')
                ->where('user_id', $user_id)
                ->where('course_id', $course->id)
                ->exists();

            // Get currency based on IP - use helper or detect directly
            $currency = $this->detectCurrency($request);
            $priceInUSD = $course->price_usd ?? ($currency === 'USD' ? $course->price : null);
            $formattedPrice = function_exists('convertPrice') 
                ? convertPrice($course->price, true, $priceInUSD) 
                : ($currency === 'USD' && $priceInUSD ? '$' . number_format($priceInUSD, 2) : '₹' . number_format($course->price, 0));
            
            return [
                'id' => $course->id,
                'user_id' => $course->user_id,
                'title' => $course->title,
                'age_group' => $course->age_group,
                'price' => $course->price, // Keep original INR price
                'price_usd' => $priceInUSD, // USD price
                'price_formatted' => $formattedPrice, // Formatted price with currency symbol
                'currency' => $currency, // Currency code (INR or USD)
                'course_id' => $course->id,
                'number_of_classes' => $course->number_of_classes,
                'image' => $course->image,
                'isFavourite' => $isFavourite ? 1 : 0, // Show 1 if favorite, otherwise 0
            ];
        });

    if ($purchasedCourses->isEmpty()) {
        return response()->json(['code' => 404, 'message' => 'No purchased courses found', 'courses' => []], 404);
    }

    return response()->json(['code' => 200, 'courses' => $purchasedCourses], 200);
}

    /**
     * Detect currency based on IP address
     */
    private function detectCurrency(Request $request)
    {
        // For mobile apps, default to INR unless explicitly detected as non-Indian
        // This ensures Indian users see ₹ symbol by default
        $defaultCurrency = 'INR';
        
        if (function_exists('getAppCurrency')) {
            $currency = getAppCurrency();
            Log::info('Currency detected via helper', ['currency' => $currency, 'ip' => $request->ip()]);
            return $currency;
        }
        
        // Direct IP detection if helper not available
        $ip = $request->ip();
        
        // Check for X-Forwarded-For header (common in mobile apps behind proxies)
        $forwardedFor = $request->header('X-Forwarded-For');
        if ($forwardedFor) {
            $ips = explode(',', $forwardedFor);
            $ip = trim($ips[0]);
        }
        
        // Check for X-Real-IP header
        $realIp = $request->header('X-Real-IP');
        if ($realIp) {
            $ip = $realIp;
        }
        
        // For localhost or private IPs, default to INR
        if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168') || str_starts_with($ip, '10.') || str_starts_with($ip, '172.')) {
            Log::info('Currency: Defaulting to INR for local/private IP', ['ip' => $ip]);
            return $defaultCurrency;
        }
        
        $cacheKey = "currency_for_ip_{$ip}";
        return Cache::remember($cacheKey, 86400, function () use ($ip, $defaultCurrency) {
            try {
                $url = "http://ipwhois.app/json/{$ip}?objects=country_code";
                $response = @file_get_contents($url, false, stream_context_create(['http' => ['timeout' => 2]]));
                if ($response !== false) {
                    $data = json_decode($response, true);
                    $country = $data['countryCode'] ?? $data['country_code'] ?? 'IN';
                    $currency = ($country === 'IN') ? 'INR' : 'USD';
                    Log::info('Currency detected from IP', ['ip' => $ip, 'country' => $country, 'currency' => $currency]);
                    return $currency;
                }
            } catch (\Exception $e) {
                Log::warning('Currency detection failed', ['ip' => $ip, 'error' => $e->getMessage()]);
            }
            // Default to INR for Indian users (safer default)
            Log::info('Currency: Defaulting to INR (fallback)', ['ip' => $ip]);
            return $defaultCurrency;
        });
    }

}