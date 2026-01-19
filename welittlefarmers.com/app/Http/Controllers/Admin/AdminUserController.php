<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(20);
        
        // Get categories for filter dropdown
        $categories = User::distinct()->pluck('category')->filter();

        // Calculate statistics
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::where('payment_status', 2)->count(),
            'pending_users' => User::where('payment_status', 1)->count(),
            'unverified_users' => User::where('payment_status', 0)->count(),
        ];

        $currencySymbol = getCurrencySymbol();

        return view('admin.users.index', compact('users', 'categories', 'stats', 'currencySymbol'));
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // Get user's enrolled courses
        $enrolledCourses = $user->courseProgress()
            ->with('course')
            ->get();

        // Get user's lesson progress
        $lessonProgress = $user->lessonProgress()
            ->with('lesson')
            ->get();

        // Get payment details
        $paymentDetails = [
            'status' => $user->getPaymentStatusTextAttribute(),
            'pending_course' => $user->pendingCourse,
            'submitted_at' => $user->payment_submitted_at,
            'verified_at' => $user->payment_verified_at,
            'verified_by_admin' => $user->verifiedByAdmin,
        ];

        $currencySymbol = getCurrencySymbol();

        return view('admin.users.show', compact('user', 'enrolledCourses', 'lessonProgress', 'paymentDetails', 'currencySymbol'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $courses = Course::all();
        $currencySymbol = getCurrencySymbol();
        
        return view('admin.users.edit', compact('user', 'courses', 'currencySymbol'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'age' => 'nullable|integer|min:1|max:120',
            'category' => 'nullable|string',
            'school_name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user->id)
                       ->with('success', 'User updated successfully!');
    }
}
