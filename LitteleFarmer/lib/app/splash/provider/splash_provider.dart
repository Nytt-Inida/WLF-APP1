import 'package:flutter/material.dart';
import 'package:firebase_database/firebase_database.dart';
import 'package:little_farmer/app/login/ui/login_screen.dart';
import 'package:little_farmer/app/main_home/ui/main_home_screen.dart';
import 'package:little_farmer/network/apis.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/payment_config.dart';
import 'package:little_farmer/utils/SharedPreferencesUtil.dart';

class SplashProvider extends ChangeNotifier {
  String apiUrl = "";
  bool isPurchase = false;
  final DatabaseReference? databaseReference;
  SplashProvider({this.databaseReference});

  Future<void> gotoMainScreen({required BuildContext context}) async {
    debugPrint('gotoMainScreen called');
    await Future.delayed(const Duration(seconds: 3));
    debugPrint('gotoMainScreen delay completed');
    
    // Ensure context is still valid and widget is mounted before navigation
    if (!context.mounted) {
      debugPrint('Context is not mounted, cannot navigate');
      return;
    }
    
    try {
      debugPrint('Checking login status');
      final isLoggedIn = SharedPreferencesUtil.getBoolean(SharedPreferencesKey.isLogin);
      debugPrint('Login status: $isLoggedIn');
      
      if (!context.mounted) {
        debugPrint('Context lost before navigation');
        return;
      }
      
      debugPrint('Navigating to ${isLoggedIn ? "MainHomeScreen" : "LoginScreen"}');
      if (isLoggedIn) {
        await Navigator.of(context).pushReplacement(
          MaterialPageRoute(builder: (context) => MainHomeScreen())
        );
        debugPrint('Navigated to MainHomeScreen');
      } else {
        await Navigator.of(context).pushReplacement(
          MaterialPageRoute(builder: (context) => LoginScreen())
        );
        debugPrint('Navigated to LoginScreen');
      }
    } catch (e, stackTrace) {
      debugPrint('Error navigating from splash screen: $e');
      debugPrint('Stack trace: $stackTrace');
      // Retry navigation after a short delay
      Future.delayed(const Duration(milliseconds: 1000), () {
        if (!context.mounted) {
          debugPrint('Context not mounted during retry');
          return;
        }
        try {
          debugPrint('Retrying navigation');
          final isLoggedIn = SharedPreferencesUtil.getBoolean(SharedPreferencesKey.isLogin);
          if (isLoggedIn) {
            Navigator.of(context).pushReplacement(
              MaterialPageRoute(builder: (context) => MainHomeScreen())
            );
          } else {
            Navigator.of(context).pushReplacement(
              MaterialPageRoute(builder: (context) => LoginScreen())
            );
          }
        } catch (e2, stackTrace2) {
          debugPrint('Error retrying navigation: $e2');
          debugPrint('Stack trace: $stackTrace2');
        }
      });
    }
  }

  Future<void> getApi() async {
    try {
      apiUrl = "";

      // databaseReference.child(Constant.Api).onValue.listen((event) {
      //   DataSnapshot snapshot = event.snapshot;
      //   if (snapshot.value != null) {
      //     final data = Map<String, dynamic>.from(snapshot.value as Map);
      //     apiUrl = data[Constant.api] ?? '';
      //     isPurchase = data[Constant.isPurchase] ?? false;
      //     debugPrint("App url : $apiUrl"); // Changed from print to debugPrint
      //   } else {
      //     apiUrl = '';
      //     isPurchase = false;
      //   }
      //   Apis.baseURL = apiUrl;
      //   notifyListeners();
      // }, onError: (error) {});

      // Hardcoded URL for welittlefarmers.com
      apiUrl = "https://welittlefarmers.com/api/"; 
      isPurchase = true; 
      debugPrint("App url (Hardcoded): $apiUrl");
      Apis.baseURL = apiUrl;

      // Fetch User Country via IP (Global Setup) - don't block on this
      // Run in background to avoid blocking app startup
      ApiResponse().fetchUserCountryFromIP().then((country) {
        if (country != null) {
          PaymentConfig.detectedCountryCode = country;
          debugPrint("Global Detected Country: $country");
        }
      }).catchError((e) {
        debugPrint("Global Country Fetch Error: $e");
        // Don't fail app startup if country detection fails
      });

      // Use addPostFrameCallback to safely call notifyListeners
      WidgetsBinding.instance.addPostFrameCallback((_) {
        try {
          notifyListeners();
        } catch (e) {
          debugPrint("Error in notifyListeners (getApi): $e");
        }
      });
    } catch (e) {
      debugPrint("Error in getApi: $e");
      // Set default values even if there's an error
      apiUrl = "https://welittlefarmers.com/api/";
      isPurchase = true;
      Apis.baseURL = apiUrl;
    }
  }
}
