import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/SharedPreferencesUtil.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';

class UpdateProfileProvider extends ChangeNotifier {
  final TextEditingController userNameController = TextEditingController();
  final TextEditingController emailController = TextEditingController();
  final TextEditingController schoolNameController = TextEditingController();
  final TextEditingController ageController = TextEditingController();
  bool isApiCalling = false;
  String country = "";

  Future<void> resetProvider() async {
    userNameController.clear();
    emailController.clear();
    schoolNameController.clear();
    ageController.clear();
    isApiCalling = false;
    country = "";
  }

  Future<void> assignValue() async {
    userNameController.text = SharedPreferencesUtil.getString(SharedPreferencesKey.name);
    emailController.text = SharedPreferencesUtil.getString(SharedPreferencesKey.email);
    schoolNameController.text = SharedPreferencesUtil.getString(SharedPreferencesKey.schoolName);
    ageController.text = SharedPreferencesUtil.getInteger(SharedPreferencesKey.age).toString();
    country = SharedPreferencesUtil.getString(SharedPreferencesKey.country);
  }

  Future<void> updateProfile({required BuildContext context}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        FocusScope.of(context).unfocus();
        isApiCalling = true;
        notifyListeners();

        var response = await ApiResponse().onUpdateProfile(userName: userNameController.text.toString().trim(), email: emailController.text.toString().trim(), schoolName: schoolNameController.text.toString().trim(), age: ageController.text.toString().trim(), country: country);
        isApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Utils.showSnackbarMessage(message: CommonString.success_message_profile_update);
          SharedPreferencesUtil.setString(SharedPreferencesKey.email, emailController.text.trim());
          SharedPreferencesUtil.setString(SharedPreferencesKey.name, userNameController.text.trim());
          SharedPreferencesUtil.setString(SharedPreferencesKey.schoolName, schoolNameController.text.trim());
          SharedPreferencesUtil.setString(SharedPreferencesKey.country, country);
          SharedPreferencesUtil.setInteger(SharedPreferencesKey.age, int.parse(ageController.text.trim()));
          SharedPreferencesUtil.setBoolean(SharedPreferencesKey.isLogin, true);

          resetProvider();
          notifyListeners();
          Navigator.of(context).pop(true);
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
      } catch (e) {
        isApiCalling = false;
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }
}
