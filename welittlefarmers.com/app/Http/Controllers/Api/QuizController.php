<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Result; 
use App\Models\Question;
use App\Models\QuizProgress;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function getTestsForCourse(Request $request)
{
    $validateUser = Validator::make($request->all(),[
        'course_id' => 'required|exists:courses,id',
        'quiz_title' => 'nullable|string', // Optional quiz title parameter
        'quiz_id' => 'nullable|exists:tests,id', // Optional quiz ID parameter
    ]);
    
    if ($validateUser->fails()) {
        return response()->json([
            'code' => 403,
            'status' => false,
            'message' => $validateUser->errors()
        ], 403);
    }

    $course_id = $request->input('course_id');
    $quiz_title = $request->input('quiz_title');
    $quiz_id = $request->input('quiz_id');

    // Fetch the tests with their questions and sections
    $query = Test::where('course_id', $course_id);

    if ($quiz_title) {
        $query->whereHas('questions', function ($query) use ($quiz_title) {
            $query->where('quiz_title', $quiz_title);
        });
    }

    if ($quiz_id) {
        $query->where('id', $quiz_id);
    }

    $tests = $query->with(['questions' => function($query) {
        $query->with('section');
    }])->get();

    if ($tests->isEmpty()) {
        return response()->json(['code' => 404,'message' => 'No tests found for this course'], 404);
    }

    // Format the response to include section details
    $response = [
        'code' => 200,
        'message' => 'Tests retrieved successfully',
        'data' => $tests->map(function ($test) {
            return [
                'id' => $test->id,
                'course_id' => $test->course_id,
                'title' => $test->title,
                'questions' => $test->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'test_id' => $question->test_id,
                        'section_id' => $question->section_id,
                        'section_title' => $question->section ? $question->section->title : "",
                        'question' => $question->question,
                        'quiz_title' => $question->quiz_title, // Include quiz_title from the questions table
                        'option1' => $question->option1,
                        'option2' => $question->option2,
                        'option3' => $question->option3,
                        'option4' => $question->option4,
                        'correct_option' => $question->correct_option,
                    ];
                }),
            ];
        })
    ];

    return response()->json($response, 200);
}

public function Test(Request $request)

