import 'dart:async';
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:little_farmer/app/course_detail/ui/course_detail_screen.dart';
import 'package:little_farmer/app/purchase_course_detail/ui/purchase_course_detail_screen.dart';
import 'package:little_farmer/app/search/model/search_course_model.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';

class SearchProvider extends ChangeNotifier {
  TextEditingController searchController = TextEditingController();
  Timer? _debounce;
  bool isDataSearched = true; // Default to true so it doesn't show loader initially
  List<SearchCourse> searchCoursesList = [];
  bool isFavoriteApiCalling = false;
  int selectedItemForFavorite = 0;

  bool _isSearched = false;
  bool get isSearched => _isSearched;
  set isSearched(bool value) {
    _isSearched = value;
    notifyListeners();
  }

  Future<void> resetProvider() async {
    _debounce?.cancel();
    _debounce = null;
    searchCoursesList = [];
    isDataSearched = true; // Reset to true
    _isSearched = false;
    isFavoriteApiCalling = false;
    selectedItemForFavorite = 0;
  }
  
  @override
  void dispose() {
    _debounce?.cancel();
    searchController.dispose();
    super.dispose();
  }

  Future<void> gotoCourseDetailScreen({required BuildContext context, required int courseId, required String title, required String price, required bool isPurchased}) async {
    if (isPurchased) {
      Navigator.push(context, MaterialPageRoute(builder: (context) => PurchaseCourseDetailScreen(courseId: courseId, title: title)));
    } else {
      Navigator.push(context, MaterialPageRoute(builder: (context) => CourseDetailScreen(courseId: courseId, title: title, price: price)));
    }
  }

  Future<void> onSearchChanged() async {
    if (_debounce?.isActive ?? false) _debounce?.cancel();
    _debounce = Timer(const Duration(milliseconds: 500), () {
      if (searchController.text.trim().isNotEmpty) {
        searchCourse();
      } else {
        isDataSearched = true; // Don't show loader for empty text
        searchCoursesList.clear();
        notifyListeners();
      }
    });
  }

  Future<void> searchCourse() async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isDataSearched = false; // Start loading
        notifyListeners();
        
        var response = await ApiResponse().onFetchSearchCourse(search: searchController.text.toString().trim());

        if (response.statusCode == Constant.response_200) {
          isDataSearched = true;
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          SearchCourseModel fetchCourseByAge = SearchCourseModel.fromJson(jsonMap);
          searchCoursesList = fetchCourseByAge.courses;
          notifyListeners();
        } else {
          isDataSearched = true; // Stop loading on error
          searchCoursesList.clear();
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
          notifyListeners();
        }
      } catch (e) {
        isDataSearched = true; // Stop loading on exception
        searchCoursesList.clear();
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isDataSearched = true; // Stop loading on no internet
      searchCoursesList.clear();
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  Future<void> addRemoveCourseInFavorite({required int position, required int courseId, required bool isFavorite}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isFavoriteApiCalling = true;
        notifyListeners();

        var response = await ApiResponse().onAddCourseInFavorite(courseId: courseId, isFavorite: isFavorite);
        isFavoriteApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          if (searchCoursesList[position].isFavorite == 0) {
            searchCoursesList[position].isFavorite = 1;
          } else {
            searchCoursesList[position].isFavorite = 0;
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
