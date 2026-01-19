import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:little_farmer/app/course_detail/ui/course_detail_screen.dart';
import 'package:little_farmer/app/favorite/model/favorite_course_model.dart';
import 'package:little_farmer/app/purchase_course_detail/ui/purchase_course_detail_screen.dart';
import 'package:little_farmer/app/search/ui/search_screen.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';

class FavoriteProvider extends ChangeNotifier {
  bool isFavoriteCourseApiCalling = false;
  bool isFetchFavoriteCourseApiCalling = false;
  int selectedItemForFavorite = 0;
  List<FavoriteCourse> favoriteCoursesList = [];

  Future<void> resetProvider() async {
    isFavoriteCourseApiCalling = false;
    isFetchFavoriteCourseApiCalling = false;
    selectedItemForFavorite = 0;
  }

  Future<void> gotoCourseDetailScreen({required BuildContext context, required int courseId, required String title, required String price, required bool isPurchased}) async {
    if (isPurchased) {
      Navigator.push(context, MaterialPageRoute(builder: (context) => PurchaseCourseDetailScreen(courseId: courseId, title: title)));
    } else {
      Navigator.push(context, MaterialPageRoute(builder: (context) => CourseDetailScreen(courseId: courseId, title: title, price: price)));
    }
  }

  Future<void> fetchFavoriteCourse() async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isFetchFavoriteCourseApiCalling = true;
        favoriteCoursesList.clear();
        notifyListeners();

        var response = await ApiResponse().onFetchFavoriteCourse();
        isFetchFavoriteCourseApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          FavoriteCourseModel fetchFavoriteCourse = FavoriteCourseModel.fromJson(jsonMap);
          favoriteCoursesList = fetchFavoriteCourse.favoriteCourses;
          notifyListeners();
        } else if (response.statusCode == 404) {
          favoriteCoursesList.clear();
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
      } catch (e) {
        isFetchFavoriteCourseApiCalling = false;
        favoriteCoursesList.clear();
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isFetchFavoriteCourseApiCalling = false;
      favoriteCoursesList.clear();
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  Future<void> addRemoveCourseInFavorite({required int position, required int courseId, required bool isFavorite}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isFavoriteCourseApiCalling = true;
        notifyListeners();

        var response = await ApiResponse().onAddCourseInFavorite(courseId: courseId, isFavorite: isFavorite);
        isFavoriteCourseApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          favoriteCoursesList[position].isFavorite = !favoriteCoursesList[position].isFavorite;
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
        isFavoriteCourseApiCalling = false;
        notifyListeners();
        Utils.showSnackbarMessage(message: e.toString());
      }
    } else {
      isFavoriteCourseApiCalling = false;
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  Future<void> gotoSearchScreen({required BuildContext context}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => SearchScreen()));
  }
}
