import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/app/quiz/model/selected_question_answer_model.dart';
import 'package:little_farmer/network/apis.dart';
import 'package:little_farmer/utils/SharedPreferencesUtil.dart';
import 'package:little_farmer/utils/constant.dart';

class ApiResponse {
  Future<http.Response> onSendOtp({required String email}) async {
    try {
      debugPrint("OTP URl : ${Apis.sendOtpUrl}");
      return await http.post(
        Uri.parse(Apis.sendOtpUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json'},
        body: jsonEncode({Constant.email: email}),
      );
    } catch (e) {
      debugPrint("Error in onSendOtp: $e");
      throw Exception(CommonString.something_went_wrong);
    }
  }

  Future<http.Response> onLogin({required String email, required String otp}) async {
    http.Response? response;
    try {
      response = await http.post(
        Uri.parse(Apis.loginUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json'},
        body: jsonEncode({Constant.email: email, Constant.otp: otp}),
      );
    } catch (e) {
      debugPrint("Error in onLogin: $e");
      response = null;
      throw Exception(CommonString.something_went_wrong);
    }

    return response;
  }

  Future<http.Response> onRegistration(
      {required String userName, required String email, required String schoolName, required String country, required String age, required String referralCode}) async {
    http.Response? response;
    try {
      response = await http.post(
        Uri.parse(Apis.registrationUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json'},
        body: jsonEncode(
            {Constant.name: userName, Constant.email: email, Constant.schoolName: schoolName, Constant.country: country, Constant.age: age, Constant.referralCode: referralCode}),
      );
    } catch (e) {
      debugPrint("Error in onRegistration: $e");
      response = null;
      throw Exception(CommonString.something_went_wrong);
    }

    return response;
  }

  Future<http.Response> onFetchCourseByAge({required String age}) async {
    http.Response? response;
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.fetchCourseByAgeUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.userId: userId, Constant.ageGroup: age}),
      );
    } catch (e) {
      debugPrint("Error in onFetchCourseByAge: $e");
      response = null;
      throw Exception(CommonString.something_went_wrong);
    }

