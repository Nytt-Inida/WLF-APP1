<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Question;
use App\Models\QuizProgress; // Import the QuizProgress model
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class QuizQuestionController extends Controller
{
    public function fetchQuizQuestions(Request $request)
    {
        $sectionId = $request->input('section_id');
        $courseId = $request->input('course_id');

        $questions = Question::where('section_id', $sectionId)
            ->where('course_id', $courseId)
            ->get();

        if ($questions->isEmpty()) {
            return response()->json(['message' => 'No quiz questions found.'], 404);
        }

        return response()->json($questions);
    }



    // Method to submit quiz answers and evaluate them
    public function submitQuiz(Request $request)
    {
        $answers = $request->input('answers');
        $userId = auth()->user()->id;
        $results = [];

        foreach ($answers as $questionId => $selectedOption) {
            $question = Question::find($questionId);
            $isCorrect = ($question->correct_option == $selectedOption) ? 1 : 0;

            // Store the result in the quiz_progress table
            QuizProgress::create([
                'user_id' => $userId,
                'question_id' => $questionId,
                'is_correct' => $isCorrect,
            ]);

            // Add the result to the response array
            $results[] = [
                'question_id' => $questionId,
                'is_correct' => $isCorrect,
            ];
        }

        return response()->json(['results' => $results]);
    }

    public function saveProgress(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'question_id' => 'required|exists:questions,id',
            'is_correct' => 'required|boolean',
            'selected_option' => 'required|integer|min:1|max:4', // Add this validation
        ]);


        $quizProgress = QuizProgress::updateOrCreate(
            [
                'user_id' => auth()->id(), // Fixed IDOR
                'question_id' => $validated['question_id']
            ],
            [
                'is_correct' => $validated['is_correct'],
                'selected_option' => $validated['selected_option'] // Add this
            ]
        );

        return response()->json([
            'success' => true,
            'progress' => $quizProgress
        ]);
    }

    public function fetchPreviewsAnswers(Request $request)
    {

        $request->validate([
            'user_id' => 'required|integer',
            'section_id' => 'required|integer',
        ]);

        $userId = auth()->id();
        if (!$userId) return response()->json(['error' => 'Unauthenticated'], 401);
        // $userId = $request->user_id;
        $sectionId = $request->section_id;

        // Get all question IDs for the given section
        $questionIds = Question::whereHas('test', function ($query) use ($sectionId) {
            $query->where('section_id', $sectionId);
        })->pluck('id');

        // Fetch the user's previous answers for these questions
        $previousAnswers = QuizProgress::whereIn('Question_id', $questionIds)
            ->where('user_id', $userId)
            ->select('question_id', 'selected_option', 'is_correct')
            ->get()
            ->toArray();

        return response()->json($previousAnswers);
    }

    public function getProgress(Request $request)
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'error' => 'Unauthorized access'
            ], 401); // Unauthorized status
        }

        // Get the authenticated user's ID
        $userId = auth()->id();

        // Get the section ID from the request
        $sectionId = $request->input('section_id');

        // Fetch the quiz completion status from the `quiz_progress` table by joining with `questions`
        $quizCompleted = DB::table('quiz_progress')
            ->join('questions', 'quiz_progress.question_id', '=', 'questions.id')
            ->where('quiz_progress.user_id', $userId)
            ->where('questions.section_id', $sectionId)
            ->exists();

        return response()->json([
            'quiz_completed' => $quizCompleted
        ]);
    }
    public function getCompletedArticles(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) return response()->json(['error' => 'Unauthenticated'], 401);
        // $userId = $request->input('user_id');
        $sectionId = $request->input('section_id');

        // Fetch completed articles for the user and section from the database
        $completedArticles = DB::table('article_progress')
            ->join('articles', 'article_progress.article_id', '=', 'articles.id')
            ->where('article_progress.user_id', $userId)
            ->where('articles.section_id', $sectionId)
            ->pluck('articles.id');

        return response()->json(['completed_articles' => $completedArticles]);
    }
}
