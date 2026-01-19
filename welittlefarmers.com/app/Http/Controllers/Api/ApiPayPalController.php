<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\CourseEnrollment;
use App\Services\ReferralService;

class ApiPayPalController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    /**
     * Create PayPal Order for International Payments (USD)
     * This is called from the mobile app before opening PayPal checkout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'coupon_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        try {
            $course = Course::findOrFail($request->course_id);

            // Get USD price (default to 29 if not set)
            $basePrice = $course->price_usd ?? 29;
            $finalPrice = $basePrice;
            $discountAmount = 0;
            $appliedCoupon = null;

            // Check for pending referral from signup (auto-apply)
            $pendingReferral = \App\Models\Referral::where('referred_user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if ($pendingReferral && !$request->filled('coupon_code')) {
                $referrer = User::find($pendingReferral->referrer_id);
                if ($referrer && $referrer->referral_code) {
                    $validation = $this->referralService->validateCoupon(
                        $referrer->referral_code,
                        $user->id,
                        $basePrice,
                        'USD'
                    );

                    if ($validation['valid']) {
                        $finalPrice = $validation['new_price'];
                        $discountAmount = $validation['discount_amount'];
                        $appliedCoupon = $referrer->referral_code;
                    }
                }
            }

            // Process manual coupon code if provided
            if ($request->filled('coupon_code')) {
                $manualValidation = $this->referralService->validateCoupon(
                    $request->coupon_code,
                    $user->id,
                    $finalPrice,
                    'USD'
                );

                if ($manualValidation['valid'] && $manualValidation['type'] == 'discount') {
                    $finalPrice = $manualValidation['new_price'];
                    $discountAmount = $manualValidation['discount_amount'];
                    $appliedCoupon = $request->coupon_code;
                }
            }

            // Format price for PayPal (2 decimal places)
            $usdPrice = round($finalPrice, 2);
            $usdPriceStr = number_format($usdPrice, 2, '.', '');

            // Get PayPal credentials from config
            $clientId = config('services.paypal.client_id');
            $secret = config('services.paypal.secret');
            $mode = config('services.paypal.mode', 'live');
            $baseUrl = ($mode === 'sandbox') ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';

            if (empty($clientId) || empty($secret)) {
                Log::error('PayPal credentials missing in config');
                return response()->json([
                    'success' => false,
                    'message' => 'Payment configuration error. Please contact support.'
                ], 500);
            }

            // Step 1: Get PayPal Access Token
            $tokenResponse = Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post("$baseUrl/v1/oauth2/token", [
                    'grant_type' => 'client_credentials'
                ]);

            if (!$tokenResponse->successful()) {
                Log::error('PayPal Token Error', ['body' => $tokenResponse->body()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Could not connect to payment provider'
                ], 500);
            }

            $accessToken = $tokenResponse->json()['access_token'];

            // Step 2: Create PayPal Order
            $orderResponse = Http::withToken($accessToken)
                ->post("$baseUrl/v2/checkout/orders", [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => $usdPriceStr
                        ],
                        'description' => $course->title . ' - Course Purchase',
                        'custom_id' => json_encode([
                            'course_id' => $course->id,
                            'user_id' => $user->id,
                        ])
                    ]],
                    'application_context' => [
                        'brand_name' => 'We Little Farmers',
                        'shipping_preference' => 'NO_SHIPPING',
                        'user_action' => 'PAY_NOW',
                        'return_url' => 'https://welittlefarmers.com/paypal/return',
                        'cancel_url' => 'https://welittlefarmers.com/paypal/cancel',
                    ]
                ]);

            if (!$orderResponse->successful()) {
                Log::error('PayPal Create Order Error', ['body' => $orderResponse->body()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create payment order'
                ], 500);
            }

            $orderData = $orderResponse->json();

            // Extract approval link for WebView
            $approveLink = null;
            foreach ($orderData['links'] ?? [] as $link) {
                if ($link['rel'] === 'approve') {
                    $approveLink = $link['href'];
                    break;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order_id' => $orderData['id'],
                'approve_link' => $approveLink,
                'amount' => $usdPrice,
                'currency' => 'USD',
                'course_id' => $course->id,
                'course_title' => $course->title,
                'discount_amount' => $discountAmount,
                'applied_coupon' => $appliedCoupon,
            ]);

        } catch (\Exception $e) {
            Log::error('PayPal Order Creation Error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
                'course_id' => $request->course_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify PayPal Payment and Grant Course Access
     * Called after user completes payment in PayPal WebView
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'order_id' => 'required|string',
            'coupon_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        try {
            $course = Course::findOrFail($request->course_id);

            // Calculate expected amount
            $basePrice = $course->price_usd ?? 29;
            $finalPrice = $basePrice;
            $appliedCoupon = $request->coupon_code;

            // Check for pending referral
            $pendingReferral = \App\Models\Referral::where('referred_user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if ($pendingReferral && !$appliedCoupon) {
                $referrer = User::find($pendingReferral->referrer_id);
                if ($referrer && $referrer->referral_code) {
                    $validation = $this->referralService->validateCoupon(
                        $referrer->referral_code,
                        $user->id,
                        $basePrice,
                        'USD'
                    );

                    if ($validation['valid']) {
                        $finalPrice = $validation['new_price'];
                        $appliedCoupon = $referrer->referral_code;
                    }
                }
            }

            // Process coupon code if provided
            if ($request->filled('coupon_code')) {
                $manualValidation = $this->referralService->validateCoupon(
                    $request->coupon_code,
                    $user->id,
                    $finalPrice,
                    'USD'
                );

                if ($manualValidation['valid'] && $manualValidation['type'] == 'discount') {
                    $finalPrice = $manualValidation['new_price'];
                }
            }

            $expectedAmount = round($finalPrice, 2);

            // Verify PayPal Order
            $verification = $this->verifyPayPalOrder($request->order_id, $expectedAmount);

            if (!$verification['success']) {
                Log::warning('PayPal Verification Failed', [
                    'order_id' => $request->order_id,
                    'user_id' => $user->id,
                    'error' => $verification['error'] ?? 'Unknown error'
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Payment verification failed: ' . ($verification['error'] ?? 'Invalid Order')
                ], 400);
            }

            DB::beginTransaction();

            try {
                // Check if user already has this course
                $existingProgress = UserProgress::where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->first();

                if ($existingProgress && $existingProgress->payment_status == 1) {
                    DB::rollBack();
                    return response()->json([
                        'success' => true,
                        'message' => 'You already have access to this course.',
                        'course_id' => $course->id
                    ]);
                }

                // Record coupon usage if applicable
                if ($appliedCoupon) {
                    $couponValidation = $this->referralService->validateCoupon(
                        $appliedCoupon,
                        $user->id,
                        $basePrice,
                        'USD'
                    );
                    if ($couponValidation['valid']) {
                        $this->referralService->recordUsage($couponValidation, $user->id);
                    }

                    // Update referral status if applicable
                    if ($pendingReferral) {
                        $pendingReferral->update(['status' => 'completed']);
                    }
                }

                // Create or update UserProgress (grant course access)
                if ($existingProgress) {
                    $existingProgress->update([
                        'payment_status' => 1,
                        'is_completed' => 0
                    ]);
                } else {
                    UserProgress::create([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'payment_status' => 1,
                        'is_completed' => 0
                    ]);
                }

                // Create enrollment record
                $existingEnrollment = CourseEnrollment::where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->first();

                if ($existingEnrollment) {
                    $existingEnrollment->update([
                        'payment_status' => 'verified',
                        'amount' => $finalPrice,
                        'coupon_code' => $appliedCoupon,
                        'discount_amount' => $basePrice - $finalPrice,
                        'notes' => "Method: paypal, Order ID: {$request->order_id}"
                    ]);
                } else {
                    CourseEnrollment::create([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'payment_status' => 'verified',
                        'amount' => $finalPrice,
                        'enrollment_date' => now(),
                        'coupon_code' => $appliedCoupon,
                        'discount_amount' => $basePrice - $finalPrice,
                        'notes' => "Method: paypal, Order ID: {$request->order_id}"
                    ]);
                }

                // Update user payment status
                $user->update([
                    'payment_status' => 2, // Verified
                    'pending_course_id' => $course->id,
                    'payment_verified_at' => now()
                ]);

                DB::commit();

                Log::info('PayPal Payment Verified Successfully', [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'order_id' => $request->order_id,
                    'amount' => $finalPrice
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified successfully! You now have access to the course.',
                    'course_id' => $course->id
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('PayPal Payment Verification Error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
                'course_id' => $request->course_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify PayPal Order Server-Side
     *
     * @param string $orderId
     * @param float $expectedAmount
     * @return array
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

            // Get Access Token
            $response = Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post("$baseUrl/v1/oauth2/token", [
                    'grant_type' => 'client_credentials'
                ]);

            if (!$response->successful()) {
                Log::error('PayPal Auth Failed', ['body' => $response->body()]);
                return ['success' => false, 'error' => 'Could not connect to payment provider'];
            }

            $accessToken = $response->json()['access_token'];

            // Get Order Details
            $orderResponse = Http::withToken($accessToken)
                ->get("$baseUrl/v2/checkout/orders/$orderId");

            if (!$orderResponse->successful()) {
                return ['success' => false, 'error' => 'Invalid Order ID'];
            }

            $orderData = $orderResponse->json();

            // Verify Status (COMPLETED or APPROVED)
            $status = $orderData['status'] ?? 'UNKNOWN';
            if ($status !== 'COMPLETED' && $status !== 'APPROVED') {
                return ['success' => false, 'error' => "Payment not completed. Status: $status"];
            }

            // Verify Amount
            $paidAmount = $orderData['purchase_units'][0]['amount']['value'] ?? 0;

            // Allow small float difference
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
     * Capture PayPal Order (if using server-side capture)
     * Called after user approves the order
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function captureOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        try {
            $clientId = config('services.paypal.client_id');
            $secret = config('services.paypal.secret');
            $mode = config('services.paypal.mode', 'live');
            $baseUrl = ($mode === 'sandbox') ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';

            // Get Access Token
            $tokenResponse = Http::withBasicAuth($clientId, $secret)
                ->asForm()
                ->post("$baseUrl/v1/oauth2/token", [
                    'grant_type' => 'client_credentials'
                ]);

            if (!$tokenResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not connect to payment provider'
                ], 500);
            }

            $accessToken = $tokenResponse->json()['access_token'];

            // Capture the order
            $captureResponse = Http::withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("$baseUrl/v2/checkout/orders/{$request->order_id}/capture");

            if (!$captureResponse->successful()) {
                Log::error('PayPal Capture Error', ['body' => $captureResponse->body()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to capture payment'
                ], 500);
            }

            $captureData = $captureResponse->json();

            return response()->json([
                'success' => true,
                'message' => 'Payment captured successfully',
                'order_id' => $captureData['id'],
                'status' => $captureData['status']
            ]);

        } catch (\Exception $e) {
            Log::error('PayPal Capture Error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to capture payment'
            ], 500);
        }
    }
}
