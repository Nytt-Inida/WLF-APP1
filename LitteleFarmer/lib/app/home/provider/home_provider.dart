import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:little_farmer/app/course_detail/ui/course_detail_screen.dart';
import 'package:little_farmer/app/home/model/course_by_age_model.dart';
import 'package:little_farmer/app/home/model/popular_course_model.dart';
import 'package:little_farmer/app/popular_course/ui/popular_course_screen.dart';
import 'package:little_farmer/app/purchase_course_detail/ui/purchase_course_detail_screen.dart';
import 'package:little_farmer/app/search/ui/search_screen.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';

class HomeProvider extends ChangeNotifier {
  int selectedItem = 0;
  int selectedItemForFavorite = 0;
  List<String> ageList = ["All", "AGE 5 TO 8", "AGE 9 TO 12", "AGE 13 TO 15"];
  bool isCourseFetchByAgeApiCalling = false;
  bool isPopularCourseApiCalling = false;
  bool isFavoriteApiCalling = false;
  bool isFavoritePopularApiCalling = false;
  List<Course> coursesByAgeList = [];
  List<PopularCourse> popularCoursesList = [];

  Future<void> resetProvider() async {
    selectedItem = 0;
    isCourseFetchByAgeApiCalling = false;
    isFavoritePopularApiCalling = false;
    isFavoriteApiCalling = false;
    isPopularCourseApiCalling = false;
    coursesByAgeList.clear();
    popularCoursesList.clear();
    selectedItemForFavorite = 0;
  }
  
  @override
  void dispose() {
    // Don't call resetProvider during dispose - it can cause "setState during dispose" errors
    // Just clear the lists without notifying
    selectedItem = 0;
    isCourseFetchByAgeApiCalling = false;
    isFavoritePopularApiCalling = false;
    isFavoriteApiCalling = false;
    isPopularCourseApiCalling = false;
    coursesByAgeList.clear();
    popularCoursesList.clear();
    selectedItemForFavorite = 0;
    super.dispose();
  }

  Future<void> gotoPopularCoursesScreen({required BuildContext context}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => PopularCourseScreen(popularCoursesList: popularCoursesList))).then((e) {
      fetchPopularCourse();
    });
  }

  Future<void> gotoCourseDetailScreen({required BuildContext context, required int courseId, required String title, required String price, required bool isPurchased}) async {
    if (isPurchased) {
      Navigator.push(context, MaterialPageRoute(builder: (context) => PurchaseCourseDetailScreen(courseId: courseId, title: title)));
    } else {
      Navigator.push(context, MaterialPageRoute(builder: (context) => CourseDetailScreen(courseId: courseId, title: title, price: price)));
    }
  }

  Future<void> gotoSearchScreen({required BuildContext context}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => const SearchScreen()));
  }

  Future<void> fetchCourseByAge({required String age}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isCourseFetchByAgeApiCalling = true;
        coursesByAgeList.clear();
        notifyListeners();

        var response = await ApiResponse().onFetchCourseByAge(age: age);
        isCourseFetchByAgeApiCalling = false;

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          CourseByAgeModel fetchCourseByAge = CourseByAgeModel.fromJson(jsonMap);
          coursesByAgeList = fetchCourseByAge.courses;
        } else if (response.statusCode == Constant.response_404) {
          coursesByAgeList.clear();
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
        notifyListeners();
      } catch (e) {
        isCourseFetchByAgeApiCalling = false;
        coursesByAgeList.clear();
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isCourseFetchByAgeApiCalling = false;
      coursesByAgeList.clear();
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  Future<void> fetchPopularCourse() async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isPopularCourseApiCalling = true;
        popularCoursesList.clear();
        notifyListeners();

        var response = await ApiResponse().onFetchPopularCourse();
        isPopularCourseApiCalling = false;

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          PopularCourseModel fetchPopularCourse = PopularCourseModel.fromJson(jsonMap);
          popularCoursesList = fetchPopularCourse.courses;
        } else if (response.statusCode == 404) {
          popularCoursesList.clear();
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
        notifyListeners();
      } catch (e) {
        isPopularCourseApiCalling = false;
        popularCoursesList.clear();
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isPopularCourseApiCalling = false;
      popularCoursesList.clear();
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  Future<void> addRemoveCourseInFavorite({required int position, required int courseId, required bool isFavorite, required bool isPopularCourse}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        if (isPopularCourse) {
          isFavoritePopularApiCalling = true;
        } else {
          isFavoriteApiCalling = true;
        }
        notifyListeners();

        var response = await ApiResponse().onAddCourseInFavorite(courseId: courseId, isFavorite: isFavorite);
        if (isPopularCourse) {
          isFavoritePopularApiCalling = false;
        } else {
          isFavoriteApiCalling = false;
        }
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          if (isPopularCourse) {
            if (popularCoursesList[position].isFavorite == 0) {
              popularCoursesList[position].isFavorite = 1;
            } else {
              popularCoursesList[position].isFavorite = 0;
            }
            notifyListeners();
          } else {
            if (coursesByAgeList[position].isFavorite == 0) {
              coursesByAgeList[position].isFavorite = 1;
            } else {
              coursesByAgeList[position].isFavorite = 0;
            }
            notifyListeners();
          }
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
      } catch (e) {
        if (isPopularCourse) {
          isFavoritePopularApiCalling = false;
        } else {
          isFavoriteApiCalling = false;
        }
        notifyListeners();
        Utils.showSnackbarMessage(message: e.toString());
      }
    } else {
      if (isPopularCourse) {
        isFavoritePopularApiCalling = false;
      } else {
        isFavoriteApiCalling = false;
      }
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }
}
