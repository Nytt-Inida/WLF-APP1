<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
   public function searchCourses(Request $request)
{
    $request->validate([
        'keyword' => 'required|string|min:1',
        'user_id' => 'required|exists:users,id',
    ]);

    $keyword = $request->input('keyword');
    $user_id = auth()->id();
    if (!$user_id) return response()->json(['code' => 401, 'message' => 'Unauthenticated'], 401);
    // $user_id = $request->input('user_id');

    // Perform the search
    $courses = Course::where('title', 'like', '%' . $keyword . '%')
        ->orWhere('description', 'like', '%' . $keyword . '%')
        ->get();

    // Add isFavourite and isPurchase status for each course
    $courses->each(function ($course) use ($user_id) {
        // Check if the course is a favorite
        $isFavourite = DB::table('favorites')
            ->where('user_id', $user_id)
            ->where('course_id', $course->id)
            ->exists();

        $course->isFavourite = $isFavourite ? 1 : 0;

        // Check if the user has purchased the course
        $progress = DB::table('user_progress')
            ->where('user_id', $user_id)
            ->where('course_id', $course->id)
            ->first();

        // Set isPurchase to true if payment_status is 1 (purchased)
        $course->isPurchase = ($progress && $progress->payment_status == 1) ? true : false;
    });

    // Hide unnecessary attributes
    $courses->makeHidden(['description']);

    if ($courses->isEmpty()) {
        return response()->json(['code' => 404, 'message' => 'No courses found', 'courses' => []], 404);
    }

    return response()->json(['code' => 200, 'courses' => $courses], 200);
}

  public function getSuggestions(Request $request)
{
    $request->validate([
        'keyword' => 'required|string|min:1',
    ]);

    $keyword = $request->input('keyword');

    // Perform a search to get suggestions
    $suggestions = Course::where('title', 'like', '%' . $keyword . '%')
        ->orWhere('description', 'like', '%' . $keyword . '%')
        ->take(20) // Limit results to 20
        ->pluck('title')
        ->toArray();

    if (empty($suggestions)) {
        return response()->json(['code' => 404, 'message' => 'No suggestions found.', 'data' => []], 404);
    }

    // Convert the array to an associative array with string keys
    $indexedSuggestions = [];
    foreach ($suggestions as $index => $suggestion) {
        $indexedSuggestions[(string)$index] = $suggestion;
    }

    return response()->json(['code' => 200, 'message' => 'Suggestions fetched successfully.', 'data' => $indexedSuggestions], 200);
}
public function getRelatedCourses(Request $request)
{
    $request->validate([
        'keyword' => 'required|string|min:1',
        'user_id' => 'required|exists:users,id',
    ]);

    $keyword = $request->input('keyword');
    $user_id = auth()->id();
    if (!$user_id) return response()->json(['code' => 401, 'message' => 'Unauthenticated'], 401);
    // $user_id = $request->input('user_id');

    // Perform a search to get related courses
    $courses = Course::where('title', 'like', '%' . $keyword . '%')
        ->orWhere('description', 'like', '%' . $keyword . '%')
        ->take(10) // Limit results to 10
        ->get()
        ->map(function ($course) use ($user_id) {
            // Check if the course is marked as favorite by the user
            $isFavourite = DB::table('favorites')
                ->where('user_id', $user_id)
                ->where('course_id', $course->id)
                ->exists();

            return [
                'id' => $course->id,
                'user_id' => $course->user_id,
                'title' => $course->title,
                'age_group' => $course->age_group,
                'price' => $course->price,
                'number_of_classes' => $course->number_of_classes,
                'image' => $course->image,
                'isFavourite' => $isFavourite ? 1 : 0,
            ];
        });

    if ($courses->isEmpty()) {
        return response()->json(['code' => 404, 'message' => 'No related courses found.', 'courses' => []], 404);
    }

    return response()->json(['code' => 200, 'message' => 'Related courses fetched successfully.', 'courses' => $courses], 200);
}


}