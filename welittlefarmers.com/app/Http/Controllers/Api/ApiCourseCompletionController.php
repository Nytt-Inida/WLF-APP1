<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiCourseCompletionController extends Controller
{
    /**
     * Check course completion for mobile app
     * This excludes sample tests to match what the app displays
     */
    public function checkCompletion(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $courseId = $request->input('course_id');

        $lessonStats = $this->getLessonStats($userId, $courseId);
        $quizStats = $this->getQuizStats($userId, $courseId);
        $reviewStats = $this->getReviewStats($userId, $courseId);

        $allLessonsDone = $lessonStats['completed_count'] >= $lessonStats['total_count'] && $lessonStats['total_count'] > 0;
        $allQuizzesDone = $quizStats['completed_count'] >= $quizStats['total_count'] && $quizStats['total_count'] > 0;
        $reviewCompleted = $reviewStats['completed'];
        $reviewRequired = $reviewStats['required'];

        // Course is complete only if lessons, quizzes, and review (if required) are done
        $courseCompleted = $allLessonsDone && $allQuizzesDone && (!$reviewRequired || $reviewCompleted);

        return response()->json([
            'completed' => $courseCompleted,
            'lessons_completed' => $allLessonsDone,
            'quizzes_completed' => $allQuizzesDone,
            'review_completed' => $reviewCompleted,
            'review_required' => $reviewRequired,
            // Detailed stats for progress bars
            'lesson_stats' => $lessonStats,
            'quiz_stats' => $quizStats,
            'review_stats' => $reviewStats
        ]);
    }

    private function getReviewStats(int $userId, int $courseId): array
    {
        $totalQuestions = \App\Models\ReviewQuestion::where('course_id', $courseId)
            ->where('is_active', true)
            ->count();
        
        if ($totalQuestions === 0) {
            return [
                'total_count' => 0,
                'completed_count' => 0,
                'completed' => true,
                'required' => false
            ];
        }

        $answeredQuestions = \App\Models\ReviewAnswer::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->count();

        return [
            'total_count' => $totalQuestions,
            'completed_count' => $answeredQuestions,
            'completed' => $answeredQuestions >= $totalQuestions,
            'required' => true
        ];
    }

    /**
     * Get lesson statistics excluding sample tests
     * This matches what the mobile app displays
     */
    private function getLessonStats(int $userId, int $courseId): array
    {
        // Count all lessons for this course (excluding sample tests)
        $totalLessons = DB::table('lessons')
            ->where('course_id', $courseId)
            ->whereRaw('LOWER(title) NOT LIKE ?', ['%sample%'])
            ->count();
        
        $completedLessons = 0;
        
        if ($totalLessons > 0) {
            $completedLessons = DB::table('lesson_progress')
                ->join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
                ->where('lessons.course_id', $courseId)
                ->whereRaw('LOWER(lessons.title) NOT LIKE ?', ['%sample%'])
                ->where('lesson_progress.user_id', $userId)
                ->where('lesson_progress.is_completed', 1)
                ->count();
        }

        return [
            'total_count' => $totalLessons,
            'completed_count' => $completedLessons
        ];
    }

    /**
     * Get quiz statistics excluding sample tests
     * This matches what the mobile app displays
     */
    private function getQuizStats(int $userId, int $courseId): array
    {
        // Collect sections for course
        $sections = DB::table('sections')->where('course_id', $courseId)->pluck('id');
        
        $totalQuizzes = 0;
        $completedQuizzes = 0;

        foreach ($sections as $sectionId) {
            // Get questions, excluding sample tests
            $questionIds = DB::table('questions')
                ->where('section_id', $sectionId)
                ->whereRaw('LOWER(quiz_title) NOT LIKE ?', ['%sample%'])
                ->pluck('id');

            // Only count sections that actually have questions (excluding sample tests)
            if ($questionIds->count() === 0) {
                continue;
            }
            
            $totalQuizzes++;

            $answeredCount = DB::table('quiz_progress')
                ->where('user_id', $userId)
                ->whereIn('question_id', $questionIds)
                ->where('is_correct', 1) // Enforce ALL Correct answers
                ->count();

            // Check if user answered ALL questions CORRECTLY in this section
            if ($answeredCount >= $questionIds->count()) {
                $completedQuizzes++;
            }
        }

        return [
            'total_count' => $totalQuizzes,
            'completed_count' => $completedQuizzes
        ];
    }
}