{
    $validateUser = Validator::make($request->all(), [
        // 'user_id' => 'required|exists:users,id',
        'course_id' => 'required|exists:courses,id',
        'quiz_title' => 'required|string',
        'correct_question' => 'nullable|array',
        'incorrect_question' => 'nullable|array',
        'skipped_question' => 'nullable|array',
    ]);

    if ($validateUser->fails()) {
        return response()->json([
            'code' => 403,
            'status' => false,
            'message' => $validateUser->errors()
        ], 403);
    }

    $user_id = auth()->id();
    if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
    // $user_id = $request->input('user_id');
    $course_id = $request->input('course_id');
    $quiz_title = $request->input('quiz_title');
    $correct_question = $request->input('correct_question', []);
    $incorrect_question = $request->input('incorrect_question', []);
    $skipped_question = $request->input('skipped_question', []);

    // Assuming we process and store the quiz results here
    // This part can involve storing the results in a database, logging, etc.

    // For this example, we'll return the processed results
    $response = [
        'code' => 200,
        'message' => 'Quiz results processed successfully',
        'data' => [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'quiz_title' => $quiz_title,
            'correct_question' => array_map(function($question) {
                return [
                    'question' => $question['question'],
                    'status' => 'correct'
                ];
            }, $correct_question),
            'incorrect_question' => array_map(function($question) {
                return [
                    'question' => $question['question'],
                    'status' => 'incorrect'
                ];
            }, $incorrect_question),
            'skipped_question' => array_map(function($question) {
                return [
                    'question' => $question['question'],
                    'status' => 'skipped'
                ];
            }, $skipped_question),
        ]
    ];

    return response()->json($response, 200);
}
public function submitTest(Request $request)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'test_id' => 'required|exists:tests,id',
        // 'user_id' => 'required|exists:users,id',
        'answers' => 'required|array',
        'answers.*.question_id' => 'required|exists:questions,id',
        'answers.*.selected_option' => 'required|integer|min:0|max:4', // Allow null for skipped questions
    ]);

    // Handle validation failure
    if ($validator->fails()) {
        return response()->json([
            'code' => 403,
            'status' => false,
            'message' => $validator->errors()
        ], 403);
    }

    $test_id = $request->input('test_id');
    $user_id = auth()->id();
    if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
    // $user_id = $request->input('user_id');
    $test = Test::findOrFail($test_id);
    $course_id = $test->course_id;

    // Delete previous quiz progress for this user and test
    QuizProgress::where('user_id', $user_id)
        ->whereHas('question.test', function($query) use ($test_id) {
            $query->where('id', $test_id);
        })
        ->delete();

    $totalQuestions = $test->questions()->count();
    $correctAnswers = 0;

    $responseData = [];

    // Process each answer
    foreach ($request->answers as $index => $answer) {
        $question = Question::find($answer['question_id']);

        // Determine if the question is correct, incorrect, or skipped
        if (is_null($answer['selected_option']) || $answer['selected_option'] === 0) {
            $skipped = true;
            $isCorrect = 2; // Use 2 to represent skipped questions
        } else {
            $skipped = false;
            $isCorrect = $question && $question->correct_option == $answer['selected_option'] ? 1 : 0; // 1 for correct, 0 for incorrect
        }

        // Save the new quiz progress
        QuizProgress::updateOrCreate(
            ['user_id' => $user_id, 'question_id' => $question->id],
            ['is_correct' => $isCorrect]
        );

        if ($isCorrect === 1) {
            $correctAnswers++;
        }

        $responseData[] = [
            'question_number' => $index + 1,
            'question_text' => $question->question,
            'selected_option' => $answer['selected_option'],
            'is_correct' => $isCorrect === 1,
            'skipped' => $isCorrect === 2,
        ];
    }

    // Calculate overall correct answers percentage for the course
    $totalQuestionsInCourse = Question::whereHas('test', function($query) use ($course_id) {
        $query->where('course_id', $course_id);
    })->count();

    $correctAnswersInCourse = QuizProgress::where('user_id', $user_id)
        ->whereHas('question.test', function($query) use ($course_id) {
            $query->where('course_id', $course_id);
        })
        ->where('is_correct', 1) // Count only correct answers
        ->count();

    $coursePercentage = ($correctAnswersInCourse / $totalQuestionsInCourse) * 100;

    // Save the final result
    $score = ($correctAnswers / $totalQuestions) * 100;

    Result::create([
        'user_id' => $user_id,
        'test_id' => $test_id,
        'score' => $score,
        'total_questions' => $totalQuestions,
        'correct_answers' => $correctAnswers,
    ]);

    // Categorize questions into correct, incorrect, and skipped
    $correctQuestions = [];
    $incorrectQuestions = [];
    $skippedQuestions = [];

    foreach ($responseData as $data) {
        if ($data['skipped']) {
            $skippedQuestions[] = ['question' => $data['question_text']];
        } elseif ($data['is_correct']) {
            $correctQuestions[] = ['question' => $data['question_text']];
        } else {
            $incorrectQuestions[] = ['question' => $data['question_text']];
        }
    }

    // Return the response
    return response()->json([
        'message' => 'Test submitted successfully',
        'total_questions' => $totalQuestions,
        'course_percentage' => $coursePercentage,
        'results' => [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'quiz_title' => $test->title,
            'correct_question' => $correctQuestions,
            'incorrect_question' => $incorrectQuestions,
            'skipped_question' => $skippedQuestions,
        ]
    ], 200);
}


 public function uploadResultAfterLastQuestion(Request $request)
    {
        $request->validate([
            // 'user_id' => 'required|exists:users,id',
            'test_id' => 'required|exists:tests,id',  // This is the quiz ID
            'question_id' => 'required|exists:questions,id',  // The current question being answered
            'score' => 'required|integer',
            'total_questions' => 'required|integer',
            'correct_answers' => 'required|integer',
        ]);

        $test_id = $request->input('test_id');
        $question_id = $request->input('question_id');
        
        $user_id = auth()->id();
        if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
        // $user_id = $request->input('user_id');

        // Find the last question in the quiz
        $last_question = Question::where('test_id', $test_id)->orderBy('id', 'desc')->first();

        if ($last_question && $last_question->id == $question_id) {
            // If the current question is the last question, upload the result
            $result = Result::create([
                'user_id' => $user_id,
                'test_id' => $test_id,
                'score' => $request->input('score'),
                'total_questions' => $request->input('total_questions'),
                'correct_answers' => $request->input('correct_answers'),
            ]);

            // Update QuizProgress to mark the quiz as complete
            QuizProgress::updateOrCreate(
                ['user_id' => $user_id, 'question_id' => $question_id],
                ['is_correct' => true]  // Assuming that completion means the last question is answered correctly
            );

            return response()->json([
                'message' => 'Result uploaded and progress marked as complete.',
                'result' => $result
            ], 201);
        } else {
            // If not the last question, update progress but don't store the result
            QuizProgress::updateOrCreate(
                ['user_id' => $user_id, 'question_id' => $question_id],
                ['is_correct' => $request->input('is_correct', false)]
            );

            return response()->json([
                'message' => 'Progress updated, but result not uploaded as this is not the last question.',
            ], 200);
        }
    }
 public function viewQuizResults(Request $request)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'course_id' => 'required|exists:courses,id',
        'quiz_title' => 'required|string',
        // 'user_id' => 'required|exists:users,id',
    ]);

    // Handle validation failure
    if ($validator->fails()) {
        return response()->json([
            'code' => 403,
            'status' => false,
            'message' => $validator->errors()
        ], 403);
    }

    $course_id = $request->input('course_id');
    $quiz_title = $request->input('quiz_title');
    
    $user_id = auth()->id();
    if (!$user_id) return response()->json(['error' => 'Unauthenticated'], 401);
    // $user_id = $request->input('user_id');

    // Fetch the test ID based on the quiz title in the question table and course ID
    $test = Test::whereHas('questions', function ($query) use ($quiz_title) {
                    $query->where('quiz_title', $quiz_title);
                })
                ->where('course_id', $course_id)
                ->first();

    if (!$test) {
        return response()->json([
            'code' => 404,
            'status' => false,
            'message' => 'No test found with this title in the specified course.'
        ], 404);
    }

    // Fetch the result entry for the user and test
    $result = Result::where('user_id', $user_id)
                    ->where('test_id', $test->id)
                    ->first();

    if (!$result) {
        return response()->json([
            'code' => 404,
            'status' => false,
            'message' => 'No results found for this quiz.'
        ], 404);
    }

    // Fetch the quiz progress for the specific test and user
    $quizProgress = QuizProgress::where('user_id', $user_id)
        ->whereHas('question.test', function ($query) use ($test) {
            $query->where('id', $test->id);
        })
        ->get();

    $correctQuestions = [];
    $incorrectQuestions = [];
    $skippedQuestions = [];

    foreach ($quizProgress as $progress) {
        $question = $progress->question;
        if ($progress->is_correct == 2) { // 2 represents skipped questions
            $skippedQuestions[] = ['question' => $question->question];
        } elseif ($progress->is_correct == 1) {
            $correctQuestions[] = ['question' => $question->question];
        } else {
            $incorrectQuestions[] = ['question' => $question->question];
        }
    }

    return response()->json([
        'message' => 'Quiz results retrieved successfully',
        'total_questions' => $result->total_questions,
        'score' => $result->score,
        'correct_answers' => $result->correct_answers,
        'results' => [
            'user_id' => $user_id,
            'quiz_title' => $quiz_title, // Returned as input by user
            'course_id' => $course_id,
            'correct_question' => $correctQuestions,
            'incorrect_question' => $incorrectQuestions,
            'skipped_question' => $skippedQuestions, // Include skipped questions in the response
        ]
    ], 200);
}


}