    return response;
  }

  Future<http.Response> onFetchPopularCourse() async {
    http.Response? response;
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.fetchPopularCourseUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.userId: userId}),
      );
    } catch (e) {
      debugPrint("Error in onFetchPopularCourse: $e");
      response = null;
      throw Exception(CommonString.something_went_wrong);
    }

    return response;
  }

  Future<http.Response> onAddCourseInFavorite({required int courseId, required bool isFavorite}) async {
    http.Response? response;
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.addCourseInFavoriteUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.userId: userId, Constant.courseId: courseId, Constant.isFavorite: isFavorite}),
      );
    } catch (e) {
      debugPrint("Error in onAddCourseInFavorite: $e");
      response = null;
      throw Exception(CommonString.something_went_wrong);
    }

    return response;
  }

  Future<http.Response> onFetchFavoriteCourse() async {
    http.Response? response;
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.fetchFavoriteCourseUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.userId: userId}),
      );
    } catch (e) {
      debugPrint("Error in onFetchFavoriteCourse: $e");
      response = null;
      throw Exception(CommonString.something_went_wrong);
    }

    return response;
  }

  Future<http.Response> onFetchPurchaseCourse() async {
    http.Response? response;
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.fetchPurchasedCourseUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.userId: userId}),
      );
    } catch (e) {
      debugPrint("Error in onFetchPurchaseCourse: $e");
      response = null;
      throw Exception(CommonString.something_went_wrong);
    }

    return response;
  }

  Future<http.Response> onFetchCourseDetail({required int courseId}) async {
    http.Response? response;
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      // user_id is optional - if 0, don't send it (allows guest access)
      Map<String, dynamic> body = {Constant.courseId: courseId};
      if (userId > 0) {
        body[Constant.userId] = userId;
      }
      
      Map<String, String> headers = {
        Constant.content_type: Constant.application_json,
        'Accept': 'application/json'
      };
      
      // Only add authorization header if user is logged in
      if (token.isNotEmpty) {
        headers[Constant.authorization] = '${Constant.bearer} $token';
      }
      
      response = await http.post(
        Uri.parse(Apis.fetchCourseDetailUrl),
        headers: headers,
        body: jsonEncode(body),
      );
    } catch (e) {
      debugPrint("Error in onFetchCourseDetail: $e");
      response = null;
      throw Exception(CommonString.something_went_wrong);
    }

    return response;
  }

  Future<http.Response> onCourseVerify({required int courseId}) async {
    http.Response? response;
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.courseVerifyUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.userId: userId, Constant.courseId: courseId}),
      );
    } catch (e) {
      debugPrint("Error in onCourseVerify: $e");
      response = null;
      throw Exception(CommonString.something_went_wrong);
    }

    return response;
  }

  Future<http.Response> onProcessPayment({required int courseId, required String cardNumber, required String holderName, required String expiryDate, required String cvc}) async {
    http.Response? response;
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.processPaymentUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({
          Constant.userId: userId,
          Constant.courseId: courseId,
          Constant.cardNumber: cardNumber,
          Constant.holderName: holderName,
          Constant.expiryDate: expiryDate,
          Constant.cvc: cvc
        }),
      );
    } catch (e) {
      response = null;
      throw Exception("Process to payment fail");
    }

    return response;
  }

  Future<http.Response> onGetProfile({required int userId}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.get(
        Uri.parse('${Apis.baseURL}profile?user_id=$userId'),
        headers: {
          'Accept': 'application/json',
          Constant.authorization: '${Constant.bearer} $token'
        },
      );
    } catch (e) {
      response = null;
      throw Exception("Get profile fail");
    }

    return response;
  }

  Future<http.Response?> onLogout() async {
    http.Response? response;
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      // Make logout API call - don't require auth, allow it to fail gracefully
      Map<String, String> headers = {
        Constant.content_type: Constant.application_json,
        'Accept': 'application/json'
      };
      
      // Only add authorization if token exists (but don't require it)
      if (token.isNotEmpty) {
        headers[Constant.authorization] = '${Constant.bearer} $token';
      }
      
      Map<String, dynamic> body = {};
      if (userId > 0) {
        body[Constant.userId] = userId;
      }
      
      response = await http.post(
        Uri.parse(Apis.logoutUrl),
        headers: headers,
        body: jsonEncode(body),
      ).timeout(const Duration(seconds: 10));
    } catch (e) {
      // Don't throw - allow local logout to proceed even if API fails
      print("Logout API call failed (continuing with local logout): $e");
      response = null;
    }

    return response;
  }

  Future<http.Response> onFetchPurchaseCourseDetail({required int courseId}) async {
    http.Response? response;
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      // user_id is optional - if 0, don't send it (allows guest access)
      Map<String, dynamic> body = {Constant.courseId: courseId};
      if (userId > 0) {
        body[Constant.userId] = userId;
      }
      
      Map<String, String> headers = {
        Constant.content_type: Constant.application_json,
        'Accept': 'application/json'
      };
      
      // Only add authorization header if user is logged in
      if (token.isNotEmpty) {
        headers[Constant.authorization] = '${Constant.bearer} $token';
      }
      
      response = await http.post(
        Uri.parse(Apis.fetchPurchasedCourseDetailUrl),
        headers: headers,
        body: jsonEncode(body),
      );
    } catch (e) {
      response = null;
      throw Exception("Fetch purchase course detail fail");
    }

    return response;
  }

  Future<http.Response> onFetchSearchCourse({required String search}) async {
    http.Response? response;
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.get(
        Uri.parse(Apis.fetchSearchCourseUrl).replace(queryParameters: {Constant.userId: userId.toString(), Constant.keyword: search}),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
      );
    } catch (e) {
      response = null;
      throw Exception("Fetch search course fail");
    }

    return response;
  }

  Future<http.Response> onFetchQuizTest({required String quizTitle, required int courseId}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.fetchQuizTestUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.courseId: courseId, Constant.quizTitle: quizTitle}),
      );
    } catch (e) {
      response = null;
      throw Exception("Fetch quiz test fail");
    }

    return response;
  }

  Future<http.Response> onSubmitQuizTest({required int testId, required List<SelectedQuestionAnswerModel> selectedAnswerList}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);

    List<Map<String, int>> answersList = [];
    for (var i = 0; i < selectedAnswerList.length; i++) {
      answersList.add({Constant.questionId: selectedAnswerList[i].questionId, Constant.selectedOption: selectedAnswerList[i].selectedOption});
    }
    Map<String, dynamic> requestBody = {Constant.testId: testId, Constant.userId: userId, Constant.answers: answersList};

    String jsonBody = json.encode(requestBody);

    try {
      response = await http.post(
        Uri.parse(Apis.submitQuizTestUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonBody,
      );
    } catch (e) {
      response = null;
      throw Exception("Submit quiz test fail");
    }

    return response;
  }

  Future<http.Response> onFetchQuizTestResult({required String quizTitle, required int courseId}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    try {
      response = await http.post(
        Uri.parse(Apis.fetchQuizTestResultUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.userId: userId, Constant.quizTitle: quizTitle, Constant.courseId: courseId}),
      );
    } catch (e) {
      response = null;
      throw Exception("Fetch quiz test fail");
    }

    return response;
  }

  Future<http.Response> onCompleteLesson({required int lessonId, required bool isVideoType}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String item_type = Constant.lessonId;
    if (isVideoType) {
      item_type = Constant.lessonId;
    } else {
      item_type = Constant.articleId;
    }
    try {
      response = await http.post(
        Uri.parse(Apis.completeLessonUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.userId: userId, item_type: lessonId}),
      );
    } catch (e) {
      response = null;
      throw Exception("Complete lesson fail");
    }

    return response;
  }

  Future<http.Response> onFetchCompleteCourseCertificate() async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    try {
      response = await http.post(
        Uri.parse(Apis.fetchCompleteCourseCertificateUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.userId: userId}),
      );
    } catch (e) {
      response = null;
      throw Exception("Fetch certificate fail");
    }

    return response;
  }

  Future<http.Response> onUpdateProfile({required String userName, required String email, required String schoolName, required String country, required String age}) async {
    http.Response? response;
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.updateProfileUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.userId: userId, Constant.name: userName, Constant.email: email, Constant.schoolName: schoolName, Constant.country: country, Constant.age: age}),
      );
    } catch (e) {
      response = null;
      throw Exception("Update profile fail");
    }

    return response;
  }

  Future<http.Response> onGenerateVideoSignedUrl({required int lessonId}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      Map<String, String> headers = {
        Constant.content_type: Constant.application_json,
        'Accept': 'application/json'
      };
      
      // Only add authorization header if user is logged in (allows guest access for first lesson)
      if (token.isNotEmpty) {
        headers[Constant.authorization] = '${Constant.bearer} $token';
      }
      
      response = await http.post(
        Uri.parse("${Apis.generateVideoSignedUrl}$lessonId"),
        headers: headers,
      );
    } catch (e) {
      response = null;
      throw Exception("Generate video signed URL fail: $e");
    }

    return response;
  }

  Future<http.Response> onManualPayment({required int courseId}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    try {
      response = await http.post(
        Uri.parse(Apis.manualPaymentUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.userId: userId, Constant.courseId: courseId}),
      );
    } catch (e) {
      response = null;
      throw Exception("Manual payment fail");
    }

    return response;
  }

  Future<http.Response> onBookLiveSession({required String name, required String email, required String school, required String age, required String date, required String courseName}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.bookLiveSessionUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({
          Constant.name: name,
          Constant.email: email,
          Constant.schoolName: school,
          Constant.age: age, // Server expects integer or string? Validated as integer.
          Constant.date: date,
          Constant.courseName: courseName
        }),
      );
    } catch (e) {
      response = null;
      throw Exception("Book live session fail");
    }

    return response;
  }


  Future<http.Response> onFetchReviewQuestions({required int courseId}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
    
    print("Fetching Review Questions - CourseId: $courseId, UserId: $userId, HasToken: ${token.isNotEmpty}");
    
    try {
      // Build headers - always include authorization if token exists (required for auth:sanctum)
      Map<String, String> headers = {
        Constant.content_type: Constant.application_json,
        'Accept': 'application/json'
      };
      
      // Add authorization header if token exists (required for Sanctum auth)
      if (token.isNotEmpty) {
        headers[Constant.authorization] = '${Constant.bearer} $token';
        print("Review Questions - Token sent: ${token.substring(0, token.length > 20 ? 20 : token.length)}...");
      } else {
        print("Review Questions - WARNING: No token found!");
      }
      
      String url = "${Apis.fetchReviewQuestionsUrl}$courseId";
      print("Review Questions - URL: $url");
      
      response = await http.get(
        Uri.parse(url),
        headers: headers,
      );
      
      print("Review Questions API Status: ${response.statusCode}");
      print("Review Questions API Response: ${response.body}");
    } catch (e) {
      print("Review Questions API Error: $e");
      response = null;
      throw Exception("Fetch review questions fail: $e");
    }
    return response;
  }

  Future<http.Response> onSubmitReviewAnswers({required int courseId, required List<Map<String, dynamic>> answers}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse("${Apis.submitReviewAnswersUrl}$courseId"),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({"answers": answers}),
      );
    } catch (e) {
      response = null;
      throw Exception("Submit review answers fail");
    }
    return response;
  }

  Future<http.Response> onCheckCourseCompletion({required int courseId}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.checkCourseCompletionUrl),
        headers: {Constant.content_type: Constant.application_json, 'Accept': 'application/json', Constant.authorization: '${Constant.bearer} $token'},
        body: jsonEncode({Constant.courseId: courseId}),
      );
    } catch (e) {
      response = null;
      throw Exception("Check course completion fail");
    }
    return response;
  }

  Future<http.Response> onCheckCoupon({required String code, required int courseId, String? currency}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.checkCouponUrl),
        headers: {
          Constant.content_type: Constant.application_json,
          'Accept': 'application/json',
          Constant.authorization: '${Constant.bearer} $token'
        },
        body: jsonEncode({
          'code': code,
          'course_id': courseId,
          'currency': currency ?? 'INR',
        }),
      );
    } catch (e) {
      response = null;
      throw Exception("Check coupon fail: $e");
    }
    return response;
  }

  Future<http.Response> onGetDiscountedPrice({required int courseId, String? currency}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.getDiscountedPriceUrl),
        headers: {
          Constant.content_type: Constant.application_json,
          'Accept': 'application/json',
          Constant.authorization: '${Constant.bearer} $token'
        },
        body: jsonEncode({
          'course_id': courseId,
          'currency': currency ?? 'INR',
        }),
      );
    } catch (e) {
      response = null;
      throw Exception("Get discounted price fail: $e");
    }
    return response;
  }

  Future<http.Response> onSaveLessonPosition({required int lessonId, required int positionSeconds}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.saveLessonPositionUrl),
        headers: {
          Constant.content_type: Constant.application_json,
          'Accept': 'application/json',
          Constant.authorization: '${Constant.bearer} $token'
        },
        body: jsonEncode({
          'lesson_id': lessonId,
          'position_seconds': positionSeconds,
        }),
      );
    } catch (e) {
      response = null;
      throw Exception("Save lesson position fail: $e");
    }
    return response;
  }

  Future<http.Response> onGetLessonPosition({required int lessonId}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.get(
        Uri.parse('${Apis.getLessonPositionUrl}$lessonId'),
        headers: {
          'Accept': 'application/json',
          Constant.authorization: '${Constant.bearer} $token'
        },
      );
    } catch (e) {
      response = null;
      throw Exception("Get lesson position fail: $e");
    }
    return response;
  }

  Future<http.Response> onGetLastWatched({required int courseId}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.get(
        Uri.parse('${Apis.getLastWatchedUrl}$courseId'),
        headers: {
          'Accept': 'application/json',
          Constant.authorization: '${Constant.bearer} $token'
        },
      );
    } catch (e) {
      response = null;
      throw Exception("Get last watched fail: $e");
    }
    return response;
  }

  Future<http.Response> onGetNextLesson({required int lessonId}) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.get(
        Uri.parse('${Apis.getNextLessonUrl}$lessonId'),
        headers: {
          'Accept': 'application/json',
          Constant.authorization: '${Constant.bearer} $token'
        },
      );
    } catch (e) {
      response = null;
      throw Exception("Get next lesson fail: $e");
    }
    return response;
  }

  // Create PayPal order for international payments (USD)
  Future<http.Response> onCreatePayPalOrder({
    required int courseId,
    String? couponCode,
  }) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      Map<String, dynamic> body = {
        Constant.courseId: courseId,
      };

      if (couponCode != null && couponCode.isNotEmpty) {
        body['coupon_code'] = couponCode;
      }

      response = await http.post(
        Uri.parse(Apis.createPayPalOrderUrl),
        headers: {
          Constant.content_type: Constant.application_json,
          'Accept': 'application/json',
          Constant.authorization: '${Constant.bearer} $token'
        },
        body: jsonEncode(body),
      );
    } catch (e) {
      response = null;
      throw Exception("Create PayPal order fail: $e");
    }
    return response;
  }

  // Verify PayPal payment and grant course access
  Future<http.Response> onVerifyPayPalPayment({
    required int courseId,
    required String orderId,
    String? couponCode,
  }) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      Map<String, dynamic> body = {
        Constant.courseId: courseId,
        'order_id': orderId,
      };

      if (couponCode != null && couponCode.isNotEmpty) {
        body['coupon_code'] = couponCode;
      }

      response = await http.post(
        Uri.parse(Apis.verifyPayPalPaymentUrl),
        headers: {
          Constant.content_type: Constant.application_json,
          'Accept': 'application/json',
          Constant.authorization: '${Constant.bearer} $token'
        },
        body: jsonEncode(body),
      );
    } catch (e) {
      response = null;
      throw Exception("Verify PayPal payment fail: $e");
    }
    return response;
  }


  // Capture PayPal order (after user approves)
  Future<http.Response> onCapturePayPalOrder({
    required String orderId,
  }) async {
    http.Response? response;
    String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);
    try {
      response = await http.post(
        Uri.parse(Apis.capturePayPalOrderUrl),
        headers: {
          Constant.content_type: Constant.application_json,
          'Accept': 'application/json',
          Constant.authorization: '${Constant.bearer} $token'
        },
        body: jsonEncode({
          'order_id': orderId,
        }),
      );
    } catch (e) {
      response = null;
      throw Exception("Capture PayPal order fail: $e");
    }
    return response;
  }

  // Fetch User Country via IP (External API)
  Future<String?> fetchUserCountryFromIP() async {
    try {
      // Using api.country.is (HTTPS, simple JSON: {"ip": "...", "country": "US"})
      final response = await http.get(Uri.parse('https://api.country.is'));
      
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return data['country']; // Returns 2-letter code e.g., "IN", "US"
      }
    } catch (e) {
      print("Error fetching country from IP: $e");
    }
    return null;
  }
}
