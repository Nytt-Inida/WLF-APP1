<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;
use App\Mail\Mailer;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\SeoMeta;
use SEO;


class UserRegistrationController extends Controller
{
    public function showLoginForm()
    {
        // Getting SEO tags info
        $seo = SEOMeta::where('page_type', 'login')->first();
        if ($seo) {
            SEO::setTitle($seo->title);
            SEO::setDescription($seo->description);
            if ($seo->keywords) {
                SEO::metatags()->addKeyword(explode(',', $seo->keywords));
            }
        } else {
            // Fallback SEO
            SEO::setTitle('Little Farmers Academy - Login');
            SEO::setDescription('Login to your Little Farmers Academy account.');
            SEO::metatags()->addKeyword(['login', 'little farmers academy', 'education']);
        }
        return view('login', compact('seo'));
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
            // Generate a new OTP since 10 minutes have passed or it's the first OTP
            $otp = Str::random(6);
            $now = Carbon::now();

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
            // If the email is not found in the database
            return response()->json(['code' => 404, 'message' => 'Email not found. Please signup first.'], 404);
        }
    }

    public function login(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        // Attempt to find the user by email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Parse the OTP creation time
            $otpCreatedAt = Carbon::parse($user->otp_created_at);
            $now = Carbon::now();

            // Check if the OTP is valid (within 10 minutes and matches)
            if ($now->diffInMinutes($otpCreatedAt) <= 10 && $user->otp === $request->otp) {
                // Log the user in
                Auth::login($user);

                // Regenerate the session to prevent session fixation
                $request->session()->regenerate();

                // Store referral code in session if passed from signup flow
                if ($request->filled('referral_code')) {
                    session(['referral_code' => $request->referral_code]);
                }

                // Check if there's a redirect URL, otherwise go to course details (Course 1)
                $redirectUrl = $request->input('redirect_to') ?? route('course.details', ['id' => 1]);
                return redirect()->away($redirectUrl)->with('success', 'Login successful!');
            } else {
                // OTP is invalid or expired
                return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
            }
        } else {
            // User with the provided email does not exist
            return back()->withErrors(['email' => 'Email not found.']);
        }
    }


    public function showSignupForm()
    {
        // Getting the SEO meta data for the registration page
        $seo = SEOMeta::where('page_type', 'signup')->first();
        if ($seo) {
            SEO::setTitle($seo->title);
            SEO::setDescription($seo->description);
            if ($seo->keywords) {
                SEO::metatags()->addKeyword(explode(',', $seo->keywords));
            }
        } else {
            // Fallback SEO
            SEO::setTitle('Little Farmers Academy - Signup');
            SEO::setDescription('We Little Farmer is an educational platform for kids, teaching them how to grow plants and understand the importance of nature.');
            SEO::metatags()->addKeyword(['online education', 'e-learning', 'coaching', 'education', 'teaching', 'learning']);
        }

        return view('signup', compact('seo'));
    }

    public function signup(Request $request)
    {
        // Define the validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'school_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'country' => 'required|string|max:255',
            'referral_code' => 'nullable|string|max:255', // Add referral_code as nullable
        ];

        // Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if the validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if referral_code exists and is valid
        if ($request->filled('referral_code')) {
            $validReferral = User::where('referral_code', $request->referral_code)->first();

            if (!$validReferral) {
                // If the referral code is invalid, return with an error
                return redirect()->back()->with('error', 'The referral code is invalid.')->withInput();
            }
        }

        // Create the user if validation passes and referral code is valid (or not provided)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'school_name' => $request->school_name,
            'age' => $request->age,
            'country' => $request->country,
            // 'referral_code' => $request->referral_code, // DO NOT save this here, it is the REFERRER's code, not the new user's code.
            'password' => Hash::make('defaultpassword'), 
        ]);

        // Generate Registration OTP
        $otp = Str::random(6);
        $now = Carbon::now();

        // Update the OTP and otp_created_at fields in the database
        $user->update([
            'otp' => $otp,
            'otp_created_at' => $now,
        ]);

        // Send the OTP via email
        try {
            $mailer = new Mailer();
            $mailer->sendOtp($user->email, $otp);
            $message = 'Signup successful! OTP sent to your email. Please login.';
        } catch (\Exception $e) {
            Log::error('OTP Email sending failed: ' . $e->getMessage());
            $message = 'Signup successful! But failed to send OTP. Please try "Get OTP" on login.';
        }

        // Determine redirect URL
        // Use provided redirect_to if available (e.g. from generic coupon claim), otherwise default to course details
        $courseId = $request->input('course_id', 1);
        $targetUrl = $request->input('redirect_to') ?? route('course.details', ['id' => $courseId]);

        // Redirect to login page with auto-fill data and redirect intent
        return redirect()->route('login.form', [
            'redirect_to' => $targetUrl,
            'referral_code' => $request->referral_code // Pass explicitly in URL
        ])
            ->with('success', $message)
            ->with('email', $user->email)
            ->with('otp_sent', true)
            ->with('pending_referral_code', $request->referral_code); // Pass referrer's code to login flow
    }
}
