import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:little_farmer/app/quiz/model/answer_list.dart';
import 'package:little_farmer/app/quiz/model/quiz_test_result.dart';
import 'package:little_farmer/app/quiz/model/selected_question_answer_model.dart';
import 'package:little_farmer/app/quiz/model/quiz_test_model.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';

class QuizProvider extends ChangeNotifier {
  double sliderValue = 0.0;
  int currentQuestion = 1;
  bool isFirstAnswerCorrect = false;
  bool isSecondAnswerCorrect = false;
  bool isThirdAnswerCorrect = false;
  bool isFourthAnswerCorrect = false;
  bool isFirstAnswerChoose = false;
  bool isSecondAnswerChoose = false;
  bool isThirdAnswerChoose = false;
  bool isFourthAnswerChoose = false;
  bool isFetchQuizApiCalling = false;
  List<Question> questionList = [];
  List<AnswerList> answerList = [];
  List<AnswerList> questionResultList = [];
  List<SelectedQuestionAnswerModel> selectedAnswerList = [];
  int correctAnswer = 0;
  int inCorrectAnswer = 0;
  int skippedAnswer = 0;
  int testId = 0;
  bool isQuizAlreadyCompleted = false;

  bool _isAnswerSelected = false;
  bool get isAnswerSelected => _isAnswerSelected;
  set isAnswerSelected(bool value) {
    _isAnswerSelected = value;
    notifyListeners();
  }

  bool _isQuizComplete = false;
  bool get isQuizComplete => _isQuizComplete;
  set isQuizComplete(bool value) {
    _isQuizComplete = value;
    notifyListeners();
  }

  bool _isViewResult = false;
  bool get isViewResult => _isViewResult;
  set isViewResult(bool value) {
    _isViewResult = value;
    notifyListeners();
  }

  int? tempSelectedOption; // 1, 2, 3, or null
  bool isAnswerConfirmed = false;

  Future<void> selectOption(int optionIndex) async {
    if (isAnswerConfirmed) return; // Prevent changing after confirmation
    
    // Toggle if same option selected, or select new one
    tempSelectedOption = optionIndex;
    isAnswerSelected = true;
    
    // Reset individual flags for UI (though we should move away from these specific flags)
    isFirstAnswerChoose = (optionIndex == 1);
    isSecondAnswerChoose = (optionIndex == 2);
    isThirdAnswerChoose = (optionIndex == 3);
    isFourthAnswerChoose = (optionIndex == 4);
    notifyListeners();
  }

  Future<void> confirmAnswer() async {
    if (tempSelectedOption == null) {
      // Treat as skipped if nothing selected? Or prevent confirming?
      // Current logic adds as skipped if !isAnswerSelected
      return;
    }
    
    isAnswerConfirmed = true;
    
    // Validate answer
    String correctOptionStr = questionList[currentQuestion - 1].correctOption;
    int correctOption = int.tryParse(correctOptionStr) ?? 0;
    
    bool isCorrect = (tempSelectedOption == correctOption);
    
    // Update legacy flags (if needed for result screen, but we should use answerList)
    isFirstAnswerCorrect = (correctOption == 1);
    isSecondAnswerCorrect = (correctOption == 2);
    isThirdAnswerCorrect = (correctOption == 3);
    isFourthAnswerCorrect = (correctOption == 4);

    // Add to answer list
    answerList.add(AnswerList(
      question: questionList[currentQuestion - 1].question,
      selectedAnswer: tempSelectedOption!,
      questionId: questionList[currentQuestion - 1].id,
      answerType: isCorrect ? CommonString.correct : CommonString.incorrect
    ));
    
    // Check if quiz complete
    if (questionList.length == currentQuestion) {
       double sliderValues = (100.0 / questionList.length.toDouble()) * (currentQuestion.toDouble());
       sliderValue = sliderValues;
       isQuizComplete = true;
    }
    
    notifyListeners();
  }

  Future<void> resetQuestion() async {
    isFirstAnswerCorrect = false;
    isSecondAnswerCorrect = false;
    isThirdAnswerCorrect = false;
    isFirstAnswerChoose = false;
    isSecondAnswerChoose = false;
    isThirdAnswerChoose = false;
    isFourthAnswerChoose = false;
    isFourthAnswerCorrect = false;
    isFetchQuizApiCalling = false;
    isAnswerSelected = false;
    
    // New fields
    tempSelectedOption = null;
    isAnswerConfirmed = false;
    notifyListeners();
  }

