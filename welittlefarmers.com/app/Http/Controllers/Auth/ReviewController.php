<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ReviewQuestion;
use App\Models\ReviewAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Get review questions for a course with existing answers
     */
    public function getQuestions($courseId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $questions = ReviewQuestion::where('course_id', $courseId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // Get existing answers for this user and course
        $existingAnswers = ReviewAnswer::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->pluck('selected_options', 'review_question_id')
            ->toArray();

        $questionsData = $questions->map(function ($question) use ($existingAnswers) {
            $savedOptions = $existingAnswers[$question->id] ?? null;
            
            // Get all non-null options
            $options = collect([
                $question->option_1,
                $question->option_2,
                $question->option_3,
                $question->option_4,
                $question->option_5
            ])->filter()->values()->toArray();
            
            return [
                'id' => $question->id,
                'question' => $question->question,
                'options' => $options,
                'saved_options' => $savedOptions, // Array of selected option indices [0, 1, 2, 3, 4] or null
                'is_answered' => $savedOptions !== null
            ];
        });

        // Calculate completion status
        $totalQuestions = $questions->count();
        $answeredQuestions = count($existingAnswers);
        $isCompleted = $totalQuestions > 0 && $answeredQuestions >= $totalQuestions;

        return response()->json([
            'success' => true,
            'questions' => $questionsData,
            'status' => [
                'total' => $totalQuestions,
                'answered' => $answeredQuestions,
                'completed' => $isCompleted
            ]
        ]);
    }

    /**
     * Submit review answers
     */
    public function submitAnswers(Request $request, $courseId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:review_questions,id',
            'answers.*.selected_options' => 'required|array',
            'answers.*.selected_options.*' => 'integer|in:0,1,2,3,4'
        ]);

        // Verify all questions belong to this course
        $questionIds = collect($request->answers)->pluck('question_id');
        $courseQuestions = ReviewQuestion::where('course_id', $courseId)
            ->where('is_active', true)
            ->pluck('id')
            ->toArray();

        $invalidQuestions = $questionIds->diff($courseQuestions);
        if ($invalidQuestions->isNotEmpty()) {
            return response()->json([
                'success' => false,
                'error' => 'Some questions do not belong to this course'
            ], 400);
        }

        // Use database transaction to ensure all answers are saved atomically
        try {
            DB::beginTransaction();

            $savedAnswers = [];
            
            // Save or update answers
            foreach ($request->answers as $answerData) {
                $answer = ReviewAnswer::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'course_id' => $courseId,
                        'review_question_id' => $answerData['question_id']
                    ],
                    [
                        'selected_options' => $answerData['selected_options']
                    ]
                );
                
                $savedAnswers[] = $answer->id;
            }

            // Verify all answers were saved
            $savedCount = ReviewAnswer::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->whereIn('review_question_id', $questionIds)
                ->count();

            if ($savedCount < count($request->answers)) {
                DB::rollBack();
                Log::error('Review answers not fully saved', [
                    'user_id' => $user->id,
                    'course_id' => $courseId,
                    'expected' => count($request->answers),
                    'saved' => $savedCount
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to save all review answers. Please try again.'
                ], 500);
            }

            DB::commit();

            Log::info('Review answers saved successfully', [
                'user_id' => $user->id,
                'course_id' => $courseId,
                'answers_count' => count($request->answers),
                'saved_answer_ids' => $savedAnswers
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully',
                'saved_count' => $savedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving review answers', [
                'user_id' => $user->id,
                'course_id' => $courseId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while saving your review. Please try again.'
            ], 500);
        }
    }

    /**
     * Check if user has completed review for a course
     */
    public function checkCompletion($courseId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['completed' => false], 401);
        }

        // Check if there are any review questions for this course
        $hasQuestions = ReviewQuestion::where('course_id', $courseId)
            ->where('is_active', true)
            ->exists();

        // If no questions exist, consider review as completed (not required)
        if (!$hasQuestions) {
            return response()->json([
                'completed' => true,
                'required' => false
            ]);
        }

        $completed = ReviewAnswer::hasCompletedReview($user->id, $courseId);

        return response()->json([
            'completed' => $completed,
            'required' => true
        ]);
    }
}

