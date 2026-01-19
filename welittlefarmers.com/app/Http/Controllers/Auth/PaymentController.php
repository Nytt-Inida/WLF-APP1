<?php

namespace App\Http\Controllers\Auth;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\DiscountCode;
use App\Models\Referral;
use App\Models\ReferralSetting;
use Illuminate\Support\Str;

class PaymentController extends Controller
{

    public function show($course_id)
    {
        $course = Course::findOrFail($course_id);
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'You need to be logged in to make a payment.');
        }

        // Check if user already has paid/access
        $existingPayment = UserProgress::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->where('payment_status', 1)
            ->first();

        if ($existingPayment) {
            return redirect()->route('course.details', ['id' => $course_id])
                ->with('info', 'You already have access to this course.');
        }

        // Check for referral code in session (passed from signup/login flow)
        $referralCode = session('referral_code');

        // Render PayPal payment view (for international users)
        return view('payment.show', compact('course', 'referralCode'));
    }

    /**
     * Check Coupon Validity (API Endpoint)
     */
    public function checkCoupon(Request $request)
    {
        $code = $request->code;
        $course = Course::find($request->course_id);
        $user_id = auth()->id();
        if (!$user_id) return response()->json(['valid' => false, 'message' => 'Unauthenticated'], 401);
        // $user_id = $request->user_id;

        if (!$code || !$course) {
            return response()->json(['valid' => false, 'message' => 'Invalid request']);
        }

        // Determine currency (PayPal context user is usually international)
        $currency = $request->currency ?? getAppCurrency();
        $basePrice = ($currency === 'USD') ? $course->price_usd : $course->price;

        $result = $this->validateCoupon($code, $user_id, $basePrice, $currency);

        if ($result['valid']) {
            return response()->json([
                'valid' => true,
                'message' => 'Coupon applied! ' . $result['message'],
                'new_price' => $result['new_price'],
                'currency' => $currency
            ]);
        } else {
            return response()->json([
                'valid' => false,
                'message' => $result['message']
            ]);
        }
    }

    /**
     * Process PayPal payment (for international users)
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'orderID' => 'required',
            'course_id' => 'required|integer',
            // 'user_id' => 'required|integer'
        ]);

        $user_id = auth()->id();
        if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);

        DB::beginTransaction();
        try {
            // 1. Get Course and Determine Price (PayPal path is USD)
            $course = Course::findOrFail($request->course_id);
            $finalPrice = $course->price_usd; 
            
            // Coupon Processing (if applicable)
            if ($request->coupon_code) {
                 $validation = $this->validateCoupon($request->coupon_code, $user_id, $course->price_usd, 'USD');
                 if ($validation['valid']) {
                     $finalPrice = $validation['new_price'];
                 }
            }

            // [FIX] Use price directly as USD balance (division by 83.5 removed)
            $expectedUsd = round($finalPrice, 2);

            // 2. SECURITY CHECK: Verify PayPal Order server-side
            // Pass expected USD amount, not INR
            $verification = $this->verifyPayPalOrder($request->orderID, $expectedUsd);
            
            if (!$verification['success']) {
                Log::warning('PayPal Verification Failed', [
                    'order_id' => $request->orderID, 
                    'user_id' => $user_id,
                    'error' => $verification['error'] ?? 'Unknown error'
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Payment verification failed: ' . ($verification['error'] ?? 'Invalid Order')
                ], 400);
            }

            // Check if payment already exists
            $existingPayment = UserProgress::where('user_id', $user_id)
                ->where('course_id', $request->course_id)
                ->where('payment_status', 1)
                ->first();
            if ($existingPayment) {
                return response()->json([
                    'success' => false,
                    'error' => 'Payment already exists for this course'
                ]);
            }
            
            // Record Coupon Usage if it was valid and payment verified
            if ($request->coupon_code && isset($validation) && $validation['valid']) {
                 $this->referralService->recordUsage($validation, $user_id);
            }

            // Get the user
            $user = User::findOrFail($user_id);

            // Update user payment fields
            $user->update([
                'payment_status' => 2, // 2 = Verified (instant for PayPal)
                'payment_verified_at' => now(),
                'pending_course_id' => $request->course_id,
                'verified_by' => null
            ]);

            // Create a new entry in the user_progress table
            $userProgress = UserProgress::create([
                'user_id' => $user_id,
                'course_id' => $request->course_id,
                'payment_status' => 1, // Instant access for PayPal
                'is_completed' => 0
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment completed successfully.',
                'data' => $userProgress
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment processing error', [
                'error' => $e->getMessage(),
                'user_id' => $user_id,
                'course_id' => $request->course_id
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Payment processing failed. Please contact support.'
            ], 500);
        }
    }

    /**
     * Create PayPal Order Server-Side
     * Prevents Client-Side Price Manipulation
     */
    public function createPayPalOrder(Request $request) 
    {
        $request->validate([
            'course_id' => 'required|integer',
            // 'user_id' => 'required|integer'
        ]);
        
        $user_id = auth()->id();
        if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);

        try {
            $course = Course::findOrFail($request->course_id);
            $finalPrice = $course->price_usd;

            // Apply Coupon if exists (PayPal order is USD)
            if ($request->coupon_code) {
                 $validation = $this->validateCoupon($request->coupon_code, $user_id, $course->price_usd, 'USD');
                 if ($validation['valid']) {
                     $finalPrice = $validation['new_price'];
                 }
            }

            // Use price directly as USD balance (division by 83.5 removed)
            $usdPrice = round($finalPrice, 2); 
            $usdPriceStr = number_format($usdPrice, 2, '.', ''); // Ensure '5.00' format

            $clientId = config('services.paypal.client_id');
            $secret = config('services.paypal.secret');
            $mode = config('services.paypal.mode', 'live');
            $baseUrl = ($mode === 'sandbox') ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';

            if (empty($clientId) || empty($secret)) {
                return response()->json(['error' => 'Server Configuration Error'], 500);
            }

            // 1. Get Access Token
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post("$baseUrl/v1/oauth2/token", [
                    'grant_type' => 'client_credentials'
                ]);

            if (!$response->successful()) {
                Log::error("PayPal Token Error: " . $response->body());
                return response()->json(['error' => 'Could not connect to payment provider'], 500);
            }
            $accessToken = $response->json()['access_token'];

            // 2. Create Order
            $orderResponse = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->post("$baseUrl/v2/checkout/orders", [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => $usdPriceStr
                        ],
                        'description' => $course->title
                    ]]
                ]);

            if (!$orderResponse->successful()) {
                 Log::error("PayPal Create Order Error: " . $orderResponse->body());
                 return response()->json(['error' => 'Failed to create order'], 500);
            }

            return response()->json($orderResponse->json());

        } catch (\Exception $e) {
            Log::error("Create Order Exception: " . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Verify PayPal Order Server-Side
     */
    private function verifyPayPalOrder($orderId, $expectedAmount)
    {
        try {
            $clientId = config('services.paypal.client_id');
            $secret = config('services.paypal.secret');
            $mode = config('services.paypal.mode', 'live');
            
            $baseUrl = ($mode === 'sandbox') ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';

            if (empty($clientId) || empty($secret)) {
                Log::critical('PayPal credentials missing in config');
                return ['success' => false, 'error' => 'Server Payment Configuration Error'];
            }

            // 1. Get Access Token
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post("$baseUrl/v1/oauth2/token", [
                    'grant_type' => 'client_credentials'
                ]);

            if (!$response->successful()) {
                Log::error('PayPal Auth Failed', ['body' => $response->body()]);
                return ['success' => false, 'error' => 'Could not connect to payment provider'];
            }

            $accessToken = $response->json()['access_token'];

            // 2. Get Order Details
            $orderResponse = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->get("$baseUrl/v2/checkout/orders/$orderId");

            if (!$orderResponse->successful()) {
                return ['success' => false, 'error' => 'Invalid Order ID'];
            }

            $orderData = $orderResponse->json();

            // 3. Verify Status
            // status can be COMPLETED or APPROVED (if intent=CAPTURE and client already captured)
            // Usually for client-side capture, status is COMPLETED.
            $status = $orderData['status'] ?? 'UNKNOWN';
            if ($status !== 'COMPLETED' && $status !== 'APPROVED') {
                 return ['success' => false, 'error' => "Payment verification failed. Status: $status"];
            }

            // 4. Verify Amount
            // Assuming single purchase unit
            $paidAmount = $orderData['purchase_units'][0]['amount']['value'] ?? 0;
            
            // Allow small float difference or exact match
            // Cast to float for comparison
            if (abs((float)$paidAmount - (float)$expectedAmount) > 0.01) {
                 return ['success' => false, 'error' => "Amount mismatch. Paid: $paidAmount, Expected: $expectedAmount"];
            }

            return ['success' => true];

        } catch (\Exception $e) {
            Log::error('PayPal Verification Exception: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Internal verification error'];
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus(Request $request)
    {
        // Redirect if visited directly via GET (non-AJAX)
        if ($request->isMethod('get') && !$request->expectsJson()) {
            return redirect()->route('home');
        }

        $user_id = auth()->id();
        if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
        // $user_id = $request->user_id;
        $course_id = $request->course_id;

        $userProgress = UserProgress::where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->first();

        if ($userProgress && $userProgress->payment_status == 1) {
            return response()->json(['payment_status' => 1]);
        } else {
            return response()->json(['payment_status' => 0]);
        }
    }

    private $referralService;

    public function __construct(\App\Services\ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }



    public function processFreeClaim(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'code' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $course = Course::findOrFail($request->course_id);

            // Free Claim logic - determine currency/price context (default INR for claim)
            $validation = $this->referralService->validateCoupon($request->code, $user->id, $course->price, 'INR');

            if (!$validation['valid']) {
                return response()->json(['success' => false, 'message' => $validation['message']]);
            }

            // Verify it is actually free
            if ($validation['new_price'] > 0) {
                return response()->json(['success' => false, 'message' => 'This coupon provides a discount, but the course is not free. Please proceed to payment.']);
            }

            // Check existing
            $existing = UserProgress::where('user_id', $user->id)->where('course_id', $course->id)->first();
            if ($existing && $existing->payment_status == 1) {
                return response()->json(['success' => true, 'message' => 'You already have this course!']);
            }

            // Enroll User
            UserProgress::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'payment_status' => 1, // Paid/Active
                'is_completed' => 0
            ]);

            // Update User Main Table
            $user->update([
                'payment_status' => 2,
                'payment_verified_at' => now(),
                'pending_course_id' => $course->id
            ]);

            // Record Usage (this increments usage_count for discount codes)
            try {
                $this->referralService->recordUsage($validation, $user->id);
            } catch (\Exception $e) {
                Log::warning('Failed to record coupon usage', [
                    'error' => $e->getMessage(),
                    'code' => $request->code,
                    'user_id' => $user->id
                ]);
                // Don't fail the entire transaction if usage recording fails
            }

            // Also create CourseEnrollment for consistency if used elsewhere
            // Check if enrollment already exists to avoid duplicates
            $existingEnrollmentQuery = \App\Models\CourseEnrollment::where('user_id', $user->id)
                ->where('course_id', $course->id);
            
            // Check if coupon_code column exists before using it
            if (Schema::hasColumn('course_enrollments', 'coupon_code')) {
                $existingEnrollmentQuery->where('coupon_code', $request->code);
            }
            
            $existingEnrollment = $existingEnrollmentQuery->first();
            
            if (!$existingEnrollment) {
                $enrollmentData = [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'enrollment_date' => now(),
                    'payment_status' => 'verified',
                    'amount' => 0,
                ];
                
                // Only add coupon fields if columns exist
                if (Schema::hasColumn('course_enrollments', 'coupon_code')) {
                    $enrollmentData['coupon_code'] = $request->code;
                }
                if (Schema::hasColumn('course_enrollments', 'discount_amount')) {
                    $enrollmentData['discount_amount'] = $course->price;
                }
                
                \App\Models\CourseEnrollment::create($enrollmentData);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Course claimed successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Free claim error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'course_id' => $request->course_id ?? null,
                'code' => $request->code ?? null,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Something went wrong. Please try again or contact support.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function validateCoupon($code, $userId, $coursePrice, $currency = 'INR')
    {
        return $this->referralService->validateCoupon($code, $userId, $coursePrice, $currency);
    }
}
