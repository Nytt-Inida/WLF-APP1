<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReviewQuestion;
use App\Models\ReviewAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class ApiReviewController extends Controller
{
    /**
     * Get review questions for a course with existing answers (for mobile app)
     * Uses Sanctum token authentication instead of session auth
     */
    public function getQuestions(Request $request, $courseId)
    {
        // Log for debugging - This should ALWAYS log if route is reached
        $bearerToken = $request->bearerToken();
        $authHeader = $request->header('Authorization');
        
        \Log::info('=== Review Questions API CALLED ===', [
            'course_id' => $courseId,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'has_token' => $bearerToken !== null,
            'token_preview' => $bearerToken ? substr($bearerToken, 0, 20) . '...' : 'none',
            'auth_header' => $authHeader,
            'all_headers' => $request->headers->all()
        ]);
        
        // Support both session-based auth (for website) and token-based auth (for mobile app)
        $user = null;
        
        // First, try session-based authentication (for website)
        if (Auth::check()) {
            $user = Auth::user();
            \Log::info('Review Questions API: User authenticated via session', [
                'user_id' => $user->id,
                'course_id' => $courseId
            ]);
        }
        // If no session, try token-based authentication (for mobile app)
        else if ($bearerToken) {
            try {
                // Method 1: Try findToken (handles token prefix if any)
                $tokenModel = PersonalAccessToken::findToken($bearerToken);
                
                if (!$tokenModel) {
                    // Method 2: Try direct hash lookup (Sanctum stores hashed tokens)
                    $tokenHash = hash('sha256', $bearerToken);
                    $tokenModel = PersonalAccessToken::where('token', $tokenHash)->first();
                    
                    if (!$tokenModel) {
                        // Method 3: Try with token prefix (some Sanctum configs use prefixes)
                        $tokenPrefix = config('sanctum.token_prefix', '');
                        if ($tokenPrefix && str_starts_with($bearerToken, $tokenPrefix)) {
                            $cleanToken = substr($bearerToken, strlen($tokenPrefix));
                            $tokenHash = hash('sha256', $cleanToken);
                            $tokenModel = PersonalAccessToken::where('token', $tokenHash)->first();
                        }
                    }
                }
                
                if ($tokenModel) {
                    // Check if token is not expired
                    if ($tokenModel->expires_at && $tokenModel->expires_at->isPast()) {
                        \Log::warning('Review Questions API: Token expired', [
                            'token_id' => $tokenModel->id,
                            'expires_at' => $tokenModel->expires_at
                        ]);
                    } else {
                        $user = $tokenModel->tokenable;
                        \Log::info('Review Questions API: Token found and valid', [
                            'token_id' => $tokenModel->id,
                            'user_id' => $user ? $user->id : null,
                            'tokenable_type' => $tokenModel->tokenable_type,
                            'token_name' => $tokenModel->name
                        ]);
                    }
                } else {
                    \Log::warning('Review Questions API: Token not found in database', [
                        'token_preview' => substr($bearerToken, 0, 20) . '...',
                        'token_length' => strlen($bearerToken),
                        'hash_attempt' => hash('sha256', $bearerToken)
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Review Questions API: Error validating token', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => substr($e->getTraceAsString(), 0, 500),
                    'token_preview' => substr($bearerToken, 0, 20) . '...'
                ]);
            }
        }
        
        if (!$user) {
            \Log::warning('Review Questions API: User not authenticated', [
                'course_id' => $courseId,
                'has_session' => Auth::check(),
                'token_present' => $bearerToken !== null,
                'token_length' => $bearerToken ? strlen($bearerToken) : 0,
                'auth_header' => $authHeader
            ]);
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        
        \Log::info('Review Questions API: User authenticated successfully', [
            'user_id' => $user->id,
            'course_id' => $courseId
        ]);

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
            
            // Build options array, including all 5 options (filter out null/empty)
            $options = array_filter([
                $question->option_1,
                $question->option_2,
                $question->option_3,
                $question->option_4,
                $question->option_5
            ], function($option) {
                return !empty($option);
            });
            
            return [
                'id' => $question->id,
                'question' => $question->question,
                'options' => array_values($options), // Re-index array to ensure sequential indices [0, 1, 2, 3, 4]
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
     * Submit review answers (for mobile app)
     * Uses Sanctum token authentication instead of session auth
     */
    public function submitAnswers(Request $request, $courseId)
    {
        // Support both session-based auth (for website) and token-based auth (for mobile app)
        $bearerToken = $request->bearerToken();
        $user = null;
        
        // First, try session-based authentication (for website)
        if (Auth::check()) {
            $user = Auth::user();
        }
        // If no session, try token-based authentication (for mobile app)
        else if ($bearerToken) {
            try {
                $tokenModel = PersonalAccessToken::findToken($bearerToken);
                if ($tokenModel) {
                    $user = $tokenModel->tokenable;
                }
            } catch (\Exception $e) {
                \Log::error('Review Submit API: Error validating token', [
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:review_questions,id',
            'answers.*.selected_options' => 'required|array',
            'answers.*.selected_options.*' => 'integer|in:0,1,2'
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
     * Check if user has completed review for a course (for mobile app)
     * Uses Sanctum token authentication instead of session auth
     */
    public function checkCompletion(Request $request, $courseId)
    {
        // Support both session-based auth (for website) and token-based auth (for mobile app)
        $bearerToken = $request->bearerToken();
        $user = null;
        
        // First, try session-based authentication (for website)
        if (Auth::check()) {
            $user = Auth::user();
        }
        // If no session, try token-based authentication (for mobile app)
        else if ($bearerToken) {
            try {
                $tokenModel = PersonalAccessToken::findToken($bearerToken);
                if ($tokenModel) {
                    $user = $tokenModel->tokenable;
                }
            } catch (\Exception $e) {
                \Log::error('Review Check API: Error validating token', [
                    'error' => $e->getMessage()
                ]);
            }
        }
        
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

