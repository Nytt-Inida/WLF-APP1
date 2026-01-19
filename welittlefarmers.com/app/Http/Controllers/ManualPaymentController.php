<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\CourseEnrollment;
use App\Mail\Mailer;

class ManualPaymentController extends Controller
{
    protected $referralService;

    public function __construct(\App\Services\ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }
    /**
     * Show the manual payment page with QR code
     */
    public function show($course_id)
    {
        $course = Course::findOrFail($course_id);
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please login to purchase this course.');
        }

        // Check if user already has verified payment
        if ($user->payment_status == 2 && $user->pending_course_id == $course_id) {
            // User already verified, check if enrolled
            $enrolled = UserProgress::where('user_id', $user->id)
                ->where('course_id', $course_id)
                ->where('payment_status', 1)
                ->first();

            if ($enrolled) {
                return redirect()->route('course.details', ['id' => $course_id])
                    ->with('info', 'You already have access to this course.');
            }
        }

        // Check for referral code in session
        $referralCode = session('referral_code');

        return view('payment.manual', compact('course', 'referralCode'));
    }

    /**
     * Handle "Complete Order" button click
     * Updates user's payment status to pending (1)
     */
    public function completeOrder(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'user_id' => 'required|integer|exists:users,id',
            'coupon_code' => 'nullable|string'
        ]);

        $user = Auth::user();

        // Verify the authenticated user matches the request
        if ($user->id != $request->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized user'
            ], 403);
        }

        DB::beginTransaction();

        try {
            // Check if user already has verified payment for this course
            if ($user->payment_status == 2 && $user->pending_course_id == $request->course_id) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Your payment is already verified. Redirecting to course...'
                ]);
            }

            // Check if user already has pending payment
            if ($user->payment_status == 1 && $user->pending_course_id == $request->course_id) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Your payment is already pending verification. Please wait for admin approval.'
                ]);
            }

            // Update user payment status to pending
            $user->update([
                'pending_course_id' => $request->course_id,
                'payment_status' => 1, // 1 = Pending verification
                'payment_submitted_at' => now()
            ]);

            DB::commit();

            // Create enrollement recored
            $course = Course::find($request->course_id);

            // Check if user has pending referral from signup
            $pendingReferral = \App\Models\Referral::where('referred_user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            $alreadyenrolled = CourseEnrollment::where('course_id', $request->course_id)
                ->where('user_id', $request->user_id)
                ->first();

            if ($alreadyenrolled) {
                // Calculate Discount
                $finalAmount = $course->price;
                $couponCode = $request->coupon_code;
                $discountAmount = 0;
                $appliedReferralCode = null;

                // Auto-apply referral code from signup if available and no coupon code provided
                if ($pendingReferral && !$couponCode) {
                    $referrer = User::find($pendingReferral->referrer_id);
                    if ($referrer && $referrer->referral_code) {
                        $validation = $this->referralService->validateCoupon($referrer->referral_code, $user->id, $course->price, 'INR');
                        if ($validation['valid']) {
                            $finalAmount = $validation['new_price'];
                            $discountAmount = $validation['discount_amount'];
                            $appliedReferralCode = $referrer->referral_code;
                        }
                    }
                }

                // Process manual coupon code if provided (regular discount codes only)
                if ($couponCode) {
                    $manualValidation = $this->referralService->validateCoupon($couponCode, $user->id, $finalAmount, 'INR');
                    // Only apply if it's a regular discount code (not a referral code)
                    if ($manualValidation['valid'] && $manualValidation['type'] == 'discount') {
                        $finalAmount = $manualValidation['new_price'];
                        $discountAmount = $manualValidation['discount_amount'];
                    } else {
                        $couponCode = null;
                    }
                }

                $alreadyenrolled->update([
                    'payment_status' => 'pending',
                    'amount' => $finalAmount,
                    'coupon_code' => $couponCode ?? $appliedReferralCode,
                    'discount_amount' => $discountAmount,
                    'enrollment_date' => now(),
                    'notes' => "Method: manual (re-attempt)"
                ]);
                
                // Record referral usage if applied
                if ($appliedReferralCode && $pendingReferral) {
                    $referrer = User::find($pendingReferral->referrer_id);
                    if ($referrer && $referrer->referral_code) {
                        $validation = $this->referralService->validateCoupon($referrer->referral_code, $user->id, $course->price, 'INR');
                        if ($validation['valid']) {
                            $this->referralService->recordUsage($validation, $user->id);
                            $pendingReferral->update(['status' => 'completed']);
                        }
                    }
                }
            } else {
                
                // Calculate Discount
                $finalAmount = $course->price;
                $couponCode = $request->coupon_code;
                $discountAmount = 0;
                $appliedReferralCode = null;

                // Auto-apply referral code from signup if available and no coupon code provided
                if ($pendingReferral && !$couponCode) {
                    $referrer = User::find($pendingReferral->referrer_id);
                    if ($referrer && $referrer->referral_code) {
                        $validation = $this->referralService->validateCoupon($referrer->referral_code, $user->id, $course->price, 'INR');
                        if ($validation['valid']) {
                            $finalAmount = $validation['new_price'];
                            $discountAmount = $validation['discount_amount'];
                            $appliedReferralCode = $referrer->referral_code;
                        }
                    }
                }

                // Process manual coupon code if provided (regular discount codes only)
                if ($couponCode) {
                    $manualValidation = $this->referralService->validateCoupon($couponCode, $user->id, $finalAmount, 'INR');
                    // Only apply if it's a regular discount code (not a referral code)
                    if ($manualValidation['valid'] && $manualValidation['type'] == 'discount') {
                        $finalAmount = $manualValidation['new_price'];
                        $discountAmount = $manualValidation['discount_amount'];
                    } else {
                        $couponCode = null;
                    }
                }

                $enrolled = CourseEnrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $request->course_id,
                    'payment_status' => 'pending',
                    'amount' => $finalAmount,
                    'notes' => "Method: manual",
                    'enrollment_date' => now(),
                    'coupon_code' => $couponCode ?? $appliedReferralCode,
                    'discount_amount' => $discountAmount
                ]);
                
                // Record referral usage if applied
                if ($appliedReferralCode && $pendingReferral) {
                    $referrer = User::find($pendingReferral->referrer_id);
                    if ($referrer && $referrer->referral_code) {
                        $validation = $this->referralService->validateCoupon($referrer->referral_code, $user->id, $course->price, 'INR');
                        if ($validation['valid']) {
                            $this->referralService->recordUsage($validation, $user->id);
                            $pendingReferral->update(['status' => 'completed']);
                        }
                    }
                }
            }

            // Send enrollment notification email to admin
            $mailer = new Mailer();
            $adminEmail = env('ADMIN_EMAIL', 'admin@welittlefarmers.com');
            $mailer->sendEnrollmentNotification(
                $adminEmail,
                $user->name,
                $user->email,
                $course->title ?? 'Unknown course',
                $finalAmount ?? ($course->price ?? 0),
                $inquiretime = now()->format('d M Y, h:i A')
            );


            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully! Your access will be activated within 24 hours.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Manual payment error', [
                'error' => $e->getMessage(),
                'user_id' => $request->user_id ?? null,
                'course_id' => $request->course_id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again or contact support.'
            ], 500);
        }
    }

    /**
     * Admin function to verify and activate payment
     */
    public function verifyPayment($user_id)
    {
        // Only admin should access this
        if (!auth('admin')->check()) {
            abort(403, 'Unauthorized');
        }

        DB::beginTransaction();

        try {
            $user = User::findOrFail($user_id);

            if ($user->payment_status != 1) {
                return redirect()->back()
                    ->with('error', 'This payment is not pending verification.');
            }

            // Get course details
            $course = Course::find($user->pending_course_id);

            // Update user payment status to verified
            $user->update([
                'payment_status' => 2, // 2 = Verified
                'payment_verified_at' => now(),
                'verified_by' => auth('admin')->id()
            ]);

            // Create UserProgress entry to give course access
            UserProgress::create([
                'user_id' => $user->id,
                'course_id' => $user->pending_course_id,
                'payment_status' => 1, // Paid/Active
                'is_completed' => 0
            ]);

            // Handle Referral/Coupon Usage Recording
            // We need to fetch the enrollment record to get the coupon code used
            $enrollment = CourseEnrollment::where('user_id', $user->id)
                ->where('course_id', $user->pending_course_id)
                ->latest()
                ->first();

            if ($enrollment && $enrollment->coupon_code) {
                // Re-validate to get the object (Referrer/Discount) - Manual is ALWAYS INR
                $validation = $this->referralService->validateCoupon($enrollment->coupon_code, $user->id, $course->price, 'INR');
                
                if ($validation['valid']) {
                    $this->referralService->recordUsage($validation, $user->id);
                }
            }


            DB::commit();



            return redirect()->back()
                ->with('success', 'Payment verified! User now has access to the course.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment verification error', [
                'error' => $e->getMessage(),
                'user_id' => $user_id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to verify payment: ' . $e->getMessage());
        }
    }

    /**
     * Admin function to reject payment
     */
    public function rejectPayment($user_id)
    {
        // Only admin should access this
        if (!auth('admin')->check()) {
            abort(403, 'Unauthorized');
        }

        try {
            $user = User::findOrFail($user_id);

            // Reset payment status
            $user->update([
                'pending_course_id' => null,
                'payment_status' => 0, // 0 = No payment
                'payment_submitted_at' => null
            ]);



            return redirect()->back()
                ->with('success', 'Payment rejected successfully.');
        } catch (\Exception $e) {
            Log::error('Payment rejection error', [
                'error' => $e->getMessage(),
                'user_id' => $user_id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to reject payment.');
        }
    }
}
