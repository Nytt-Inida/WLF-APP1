import 'dart:async';
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:little_farmer/app/login/model/login_model.dart';
import 'package:provider/provider.dart';
import 'package:little_farmer/app/main_home/ui/main_home_screen.dart';
import 'package:little_farmer/app/profile/provider/profile_provider.dart';
import 'package:little_farmer/app/signup/ui/sing_up_screen.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/SharedPreferencesUtil.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';

class LoginProvider extends ChangeNotifier {
  final TextEditingController emailController = TextEditingController();
  final TextEditingController otpController = TextEditingController();
  bool isApiCalling = false;
  bool isNoInternet = false;
  bool isOtpApiCalled = false;
  bool isOtpNotAvailable = true;
  late ApiResponse apiResponse;

  LoginProvider({required this.apiResponse});

  Future<void> resetProvider() async {
    emailController.clear();
    otpController.clear();
    isApiCalling = false;
    isNoInternet = false;
    isOtpApiCalled = false;
    isOtpNotAvailable = true;
  }

  Future<void> gotoSignupScreen({required BuildContext context}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => const SingUpScreen()));
  }

  Future<void> sendOtp({required BuildContext context}) async {
    if (emailController.text.trim().isEmpty) {
      Utils.showSnackbarMessage(message: CommonString.error_enter_email);
      return;
    }

    FocusScope.of(context).unfocus();
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isApiCalling = true;
        print("isApiCalling: $isApiCalling");
        isNoInternet = false;
        notifyListeners();

        http.Response response = await apiResponse.onSendOtp(email: emailController.text.toString().trim());
        print("Otp Api Response : ${response.body}");
        isApiCalling = false;
        print("isApiCalling: $isApiCalling  ${response.body}");
        notifyListeners();

        var jsonMap = jsonDecode(response.body);
        if (response.statusCode == Constant.response_200) {
          isOtpApiCalled = true;
          isOtpNotAvailable = false;
          notifyListeners();
        } else {
          Utils.showSnackbarMessage(message: jsonMap[Constant.message]);
        }
      } catch (e) {
        isApiCalling = false;
        notifyListeners();
        Utils.showSnackbarMessage(message: e.toString());
      }
    } else {
      isApiCalling = false;
      isNoInternet = true;
      Utils.showSnackbarMessage(message: CommonString.no_internet);
      notifyListeners();
    }
  }

  Future<void> onLogin({required BuildContext context}) async {
    if (otpController.text.toString().trim().isNotEmpty) {
      FocusScope.of(context).unfocus();
      if (await NetUtils.checkNetworkStatus()) {
        try {
          isApiCalling = true;
          isNoInternet = false;
          notifyListeners();

          http.Response response = await ApiResponse().onLogin(email: emailController.text.toString().trim(), otp: otpController.text.toString().trim());
          isApiCalling = false;
          notifyListeners();

          var jsonMap = jsonDecode(response.body);
          if (response.statusCode == Constant.response_200) {
            Map<String, dynamic> jsonResponse = jsonDecode(response.body);
            LoginModel loginModel = LoginModel.fromJson(jsonResponse);
            SharedPreferencesUtil.setString(SharedPreferencesKey.token, loginModel.token);
            SharedPreferencesUtil.setString(SharedPreferencesKey.email, loginModel.email);
            SharedPreferencesUtil.setString(SharedPreferencesKey.name, loginModel.name);
            SharedPreferencesUtil.setInteger(SharedPreferencesKey.id, loginModel.id);
            SharedPreferencesUtil.setString(SharedPreferencesKey.profilePhoto, loginModel.profilePhoto);
            SharedPreferencesUtil.setString(SharedPreferencesKey.schoolName, loginModel.schoolName);
            SharedPreferencesUtil.setString(SharedPreferencesKey.country, loginModel.country);
            SharedPreferencesUtil.setString(SharedPreferencesKey.referralCode, loginModel.referralCode);
            SharedPreferencesUtil.setInteger(SharedPreferencesKey.age, loginModel.age);
            SharedPreferencesUtil.setBoolean(SharedPreferencesKey.isLogin, true);
            
            // Update profile provider with referral status
            final profileProvider = Provider.of<ProfileProvider>(context, listen: false);
            profileProvider.referralCode = loginModel.referralCode;
            profileProvider.isReferralEnabled = loginModel.isReferralEnabled;
            profileProvider.notifyListeners();
            Utils.showSnackbarMessage(message: CommonString.success_message_login);

            resetProvider();
            Timer(const Duration(seconds: 1), () => Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => const MainHomeScreen())));
          } else {
            Utils.showSnackbarMessage(message: jsonMap[Constant.message]);
          }
        } catch (e) {
          Utils.showSnackbarMessage(message: e.toString());
        }
      } else {
        isApiCalling = false;
        isNoInternet = true;
        Utils.showSnackbarMessage(message: CommonString.no_internet);
        notifyListeners();
      }
    } else {
      Utils.showSnackbarMessage(message: CommonString.error_enter_otp);
    }
  }
}
