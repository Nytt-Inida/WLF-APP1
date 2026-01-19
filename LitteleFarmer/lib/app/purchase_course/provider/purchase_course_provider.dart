import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:little_farmer/app/purchase_course/model/purchase_course_model.dart';
import 'package:little_farmer/app/purchase_course_detail/ui/purchase_course_detail_screen.dart';
import 'package:little_farmer/app/search/ui/search_screen.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';

class PurchaseCourseProvider extends ChangeNotifier {
  bool isFetchPurchaseCourseApiCalling = false;
  bool isFavoriteCourseApiCalling = false;
  List<PurchaseCourse> purchaseCoursesList = [];
  int selectedItemForFavorite = 0;
  
  @override
  void dispose() {
    // No resources to dispose in this provider
    super.dispose();
  }

  Future<void> resetProvider() async {
    isFetchPurchaseCourseApiCalling = false;
    isFavoriteCourseApiCalling = false;
    selectedItemForFavorite = 0;
    purchaseCoursesList.clear();
  }

  Future<void> gotoSearchScreen({required BuildContext context}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => SearchScreen()));
  }

  Future<void> fetchPurchaseCourse() async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isFetchPurchaseCourseApiCalling = true;
        purchaseCoursesList.clear();
        notifyListeners();

        var response = await ApiResponse().onFetchPurchaseCourse();
        isFetchPurchaseCourseApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          PurchaseCourseModel fetchPurchaseCourse = PurchaseCourseModel.fromJson(jsonMap);
          purchaseCoursesList = fetchPurchaseCourse.purchaseCourses;
          notifyListeners();
        } else if (response.statusCode == 404) {
          purchaseCoursesList.clear();
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
      } catch (e) {
        isFetchPurchaseCourseApiCalling = false;
        purchaseCoursesList.clear();
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isFetchPurchaseCourseApiCalling = false;
      purchaseCoursesList.clear();
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
          if (purchaseCoursesList[position].isFavorite == 0) {
            purchaseCoursesList[position].isFavorite = 1;
          } else {
            purchaseCoursesList[position].isFavorite = 0;
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

  Future<void> gotoPurchaseCourseDetailScreen({required BuildContext context, required int courseId, required String title}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => PurchaseCourseDetailScreen(courseId: courseId, title: title)));
  }
}
