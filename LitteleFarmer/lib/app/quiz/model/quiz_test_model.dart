import 'package:little_farmer/utils/constant.dart';

class QuizTestModel {
  final int code;
  final String message;
  final List<QuizData> data;

  QuizTestModel({
    required this.code,
    required this.message,
    required this.data,
  });

  factory QuizTestModel.fromJson(Map<String, dynamic> json) {
    return QuizTestModel(
      code: json[Constant.code],
      message: json[Constant.message],
      data: (json[Constant.data] as List).map((i) => QuizData.fromJson(i)).toList(),
    );
  }
}

class QuizData {
  final int id;
  final int courseId;
  final String title;
  final List<Question> questions;

  QuizData({
    required this.id,
    required this.courseId,
    required this.title,
    required this.questions,
  });

  factory QuizData.fromJson(Map<String, dynamic> json) {
    return QuizData(
      id: json[Constant.id],
      courseId: json[Constant.courseId],
      title: json[Constant.title],
      questions: (json[Constant.questions] as List).map((i) => Question.fromJson(i)).toList(),
    );
  }
}

class Question {
  final int id;
  final int testId;
  final int sectionId;
  final String sectionTitle;
  final String question;
  final String quizTitle;
  final String option1;
  final String option2;
  final String option3;
  final String option4;
  final String correctOption;

  Question({
    required this.id,
    required this.testId,
    required this.sectionId,
    required this.sectionTitle,
    required this.question,
    required this.quizTitle,
    required this.option1,
    required this.option2,
    required this.option3,
    required this.option4,
    required this.correctOption,
  });

  factory Question.fromJson(Map<String, dynamic> json) {
    return Question(
      id: json[Constant.id],
      testId: json[Constant.testId],
      sectionId: json[Constant.sectionId],
      sectionTitle: json[Constant.sectionTitle],
      question: json[Constant.question],
      quizTitle: json[Constant.quizTitle],
      option1: json[Constant.option1],
      option2: json[Constant.option2],
      option3: json[Constant.option3],
      option4: json[Constant.option4],
      correctOption: json[Constant.correctOption],
    );
  }
}
