import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:little_farmer/app/certificate/model/certificate_list_model.dart';
import 'package:little_farmer/app/download_certificate/ui/download_certificate_screen.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';

class CertificateProvider extends ChangeNotifier {
  bool isCompleteCourseFetching = false;
  List<CompletedCourse> completedCoursesList = [];
  
  @override
  void dispose() {
    // No resources to dispose in this provider
    super.dispose();
  }

  Future<void> resetProvider() async {
    isCompleteCourseFetching = false;
    completedCoursesList = [];
  }

  Future<void> gotoCertificateScreen({required BuildContext context, required String title}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => DownloadCertificateScreen(title: title)));
  }

  Future<void> fetchCompleteCourseCertificate() async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isCompleteCourseFetching = true;
        completedCoursesList.clear();
        notifyListeners();

        var response = await ApiResponse().onFetchCompleteCourseCertificate();
        isCompleteCourseFetching = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          CertificateListModel fetchCertificate = CertificateListModel.fromJson(jsonMap);
          completedCoursesList = fetchCertificate.completedCourses;
          notifyListeners();
        } else if (response.statusCode == 404) {
          completedCoursesList.clear();
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
      } catch (e) {
        isCompleteCourseFetching = false;
        completedCoursesList.clear();
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isCompleteCourseFetching = false;
      completedCoursesList.clear();
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }
}
