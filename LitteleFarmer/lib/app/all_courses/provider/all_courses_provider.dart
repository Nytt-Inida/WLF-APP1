import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:little_farmer/app/course_detail/ui/course_detail_screen.dart';
import 'package:little_farmer/app/home/model/course_by_age_model.dart';
import 'package:little_farmer/app/purchase_course_detail/ui/purchase_course_detail_screen.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';

class AllCoursesProvider extends ChangeNotifier {
  int selectedItemForFavorite = -1;
  bool isCourseFetchApiCalling = false;
  bool isFavoriteApiCalling = false;
  List<Course> allCoursesList = [];

  Future<void> resetProvider() async {
    selectedItemForFavorite = -1;
    isCourseFetchApiCalling = false;
    isFavoriteApiCalling = false;
    allCoursesList.clear();
  }
  
  @override
  void dispose() {
    resetProvider();
    super.dispose();
  }

  Future<void> gotoCourseDetailScreen({required BuildContext context, required int courseId, required String title, required String price, required bool isPurchased}) async {
    if (isPurchased) {
      Navigator.push(context, MaterialPageRoute(builder: (context) => PurchaseCourseDetailScreen(courseId: courseId, title: title)));
    } else {
      Navigator.push(context, MaterialPageRoute(builder: (context) => CourseDetailScreen(courseId: courseId, title: title, price: price)));
    }
  }

  Future<void> fetchAllCourses() async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isCourseFetchApiCalling = true;
        allCoursesList.clear();
        notifyListeners();

        // Use "Any age" to fetch all courses
        var response = await ApiResponse().onFetchCourseByAge(age: "Any age");
        isCourseFetchApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          CourseByAgeModel fetchCourseByAge = CourseByAgeModel.fromJson(jsonMap);
          allCoursesList = fetchCourseByAge.courses;
          notifyListeners();
        } else if (response.statusCode == Constant.response_404) {
          allCoursesList.clear();
          notifyListeners();
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
          allCoursesList.clear();
          notifyListeners();
        }
      } catch (e) {
        isCourseFetchApiCalling = false;
        allCoursesList.clear();
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isCourseFetchApiCalling = false;
      allCoursesList.clear();
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  Future<void> addRemoveCourseInFavorite({required int position, required int courseId, required bool isFavorite, required List<Course> coursesList}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isFavoriteApiCalling = true;
        selectedItemForFavorite = position;
        notifyListeners();

        var response = await ApiResponse().onAddCourseInFavorite(courseId: courseId, isFavorite: isFavorite);
        isFavoriteApiCalling = false;
        selectedItemForFavorite = -1;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          if (jsonMap[Constant.code] == Constant.response_200) {
            // Update the favorite status in the list
            if (position < coursesList.length) {
              coursesList[position].isFavorite = isFavorite ? 1 : 0;
              notifyListeners();
            }
          } else {
            Utils.showSnackbarMessage(message: jsonMap[Constant.message] ?? CommonString.something_went_wrong);
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
        isFavoriteApiCalling = false;
        selectedItemForFavorite = -1;
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isFavoriteApiCalling = false;
      selectedItemForFavorite = -1;
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }
}
