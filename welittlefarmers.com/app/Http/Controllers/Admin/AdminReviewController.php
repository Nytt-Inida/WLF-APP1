<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewQuestion;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    /**
     * Display list of courses to manage reviews
     */
    public function courses()
    {
        $courses = Course::orderBy('title')->get();
        return view('admin.reviews.courses', compact('courses'));
    }

    /**
     * Display a listing of review questions for a course
     */
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        $questions = ReviewQuestion::where('course_id', $courseId)
            ->orderBy('order')
            ->get();
        
        return view('admin.reviews.index', compact('course', 'questions'));
    }

    /**
     * Show the form for creating a new review question
     */
    public function create($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('admin.reviews.create', compact('course'));
    }

    /**
     * Store a newly created review question
     */
    public function store(Request $request, $courseId)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
            'option_1' => 'required|string|max:255',
            'option_2' => 'required|string|max:255',
            'option_3' => 'required|string|max:255',
            'option_4' => 'nullable|string|max:255',
            'option_5' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order' => 'integer|min:0'
        ]);

        ReviewQuestion::create([
            'course_id' => $courseId,
            'question' => $request->question,
            'option_1' => $request->option_1,
            'option_2' => $request->option_2,
            'option_3' => $request->option_3,
            'option_4' => $request->option_4,
            'option_5' => $request->option_5,
            'is_active' => $request->has('is_active') ? true : false,
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.reviews.index', $courseId)
            ->with('success', 'Review question created successfully.');
    }

    /**
     * Show the form for editing a review question
     */
    public function edit($courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $question = ReviewQuestion::where('course_id', $courseId)
            ->findOrFail($id);
        
        return view('admin.reviews.edit', compact('course', 'question'));
    }

    /**
     * Update the specified review question
     */
    public function update(Request $request, $courseId, $id)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
            'option_1' => 'required|string|max:255',
            'option_2' => 'required|string|max:255',
            'option_3' => 'required|string|max:255',
            'option_4' => 'nullable|string|max:255',
            'option_5' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order' => 'integer|min:0'
        ]);

        $question = ReviewQuestion::where('course_id', $courseId)
            ->findOrFail($id);

        $question->update([
            'question' => $request->question,
            'option_1' => $request->option_1,
            'option_2' => $request->option_2,
            'option_3' => $request->option_3,
            'option_4' => $request->option_4,
            'option_5' => $request->option_5,
            'is_active' => $request->has('is_active') ? true : false,
            'order' => $request->order ?? $question->order
        ]);

        return redirect()->route('admin.reviews.index', $courseId)
            ->with('success', 'Review question updated successfully.');
    }

    /**
     * Remove the specified review question
     */
    public function destroy($courseId, $id)
    {
        $question = ReviewQuestion::where('course_id', $courseId)
            ->findOrFail($id);
        
        $question->delete();

        return redirect()->route('admin.reviews.index', $courseId)
            ->with('success', 'Review question deleted successfully.');
    }

    /**
     * Toggle active status of a review question
     */
    public function toggle($courseId, $id)
    {
        $question = ReviewQuestion::where('course_id', $courseId)
            ->findOrFail($id);
        
        $question->is_active = !$question->is_active;
        $question->save();

        return redirect()->route('admin.reviews.index', $courseId)
            ->with('success', 'Review question status updated.');
    }
}

