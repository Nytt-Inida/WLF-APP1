<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use App\Mail\Mailer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'school_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'country' => 'required|string|max:255',
            'referral_code' => 'nullable|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(['code' => 422, 'errors' => $validator->errors(),], 422);
        }

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            return response()->json(['message' => 'Email already exists. Please log in.'], 409);
        }

        // Check if referral code exists
        if ($request->filled('referral_code')) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
            if (!$referrer) {
                return response()->json(['message' => 'Invalid referral code.'], 400);
            }
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'school_name' => $request->school_name,
            'age' => $request->age,
            'country' => $request->country,
            'referral_code' => $request->referral_code,
            'password' => Hash::make(Str::random(8)), // Assign a random password
        ]);

        return response()->json(['message' => 'Registration successful! You can now log in.'], 201);
    }



    public function sendOtp(Request $request)
    {
        // Manually create a validator instance
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Retrieve the `otp_created_at` timestamp and convert it to UTC
            $otpCreatedAt = $user->otp_created_at ? Carbon::parse($user->otp_created_at)->setTimezone('UTC') : null;
            $now = Carbon::now('UTC');

            // Calculate the difference in minutes between the current time and the OTP creation time
            if ($otpCreatedAt) {
                $minutesElapsed = $otpCreatedAt->diffInMinutes($now);
            } else {
                $minutesElapsed = 10; // If no OTP exists, treat it as expired
            }

            // Check if 10 minutes have passed
            if ($minutesElapsed >= 10) {
                // Generate a new OTP since 10 minutes have passed or it's the first OTP
                $otp = Str::random(6);

                // Update the OTP and otp_created_at fields in the database
                $user->update([
                    'otp' => $otp,
                    'otp_created_at' => $now, // Save the current time to otp_created_at
                ]);

                // Send the OTP via email
                $mailer = new Mailer();
                $mailer->sendOtp($user->email, $otp);

                return response()->json(['code' => 200, 'message' => 'New OTP sent to your email address.'], 200);
            } else {
                // Calculate the remaining time in minutes
                $minutesRemaining = 10 - $minutesElapsed;

                return response()->json([
                    'code' => 200,
                    'message' => "OTP is still valid. Please use the existing OTP. You can request a new OTP in $minutesRemaining minutes."
                ], 200);
            }
        } else {
            // If the email is not found in the database
            return response()->json(['code' => 404, 'message' => 'Email not found. Please Signup first.'], 404);
        }
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $otpCreatedAt = Carbon::parse($user->otp_created_at);
            $now = Carbon::now();

            if ($now->diffInMinutes($otpCreatedAt) <= 30 && $user->otp === $request->otp) {
                // Create token
                $token = $user->createToken('authToken')->plainTextToken;

                // Generate default profile photo URL
                $defaultProfilePhotoUrl = "https://ui-avatars.com/api/?name=" . substr($user->name, 0, 1) . "&color=7F9CF5&background=EBF4FF";

                // Generate referral code if it doesn't exist
                if (empty($user->referral_code)) {
                    $user->referral_code = \App\Models\User::generateUniqueReferralCode();
                    $user->save();
                }

                // Check if referral system is enabled
                $isReferralEnabled = \App\Models\ReferralSetting::getValue('is_enabled', '1') == '1';

                return response()->json([
                    'code' => 200,
                    'message' => 'Login successful!',
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'country' => $user->country ?? "",
                    'school_name' => $user->school_name ?? "",
                    'profile_photo' => $defaultProfilePhotoUrl,
                    'token' => $token,
                    'age' => $user->age, // Add age to the response
                    'referral_code' => $user->referral_code ?? "", // Add referral code to the response
                    'is_referral_enabled' => $isReferralEnabled, // Add referral enabled status
                ], 200);
            } else {
                return response()->json(['code' => 401, 'message' => 'Invalid or expired OTP.'], 401);
            }
        } else {
            return response()->json(['code' => 404, 'message' => 'Email not found.'], 404);
        }
    }




    public function signup(Request $request)
    {
        try {
            // Define the validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'school_name' => 'required|string|max:255',
                'age' => 'required|integer|min:1',
                'country' => 'required|string|max:255',
                'referral_code' => 'nullable|string|max:255', // Add this line
            ];

            // Create the validator instance
            $validator = Validator::make($request->all(), $rules);

            // Check if the validation fails
            if ($validator->fails()) {
                return response()->json(['code' => 422, 'errors' => $validator->errors(),], 422);
            }

            // Validate referral code if provided
            $referrerId = null;
            if ($request->filled('referral_code')) {
                $referrer = User::where('referral_code', $request->referral_code)->first();
                if (!$referrer) {
                    return response()->json(['code' => 400, 'message' => 'Invalid referral code.'], 400);
                }
                $referrerId = $referrer->id;
            }

            // Create the user if validation passes
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'school_name' => $request->school_name,
                'age' => $request->age,
                'country' => $request->country,
            ]);

            // Store referrer's code if provided (this will be used during payment)
            // We'll store it in a way that we can retrieve it later
            if ($referrerId) {
                // Store in session or database - for API, we'll store in user's pending_referral_code
                // Since we don't have that field, we'll use a referral record in pending state
                \App\Models\Referral::create([
                    'referrer_id' => $referrerId,
                    'referred_user_id' => $user->id,
                    'status' => 'pending', // Will be completed when payment is made
                ]);
            }

            return response()->json(['message' => 'Signup successful! You can now log in.', 'code' => 200], 200);
        } catch (\Exception $e) {
            // Log the detailed error message
            Log::error('Signup failed: ' . $e->getMessage());

            // Return a generic error message
            return response()->json(['message' => 'Signup failed. Please try again.', 'code' => 500], 500);
        }
    }

    public function getProfile(Request $request)
    {
        // Validate the input
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user_id = $request->input('user_id');

        // Fetch the user details
        $user = User::findOrFail($user_id);

        // Generate default profile photo URL
        $defaultProfilePhotoUrl = "https://ui-avatars.com/api/?name=" . substr($user->name, 0, 1) . "&color=7F9CF5&background=EBF4FF";

        // Generate referral code if it doesn't exist
        if (empty($user->referral_code)) {
            $user->referral_code = \App\Models\User::generateUniqueReferralCode();
            $user->save();
        }

        // Check if referral system is enabled
        $isReferralEnabled = \App\Models\ReferralSetting::getValue('is_enabled', '1') == '1';

        // Get pending referral code (referrer's code used during signup)
        $pendingReferral = \App\Models\Referral::where('referred_user_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        $signupReferralCode = null;
        if ($pendingReferral) {
            $referrer = User::find($pendingReferral->referrer_id);
            if ($referrer) {
                $signupReferralCode = $referrer->referral_code;
            }
        }

        // Fetch active discount/reward codes for the user
        $userRewards = \App\Models\DiscountCode::where('user_id', $user->id)
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where(function($query) {
                $query->where('max_usage', 0)
                    ->orWhereRaw('usage_count < max_usage');
            })
            ->get()
            ->map(function($reward) {
                return [
                    'code' => $reward->code,
                    'type' => $reward->type,
                    'value' => $reward->value_inr ?? $reward->value,
                    'value_usd' => $reward->value_usd ?? null,
                ];
            });

        // Prepare the response data
        $response = [
            'code' => 200,
            'message' => 'Profile fetched successfully!',
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profile_photo' => $defaultProfilePhotoUrl,
            'referral_code' => $user->referral_code,
            'is_referral_enabled' => $isReferralEnabled,
            'rewards' => $userRewards,
            'signup_referral_code' => $signupReferralCode, // Referrer's code used during signup
        ];


        // Return the user profile information
        return response()->json($response, 200);
    }


    public function logout(Request $request)
    {
        // Get user token and revoke it
        $request->user()->currentAccessToken()->delete();

        return response()->json(['code' => 200, 'message' => 'Logout successful'], 200);
    }
    public function updateProfile(Request $request)
    {
        // Create a validator instance and define the validation rules
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->user_id,
            'school_name' => 'nullable|string|max:255',
            'age' => 'required|integer|min:1',
            'country' => 'nullable|string|max:255',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Fetch the user from the database
        $user = User::findOrFail($request->user_id);

        // Update the user's profile information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'school_name' => $request->school_name,
            'age' => $request->age,
            'country' => $request->country,
        ]);

        // Prepare the response data
        $response = [
            'code' => 200,
            'message' => 'Profile updated successfully!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'school_name' => $user->school_name,
                'age' => $user->age,
                'country' => $user->country,
            ],
        ];

        // Return the user profile information
        return response()->json($response, 200);
    }
}
