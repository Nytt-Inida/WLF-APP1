<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth;
use App\Models\BillingDetails; // Ensure this model exists
use Tco\TwocheckoutFacade;
use App\Services\ReferralService;
use Illuminate\Support\Facades\DB; 

class ApiPaymentController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }
    public function show(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course_id = $request->input('course_id');
        $course = Course::findOrFail($course_id);
        return response()->json($course);
    }

public function process(Request $request)
{
    // Validate the input using Validator::make
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'course_id' => 'required|exists:courses,id',
        'card_number' => 'required|string',
        'holder_name' => 'required|string|max:255', 
        'expiry_date' => 'required|string',
        'cvc' => 'required|string',
        'coupon_code' => 'nullable|string',
    ]);

    // Handle validation failure
    if ($validator->fails()) {
        return response()->json([
            'code' => 403,
            'status' => false,
            'message' => $validator->errors()
        ], 403);
    }

    // Get the authenticated user
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    // Ensure the user_id matches the authenticated user's ID
    if ($user->id !== (int) $request->input('user_id')) {
        return response()->json(['message' => 'Unauthorized user ID'], 403);
    }

    // Ensure the course exists
    $course = Course::find($request->input('course_id'));

    if (!$course) {
        return response()->json(['message' => 'Invalid course ID'], 403);
    }

    // Check if user has pending referral from signup
    $pendingReferral = \App\Models\Referral::where('referred_user_id', $user->id)
        ->where('status', 'pending')
        ->first();
    
    // Process coupon/referral code
    $finalPrice = $course->price;
    $couponValidation = null;
    $currency = 'INR'; // Default for card payment, adjust if needed
    $appliedReferralCode = null;
    
    // Auto-apply referral code from signup if available and no coupon code provided
    if ($pendingReferral && !$request->filled('coupon_code')) {
        $referrer = \App\Models\User::find($pendingReferral->referrer_id);
        if ($referrer && $referrer->referral_code) {
            $couponValidation = $this->referralService->validateCoupon(
                $referrer->referral_code,
                $user->id,
                $course->price,
                $currency
            );
            
            if ($couponValidation['valid']) {
                $finalPrice = $couponValidation['new_price'];
                $appliedReferralCode = $referrer->referral_code;
            }
        }
    }
    
    // Process manual coupon code if provided (regular discount codes only)
    if ($request->filled('coupon_code')) {
        $manualCouponValidation = $this->referralService->validateCoupon(
            $request->coupon_code,
            $user->id,
            $finalPrice, // Use already discounted price if referral was applied
            $currency
        );
        
        // Only apply if it's a regular discount code (not a referral code)
        if ($manualCouponValidation['valid'] && $manualCouponValidation['type'] == 'discount') {
            $couponValidation = $manualCouponValidation;
            $finalPrice = $manualCouponValidation['new_price'];
        }
    }

    // Billing details
    $billingDetails = [
        'FirstName' => $request->input('holder_name'),
        'LastName' => 'Customer',
        'Email' => $user->email,
        'CountryCode' => 'IN', // Country as ISO code
        'City' => 'New Delhi',
        'Address1' => '123 Test Street',
        'Zip' => '110001',
        'State' => 'DL'
    ];

    // 2Checkout configuration
    $config = [
        'sellerId' => env('TWOCHECKOUT_SELLER_ID'),
        'secretKey' => env('TWOCHECKOUT_SECRET_KEY'),
        'buyLinkSecretWord' => env('TWOCHECKOUT_SECRET_WORD'),
        'jwtExpireTime' => 30,
        'curlVerifySsl' => 1,
    ];

    $tco = new TwocheckoutFacade($config);

    // Remove ProductCode and proceed without it
    $params = [
        'Currency' => 'USD',
        'Language' => 'en',
        'BillingDetails' => $billingDetails,
        'PaymentDetails' => [
            'Type' => 'TEST', // Change to 'EES_TOKEN_PAYMENT' in production
            'Currency' => 'USD',
            'CustomerIP' => $request->ip(),
            'PaymentMethod' => [
                'CardNumber' => $request->input('card_number'),
                'CardType' => 'VISA',
                'ExpirationMonth' => substr($request->input('expiry_date'), 0, 2),
                'ExpirationYear' => substr($request->input('expiry_date'), -2),
                'CVC' => $request->input('cvc'),
                'HolderName' => $request->input('holder_name'),
            ],
        ],
        'Items' => [
            [
                'Name' => $course->title,
                'Quantity' => 1,
                'Price' => [
                    'Amount' => $finalPrice, // Use discounted price if coupon applied
                    'Type' => 'CUSTOM',  // Make sure you mark this as a custom product
                ],
                'Tangible' => false  // This is a digital product (course)
            ]
        ]
    ];

    try {
        $response = $tco->order()->place($params);

        if (!isset($response['RefNo'])) {
            return response()->json(['message' => 'Payment failed, no reference number returned.'], 500);
        }

        DB::beginTransaction();
        try {
            // Record coupon/referral usage if valid
            if ($couponValidation && $couponValidation['valid']) {
                $this->referralService->recordUsage($couponValidation, $user->id);
                
                // If referral was used, mark pending referral as completed
                if ($pendingReferral && $couponValidation['type'] == 'referral') {
                    $pendingReferral->update(['status' => 'completed']);
                }
            }

            // Save successful payment in the database
            UserProgress::create([
                'user_id' => $user->id,
                'course_id' => $request->input('course_id'),
                'payment_status' => 1,
                'is_completed' => 0,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Payment successful',
                'course_id' => $request->input('course_id'),
                'discount_applied' => $couponValidation && $couponValidation['valid'] ? $couponValidation['discount_amount'] : 0
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment processing error: ', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Payment processing failed'], 500);
        }

    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('2Checkout Payment Error: ', ['error' => $e->getMessage()]);

        return response()->json(['message' => 'Payment failed: ' . $e->getMessage()], 500);
    }
}

 public function checkCoupon(Request $request)
 {
     $request->validate([
         'code' => 'required|string',
         'course_id' => 'required|exists:courses,id',
     ]);

     $user = Auth::user();
     if (!$user) {
         return response()->json(['valid' => false, 'message' => 'Unauthenticated'], 401);
     }

     $code = $request->input('code');
     $course = Course::findOrFail($request->input('course_id'));
     $currency = $request->input('currency', 'INR');
     $basePrice = ($currency === 'USD') ? ($course->price_usd ?? $course->price) : $course->price;

     $validation = $this->referralService->validateCoupon($code, $user->id, $basePrice, $currency);

     if ($validation['valid']) {
         return response()->json([
             'valid' => true,
             'message' => $validation['message'],
             'new_price' => $validation['new_price'],
             'discount_amount' => $validation['discount_amount'],
             'type' => $validation['type'] ?? 'discount',
             'currency' => $currency
         ]);
     } else {
         return response()->json([
             'valid' => false,
             'message' => $validation['message']
         ]);
     }
 }

 public function getDiscountedPrice(Request $request)
 {
     $request->validate([
         'course_id' => 'required|exists:courses,id',
     ]);

     $user = Auth::user();
     if (!$user) {
         return response()->json(['error' => 'Unauthenticated'], 401);
     }

     $course = Course::findOrFail($request->input('course_id'));
     $currency = $request->input('currency', 'INR');
     $originalPrice = ($currency === 'USD') ? ($course->price_usd ?? $course->price) : $course->price;
     $finalPrice = $originalPrice;
     $discountAmount = 0;
     $appliedReferralCode = null;
     $hasDiscount = false;

     // Check if user has pending referral from signup
     $pendingReferral = \App\Models\Referral::where('referred_user_id', $user->id)
         ->where('status', 'pending')
         ->first();

     if ($pendingReferral) {
         $referrer = \App\Models\User::find($pendingReferral->referrer_id);
         if ($referrer && $referrer->referral_code) {
             $validation = $this->referralService->validateCoupon(
                 $referrer->referral_code,
                 $user->id,
                 $originalPrice,
                 $currency
             );
             
             if ($validation['valid']) {
                 $finalPrice = $validation['new_price'];
                 $discountAmount = $validation['discount_amount'];
                 $appliedReferralCode = $referrer->referral_code;
                 $hasDiscount = true;
             }
         }
     }

     return response()->json([
         'original_price' => $originalPrice,
         'final_price' => $finalPrice,
         'discount_amount' => $discountAmount,
         'has_discount' => $hasDiscount,
         'applied_referral_code' => $appliedReferralCode,
         'currency' => $currency,
         'message' => $hasDiscount ? 'Referral discount applied' : 'No discount applied'
     ]);
 }

 public function purchaseWithoutPayment(Request $request)
{
    // Validate that course_id is provided and valid
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'course_id' => 'required|exists:courses,id',
    ]);

    // Handle validation failure
    if ($validator->fails()) {
        return response()->json([
            'code' => 403,
            'status' => false,
            'message' => $validator->errors()
        ], 403);
    }

    // Get the authenticated user
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    // Ensure the user_id matches the authenticated user's ID
    if ($user->id !== (int) $request->input('user_id')) {
        return response()->json(['message' => 'Unauthorized user ID'], 403);
    }

    // Ensure the course exists
    $course = Course::find($request->input('course_id'));

    if (!$course) {
        return response()->json(['message' => 'Invalid course ID'], 403);
    }

    // Check if the user has already purchased the course
    $existingProgress = UserProgress::where('user_id', $user->id)
        ->where('course_id', $course->id)
        ->first();

    if ($existingProgress) {
        // If the course is already purchased, return a message
        return response()->json([
            'message' => 'You have already purchased this course.',
            'course_id' => $course->id
        ], 200);
    }

    // Simulate course purchase by adding an entry to UserProgress
    UserProgress::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'payment_status' => 1, // Mark as "paid" without actual payment
        'is_completed' => 0,   // Not completed yet
    ]);

    // Return a successful response
    return response()->json([
        'message' => 'Course successfully added to purchased courses',
        'course_id' => $course->id
    ], 200);
}



}