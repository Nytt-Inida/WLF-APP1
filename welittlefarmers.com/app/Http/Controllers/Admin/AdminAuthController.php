<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Blog;
use App\Models\User;
use App\Models\CourseEnrollment;

class AdminAuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if admin is active
            if (!Auth::guard('admin')->user()->is_active) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated.',
                ]);
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::guard('admin')->user()->name);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    // Show registration form (only for first-time setup)
    public function showRegister()
    {
        // Check if any admin exists
        if (Admin::count() > 0) {
            return redirect()->route('admin.login')
                ->with('error', 'Registration is closed. Please login.');
        }

        return view('admin.auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        // Prevent registration if admin already exists
        if (Admin::count() > 0) {
            return redirect()->route('admin.login')
                ->with('error', 'Registration is closed.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Admin account created successfully!');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }

    // Show dashboard
    public function dashboard()
    {
        $stats = [
            'total_blogs' => Blog::count(),
            'published_blogs' => Blog::where('is_published', true)->count(),
            'draft_blogs' => Blog::where('is_published', false)->count(),
            'total_views' => Blog::sum('views'),
        ];

        $recentBlogs = Blog::orderBy('created_at', 'desc')->limit(5)->get();

        $paymentStats = [
            'pending_payments' => User::where('payment_status', 1)->count(),
            'verified_payments_today' => User::where('payment_status', 2)
                ->whereDate('payment_verified_at', today())
                ->count(),
            'total_revenue' => User::where('payment_status', 2)
                ->with('pendingCourse')
                ->get()
                ->sum(function ($user) {
                    return $user->pendingCourse->price ?? 0;
                }),
        ];

        // User Management Stats
        $userStats = [
            'total_users' => User::count(),
            'verified_users' => User::where('payment_status', 2)->count(),
            'pending_payment_users' => User::where('payment_status', 1)->count(),
            'unverified_users' => User::where('payment_status', 0)->count(),
        ];

        // Recent users
        $recentUsers = User::orderBy('created_at', 'desc')->limit(10)->get();

        // Recent course enrollments
        $recentEnrollments = CourseEnrollment::orderBy('created_at', 'desc')
            ->with('user', 'course')
            ->limit(10)
            ->get();

        $currencySymbol = getCurrencySymbol();

        return view('admin.dashboard', compact('stats', 'recentBlogs', 'paymentStats', 'userStats', 'recentUsers', 'recentEnrollments', 'currencySymbol'));
    }
}
