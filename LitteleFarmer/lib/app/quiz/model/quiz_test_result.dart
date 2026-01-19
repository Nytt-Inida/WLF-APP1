import 'package:little_farmer/utils/constant.dart';

class QuizTestResult {
  final String message;
  final int totalQuestions;
  final int score;
  final int correctAnswers;
  final QuizResults results;

  QuizTestResult({
    required this.message,
    required this.totalQuestions,
    required this.score,
    required this.correctAnswers,
    required this.results,
  });

  factory QuizTestResult.fromJson(Map<String, dynamic> json) {
    return QuizTestResult(
      message: json[Constant.message],
      totalQuestions: json[Constant.totalQuestions],
      score: json[Constant.score],
      correctAnswers: json[Constant.correctAnswers],
      results: QuizResults.fromJson(json[Constant.results]),
    );
  }
}

class QuizResults {
  final int userId;
  final String quizTitle;
  final List<QuizQuestion> correctQuestion;
  final List<QuizQuestion> incorrectQuestion;
  final List<QuizQuestion> skippedQuestion;

  QuizResults({
    required this.userId,
    required this.quizTitle,
    required this.correctQuestion,
    required this.incorrectQuestion,
    required this.skippedQuestion,
  });

  factory QuizResults.fromJson(Map<String, dynamic> json) {
    return QuizResults(
      userId: json[Constant.userId],
      quizTitle: json[Constant.quizTitle],
      correctQuestion: (json[Constant.correctQuestion] as List).map((i) => QuizQuestion.fromJson(i)).toList(),
      incorrectQuestion: (json[Constant.incorrectQuestion] as List).map((i) => QuizQuestion.fromJson(i)).toList(),
      skippedQuestion: (json[Constant.skippedQuestion] as List).map((i) => QuizQuestion.fromJson(i)).toList(),
    );
  }
}

class QuizQuestion {
  final String question;

  QuizQuestion({
    required this.question,
  });

  factory QuizQuestion.fromJson(Map<String, dynamic> json) {
    return QuizQuestion(
      question: json[Constant.question],
    );
  }
}
