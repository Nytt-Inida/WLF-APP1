<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use App\Jobs\GenerateSubtitleJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SubtitleController extends Controller
{
    public function generateSingle(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id'
        ]);

        $lesson = Lesson::findOrFail($request->lesson_id);

        if (!$lesson->video_url) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson has no video URL'
            ], 400);
        }

        // Dispatch the job
        GenerateSubtitleJob::dispatch($lesson->id, $lesson->video_url);

        $lesson->update(['subtitle_status' => 'pending']);

        return response()->json([
            'success' => true,
            'message' => 'Subtitle generation started for: ' . $lesson->title,
            'lesson_id' => $lesson->id
        ]);
    }

    /**
     * Generate subtitles for all lessons in a course
     */
    public function generateBulk(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        $course = Course::with('sections.lessons')->findOrFail($request->course_id);
        $queued = 0;
        $skipped = 0;


        foreach ($course->sections as $section) {
            foreach ($section->lessons as $lesson) {
                if ($lesson->video_url) {
                    // Only queue if not already completed
                    if ($lesson->subtitle_status !== 'completed') {
                        GenerateSubtitleJob::dispatch($lesson->id, $lesson->video_url);
                        $lesson->update(['subtitle_status' => 'pending']);
                        $queued++;
                    } else {
                        $skipped++;
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Queued {$queued} lessons for subtitle generation",
            'course_id' => $course->id,
            'total_queued' => $queued,
            'skipped' => $skipped
        ]);
    }

    /**
     * Check subtitle generation status for a lesson
     */
    public function checkStatus($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        return response()->json([
            'lesson_id' => $lesson->id,
            'lesson_title' => $lesson->title,
            'status' => $lesson->subtitle_status,
            'vtt_path' => $lesson->vtt_path ? Storage::url($lesson->vtt_path) : null,
            'error' => $lesson->subtitle_error
        ]);
    }

    /**
     * Retry failed subtitle generations
     */
    public function retryFailed(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        $course = Course::with('sections.lessons')->findOrFail($request->course_id);
        $retried = 0;

        foreach ($course->sections as $section) {
            foreach ($section->lessons as $lesson) {
                if ($lesson->subtitle_status === 'failed' && $lesson->video_url) {
                    GenerateSubtitleJob::dispatch($lesson->id, $lesson->video_url);
                    $lesson->update([
                        'subtitle_status' => 'pending',
                        'subtitle_error' => null
                    ]);
                    $retried++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Retrying {$retried} failed subtitles",
            'total_retried' => $retried
        ]);
    }

    /**
     * Get subtitle statistics for a course
     */
    public function getCourseStats($courseId)
    {
        $course = Course::with('sections.lessons')->findOrFail($courseId);

        $stats = [
            'total' => 0,
            'completed' => 0,
            'processing' => 0,
            'pending' => 0,
            'failed' => 0
        ];

        foreach ($course->sections as $section) {
            foreach ($section->lessons as $lesson) {
                if ($lesson->video_url) {
                    $stats['total']++;
                    $stats[$lesson->subtitle_status]++;
                }
            }
        }

        return response()->json($stats);
    }
}
