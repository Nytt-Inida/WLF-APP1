import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:little_farmer/app/course_detail/ui/course_detail_screen.dart';
import 'package:little_farmer/app/home/model/popular_course_model.dart';
import 'package:little_farmer/app/purchase_course_detail/ui/purchase_course_detail_screen.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';

class PopularCourseProvider extends ChangeNotifier {
  bool isFavoriteApiCalling = false;
  int selectedItemForFavorite = 0;
  
  @override
  void dispose() {
    // No resources to dispose in this provider
    super.dispose();
  }

  Future<void> resetProvider() async {
    isFavoriteApiCalling = false;
    selectedItemForFavorite = 0;
  }

  Future<void> gotoCourseDetailScreen({required BuildContext context, required int courseId, required String title, required String price, required bool isPurchased}) async {
    if (isPurchased) {
      Navigator.push(context, MaterialPageRoute(builder: (context) => PurchaseCourseDetailScreen(courseId: courseId, title: title)));
    } else {
      Navigator.push(context, MaterialPageRoute(builder: (context) => CourseDetailScreen(courseId: courseId, title: title, price: price)));
    }
  }

  Future<void> addRemoveCourseInFavorite({required int position, required int courseId, required bool isFavorite, required List<PopularCourse> popularCoursesList}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isFavoriteApiCalling = true;
        notifyListeners();

        var response = await ApiResponse().onAddCourseInFavorite(courseId: courseId, isFavorite: isFavorite);
        isFavoriteApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          if (popularCoursesList[position].isFavorite == 0) {
            popularCoursesList[position].isFavorite = 1;
          } else {
            popularCoursesList[position].isFavorite = 0;
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
        isFavoriteApiCalling = false;
        notifyListeners();
        Utils.showSnackbarMessage(message: e.toString());
      }
    } else {
      isFavoriteApiCalling = false;
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }
}
