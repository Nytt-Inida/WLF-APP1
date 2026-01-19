import 'package:little_farmer/utils/common_string.dart';

class AnswerList {
  String question;
  int selectedAnswer;
  int questionId;
  String answerType = CommonString.skipped;

  AnswerList({required this.question, required this.selectedAnswer, required this.questionId, required this.answerType});
}
