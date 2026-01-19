<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    public function getCompletedItems(Request $request)
    {
        // $request->validate([
        //     'user_id' => 'required|exists:users,id',
        // ]);

        // [FIX] IDOR: Use authenticated user ID
        $user_id = auth()->id();
        if (!$user_id) {
             return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        // $user_id = $request->input('user_id');

        // Fetch all certificates associated with the user
        $certificates = Certificate::where('user_id', $user_id)
            ->with(['course', 'course.sections.lessons', 'course.sections.questions'])
            ->get();

        if ($certificates->isEmpty()) {
            return response()->json(['message' => 'No completed items found for this user'], 404);
        }

        // Format the response
        $response = $certificates->map(function ($certificate) use ($user_id) {
            $course = $certificate->course;

            // Fetch completed lessons
            $completedLessons = $course->sections->flatMap(function ($section) use ($user_id) {
                return $section->lessons->filter(function ($lesson) use ($user_id) {
                    return DB::table('lesson_progress')
                        ->where('lesson_id', $lesson->id)
                        ->where('user_id', $user_id)
                        ->where('is_completed', true)
                        ->exists();
                })->map(function ($lesson) {
                    return [
                        'itemName' => $lesson->title ?? '',
                        'itemType' => 'video',
                        'lesson_duration' => sprintf('%02d:%02d mins', floor($lesson->duration / 60), $lesson->duration % 60),
                        'lesson_video_url' => $lesson->video_url ?? '',
                    ];
                });
            });

            // Fetch completed quizzes
            $completedQuizzes = $course->sections->flatMap(function ($section) use ($user_id) {
                return $section->questions->filter(function ($question) use ($user_id) {
                    return DB::table('quiz_progress')
                        ->where('question_id', $question->id)
                        ->where('user_id', $user_id)
                        ->where('is_completed', true)
                        ->exists();
                })->map(function ($question) {
                    return [
                        'itemName' => $question->question ?? '',
                        'itemType' => 'quiz',
                    ];
                });
            });

            // Combine completed lessons and quizzes
            $completedItems = $completedLessons->merge($completedQuizzes);

            return [
                'certificate_url' => $certificate->certificate_url ?? '',
                'course_title' => $course->title ?? '',
                'completed_items' => $completedItems,
            ];
        });

        return response()->json([
            'message' => 'Completed items and certificates fetched successfully',
            'certificates' => $response
        ], 200);
    }
}