  Future<void> resetProvider() async {
    isFirstAnswerCorrect = false;
    isSecondAnswerCorrect = false;
    isThirdAnswerCorrect = false;
    isFourthAnswerCorrect = false;
    isFirstAnswerChoose = false;
    isSecondAnswerChoose = false;
    isThirdAnswerChoose = false;
    isFourthAnswerChoose = false;
    _isAnswerSelected = false;
    _isQuizComplete = false;
    _isViewResult = false;
    sliderValue = 0.0;
    currentQuestion = 1;
    answerList.clear();
    correctAnswer = 0;
    inCorrectAnswer = 0;
    skippedAnswer = 0;
    selectedAnswerList = [];
    questionResultList = [];
    isQuizAlreadyCompleted = false;
    
    tempSelectedOption = null;
    isAnswerConfirmed = false;
    notifyListeners();
  }

  Future<void> separateOutAnswer() async {
    for (var i = 0; i < answerList.length; i++) {
      selectedAnswerList.add(SelectedQuestionAnswerModel(questionId: answerList[i].questionId, selectedOption: answerList[i].selectedAnswer));
      if (answerList[i].answerType == CommonString.correct) {
        correctAnswer++;
      } else if (answerList[i].answerType == CommonString.incorrect) {
        inCorrectAnswer++;
      } else if (answerList[i].answerType == CommonString.skipped) {
        skippedAnswer++;
      }
    }
  }

  Future<void> fetchQuiz({required String title, required int courseId}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isFetchQuizApiCalling = true;
        questionList.clear();
        notifyListeners();

        var response = await ApiResponse().onFetchQuizTest(quizTitle: title, courseId: courseId);
        isFetchQuizApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          QuizTestModel quizTestModel = QuizTestModel.fromJson(jsonMap);
          questionList = quizTestModel.data[0].questions;
          testId = quizTestModel.data[0].id;
          notifyListeners();
        } else {
          questionList = [];
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
      } catch (e) {
        isFetchQuizApiCalling = false;
        questionList.clear();
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isFetchQuizApiCalling = false;
      questionList.clear();
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  Future<void> submitQuiz() async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isFetchQuizApiCalling = true;
        isViewResult = false;
        notifyListeners();

        var response = await ApiResponse().onSubmitQuizTest(testId: testId, selectedAnswerList: selectedAnswerList);
        isFetchQuizApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          isViewResult = true;
          isQuizComplete = true;
          notifyListeners();
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
      } catch (e) {
        isFetchQuizApiCalling = false;
        questionList.clear();
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isFetchQuizApiCalling = false;
      questionList.clear();
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  Future<void> fetchQuizResult({required String quizTitle, required int courseId}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isFetchQuizApiCalling = true;
        questionResultList.clear();
        notifyListeners();

        var response = await ApiResponse().onFetchQuizTestResult(quizTitle: quizTitle, courseId: courseId);
        isFetchQuizApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          QuizTestResult quizTestResult = QuizTestResult.fromJson(decoded);
          correctAnswer = quizTestResult.results.correctQuestion.length;
          for (var i = 0; i < quizTestResult.results.correctQuestion.length; i++) {
            questionResultList.add(AnswerList(question: quizTestResult.results.correctQuestion[i].question, selectedAnswer: 0, questionId: 0, answerType: CommonString.correct));
          }
          inCorrectAnswer = quizTestResult.results.incorrectQuestion.length;
          for (var i = 0; i < quizTestResult.results.incorrectQuestion.length; i++) {
            questionResultList.add(AnswerList(question: quizTestResult.results.incorrectQuestion[i].question, selectedAnswer: 0, questionId: 0, answerType: CommonString.incorrect));
          }
          skippedAnswer = quizTestResult.results.skippedQuestion.length;
          for (var i = 0; i < quizTestResult.results.skippedQuestion.length; i++) {
            questionResultList.add(AnswerList(question: quizTestResult.results.skippedQuestion[i].question, selectedAnswer: 0, questionId: 0, answerType: CommonString.skipped));
          }
          notifyListeners();
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
      } catch (e) {
        isFetchQuizApiCalling = false;
        questionResultList.clear();
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isFetchQuizApiCalling = false;
      questionResultList.clear();
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }
}
