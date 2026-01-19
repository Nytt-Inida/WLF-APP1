import 'dart:async';
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:little_farmer/app/certificate/ui/certificate_screen.dart';
import 'package:little_farmer/app/login/ui/login_screen.dart';
import 'package:little_farmer/app/profile/ui/contact_screen.dart';
import 'package:little_farmer/app/profile/ui/faq_screen.dart';
import 'package:little_farmer/app/profile/ui/referral_code_screen.dart';
import 'package:little_farmer/app/update_profile/ui/update_profile_screen.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/SharedPreferencesUtil.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';

class ProfileProvider extends ChangeNotifier {
  bool isLogoutApiCalling = false;
  bool isFetchingProfile = false;
  String userName = SharedPreferencesUtil.getString(SharedPreferencesKey.name);
  String email = SharedPreferencesUtil.getString(SharedPreferencesKey.email);
  String referralCode = SharedPreferencesUtil.getString(SharedPreferencesKey.referralCode);
  bool isReferralEnabled = true; // Default to true, will be updated from API
  List<Map<String, dynamic>> userRewards = []; // Store user rewards/coupons
  String? signupReferralCode; // Referrer's code used during signup (for automatic discount)
  
  @override
  void dispose() {
    // No resources to dispose in this provider
    super.dispose();
  }

  Future<void> resetProvider() async {
    isLogoutApiCalling = false;
  }

  Future<void> gotoLoginScreen({required BuildContext context}) async {
    Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
  }

  Future<void> gotoCertificateScreen({required BuildContext context}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => const CertificateScreen()));
  }

  Future<void> gotoUpdateProfileScreen({required BuildContext context}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => const UpdateProfileScreen())).then((value) {
      if (value) {
        userName = SharedPreferencesUtil.getString(SharedPreferencesKey.name);
        email = SharedPreferencesUtil.getString(SharedPreferencesKey.email);
        notifyListeners();
      }
    });
  }

  Future<void> gotoFaqScreen({required BuildContext context}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => const FaqScreen()));
  }

  Future<void> gotoContactScreen({required BuildContext context}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => const ContactScreen()));
  }

  Future<void> gotoReferralCodeScreen({required BuildContext context}) async {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => const ReferralCodeScreen()),
    );
  }

  bool _isFetchingProfileData = false;

  Future<void> fetchProfileData() async {
    // Prevent multiple simultaneous calls
    if (_isFetchingProfileData || isFetchingProfile) {
      return;
    }

    if (await NetUtils.checkNetworkStatus()) {
      try {
        _isFetchingProfileData = true;
        isFetchingProfile = true;
        notifyListeners();

        int userId = SharedPreferencesUtil.getInteger(SharedPreferencesKey.id);
        String token = SharedPreferencesUtil.getString(SharedPreferencesKey.token);

        if (userId > 0 && token.isNotEmpty) {
          var response = await ApiResponse().onGetProfile(userId: userId);
          
          if (response.statusCode == Constant.response_200) {
            Map<String, dynamic> decoded = jsonDecode(response.body);
            if (decoded['code'] == 200) {
              referralCode = decoded['referral_code'] ?? '';
              isReferralEnabled = decoded['is_referral_enabled'] ?? true;
              userRewards = List<Map<String, dynamic>>.from(decoded['rewards'] ?? []);
              signupReferralCode = decoded['signup_referral_code'];
            }
          }
        }
      } catch (e) {
        print("Error fetching profile data: $e");
      } finally {
        _isFetchingProfileData = false;
        isFetchingProfile = false;
        notifyListeners();
      }
    }
  }

  Future<void> onLogout({required BuildContext context}) async {
    // Always clear local storage first, regardless of API call result
    // This ensures logout works even with invalid credentials
    try {
      isLogoutApiCalling = true;
      notifyListeners();

      // Try to call logout API (but don't fail if it doesn't work)
      if (await NetUtils.checkNetworkStatus()) {
        var response = await ApiResponse().onLogout();
        // Response can be null if API failed, but we continue anyway
        if (response != null && response.statusCode == 200) {
          debugPrint("Logout API call successful");
        } else {
          debugPrint("Logout API call failed or returned error (continuing with local logout)");
        }
      }

      // Always clear local preferences
      await SharedPreferencesUtil.clearPreference();
      isLogoutApiCalling = false;
      notifyListeners();

      // Navigate to login screen
      Timer(const Duration(seconds: 1), () {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => const LoginScreen()),
        );
      });
    } catch (e) {
      // Even if something goes wrong, try to clear preferences
      try {
        await SharedPreferencesUtil.clearPreference();
      } catch (clearError) {
        print("Error clearing preferences: $clearError");
      }
      isLogoutApiCalling = false;
      notifyListeners();
      
      // Still navigate to login screen
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (context) => const LoginScreen()),
      );
    }
  }
}
