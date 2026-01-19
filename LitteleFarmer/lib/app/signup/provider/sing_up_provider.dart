import 'dart:async';
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:little_farmer/app/login/ui/login_screen.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';

class SingUpProvider extends ChangeNotifier {
  final TextEditingController userNameController = TextEditingController();
  final TextEditingController emailController = TextEditingController();
  final TextEditingController schoolNameController = TextEditingController();
  final TextEditingController ageController = TextEditingController();
  final TextEditingController referralController = TextEditingController(text: "");
  bool isApiCalling = false;
  String country = "";

  Future<void> resetProvider() async {
    userNameController.clear();
    emailController.clear();
    schoolNameController.clear();
    ageController.clear();
    isApiCalling = false;
  }

  Future<void> gotoLoginScreen({required BuildContext context}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
  }

  Future<void> checkValidation({required BuildContext context}) async {
    if (userNameController.text.isEmpty) {
      Utils.showSnackbarMessage(message: CommonString.error_enter_username);
    } else if (emailController.text.isEmpty) {
      Utils.showSnackbarMessage(message: CommonString.error_enter_email);
    } else if (!Utils.isEmailValid(emailController.text.toString().trim())) {
      Utils.showSnackbarMessage(message: CommonString.error_enter_valid_email);
    } else if (schoolNameController.text.isEmpty) {
      Utils.showSnackbarMessage(message: CommonString.error_enter_school_name);
    } else if (ageController.text.isEmpty) {
      Utils.showSnackbarMessage(message: CommonString.error_enter_age);
    } else if (!Utils.isValidAge(ageController.text.toString().trim())) {
      Utils.showSnackbarMessage(message: CommonString.error_enter_valid_age);
    } else {
      if (await NetUtils.checkNetworkStatus()) {
        try {
          FocusScope.of(context).unfocus();
          isApiCalling = true;
          notifyListeners();

          String referralCode = referralController.text.toString().trim();
          if (referralCode.isEmpty) {
            referralCode = "";
          }

          var response = await ApiResponse().onRegistration(
            userName: userNameController.text.toString().trim(),
            email: emailController.text.toString().trim(),
            schoolName: schoolNameController.text.toString().trim(),
            age: ageController.text.toString().trim(),
            country: country,
            referralCode: referralCode,
          );
          isApiCalling = false;
          notifyListeners();

          if (response.statusCode == Constant.response_200) {
            Utils.showSnackbarMessage(message: CommonString.success_message_registration);
            resetProvider();
            notifyListeners();
            Navigator.of(context).pop();
          } else if(response.statusCode == 422) {
            Utils.showSnackbarMessage(message: CommonString.error_email_already_taken);
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
}
