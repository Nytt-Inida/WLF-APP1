import 'dart:ui';
import 'package:firebase_database/firebase_database.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:little_farmer/app/blog/provider/blog_provider.dart';
import 'package:little_farmer/app/certificate/provider/certificate_provider.dart';
import 'package:little_farmer/app/course_detail/provider/course_detail_provider.dart';
import 'package:little_farmer/app/course_verify_done/provider/course_verify_done_provider.dart';
import 'package:little_farmer/app/download_certificate/provider/download_certificate_provider.dart';
import 'package:little_farmer/app/home/provider/home_provider.dart';
import 'package:little_farmer/app/login/provider/login_provider.dart';
import 'package:little_farmer/app/main_home/provider/main_home_provider.dart';
import 'package:little_farmer/app/live_sessions/provider/live_session_provider.dart';
import 'package:little_farmer/app/popular_course/provider/popular_course_provider.dart';
import 'package:little_farmer/app/profile/provider/profile_provider.dart';
import 'package:little_farmer/app/purchase_course/provider/purchase_course_provider.dart';
import 'package:little_farmer/app/payment/provider/payment_provider.dart';
import 'package:little_farmer/app/purchase_course_detail/provider/purchase_course_detail_provider.dart';
import 'package:little_farmer/app/purchase_login/provider/purchase_login_provider.dart';
import 'package:little_farmer/app/quiz/provider/quiz_provider.dart';
import 'package:little_farmer/app/search/provider/search_provider.dart';
import 'package:little_farmer/app/signup/provider/sing_up_provider.dart';
import 'package:little_farmer/app/splash/provider/splash_provider.dart';
import 'package:little_farmer/app/splash/ui/splash_screen.dart';
import 'package:little_farmer/app/update_profile/provider/update_profile_provider.dart';
import 'package:little_farmer/app/all_courses/provider/all_courses_provider.dart';
import 'package:little_farmer/firebase_options.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/SharedPreferencesUtil.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:provider/provider.dart';
import 'package:firebase_core/firebase_core.dart';

Future<void> main() async {
  debugPrint('=== APP STARTING ===');
  
  // CRITICAL: Initialize Flutter binding FIRST
  WidgetsFlutterBinding.ensureInitialized();
  debugPrint('WidgetsFlutterBinding initialized');
  
  // Set up global error handling - but don't swallow ALL errors
  FlutterError.onError = (FlutterErrorDetails details) {
    FlutterError.presentError(details);
    debugPrint('Flutter Error: ${details.exception}');
    debugPrint('Flutter Error Stack: ${details.stack}');
  };
  
  // Handle platform errors - but log them properly
  PlatformDispatcher.instance.onError = (error, stack) {
    debugPrint('Platform Error: $error');
    debugPrint('Platform Error Stack: $stack');
    return true; // Return true to prevent app from crashing
  };
  
  // Initialize SharedPreferences - don't block if it fails
  try {
    debugPrint('Initializing SharedPreferences...');
    await SharedPreferencesUtil.init();
    debugPrint('SharedPreferences initialized successfully');
  } catch (e, stackTrace) {
    debugPrint('Error initializing SharedPreferences: $e');
    debugPrint('Stack: $stackTrace');
    // Continue - app can work with default values
  }
  
  // Initialize Firebase - don't block if it fails
  DatabaseReference? databaseRef;
  
  try {
    debugPrint('Initializing Firebase...');
    await Firebase.initializeApp(options: DefaultFirebaseOptions.currentPlatform);
    debugPrint('Firebase initialized successfully');
    
    // Try to create database reference
    try {
      databaseRef = FirebaseDatabase.instance.ref();
      debugPrint('Firebase Database reference created');
    } catch (e) {
      debugPrint('Error creating Firebase Database reference: $e');
    }
  } catch (e, stackTrace) {
    debugPrint('Error initializing Firebase: $e');
    debugPrint('Stack: $stackTrace');
    // Continue - app can work without Firebase
  }
  
  // Set system UI - transparent status bar for iOS
  SystemChrome.setSystemUIOverlayStyle(
    SystemUiOverlayStyle(
      statusBarColor: Colors.transparent, // Transparent status bar
      statusBarIconBrightness: Brightness.dark,
      statusBarBrightness: Brightness.dark,
      systemNavigationBarColor: Colors.transparent, // Transparent navigation bar
      systemNavigationBarIconBrightness: Brightness.dark,
    ),
  );
  
  SystemChrome.setPreferredOrientations([
    DeviceOrientation.portraitUp,
    DeviceOrientation.portraitDown,
  ]);
  
  debugPrint('Starting app...');
  
  // CRITICAL: Call runApp IMMEDIATELY, not in a callback
  // This ensures the app starts even if other things fail
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (context) {
          debugPrint('Creating SplashProvider...');
          try {
            if (databaseRef != null) {
              debugPrint('SplashProvider: Using Firebase Database reference');
              return SplashProvider(databaseReference: databaseRef);
            } else {
              debugPrint('SplashProvider: No database reference (Firebase may not be initialized)');
              return SplashProvider(databaseReference: null);
            }
          } catch (e, stackTrace) {
            debugPrint('Error creating SplashProvider: $e');
            debugPrint('Stack: $stackTrace');
            // Always return a provider, even if there's an error
            return SplashProvider(databaseReference: null);
          }
        }),
          ChangeNotifierProvider(create: (context) => LoginProvider(apiResponse: ApiResponse())),
          ChangeNotifierProvider(create: (context) => SingUpProvider()),
          ChangeNotifierProvider(create: (context) => MainHomeProvider()),
          ChangeNotifierProvider(create: (context) => HomeProvider()),
          ChangeNotifierProvider(create: (context) => PopularCourseProvider()),
          ChangeNotifierProvider(create: (context) => CourseDetailProvider()),
          ChangeNotifierProvider(create: (context) => SearchProvider()),
          ChangeNotifierProvider(create: (context) => PurchaseCourseProvider()),
          ChangeNotifierProvider(create: (context) => PurchaseCourseDetailProvider()),
          ChangeNotifierProvider(create: (context) => QuizProvider()),
          ChangeNotifierProvider(create: (context) => ProfileProvider()),
          ChangeNotifierProvider(create: (context) => CertificateProvider()),
          ChangeNotifierProvider(create: (context) => DownloadCertificateProvider()),
          ChangeNotifierProvider(create: (context) => UpdateProfileProvider()),
          ChangeNotifierProvider(create: (context) => PurchaseLoginProvider()),
          ChangeNotifierProvider(create: (context) => CourseVerifyDoneProvider()),
          ChangeNotifierProvider(create: (context) => BlogProvider()),
          ChangeNotifierProvider(create: (context) => PaymentProvider()),
          ChangeNotifierProvider(create: (context) => LiveSessionProvider()),
          ChangeNotifierProvider(create: (context) => AllCoursesProvider()),
        ],
        child: const MyApp(),
      ),
    );
  
  debugPrint('=== APP STARTED ===');
}

final GlobalKey<NavigatorState> navigatorKey = GlobalKey<NavigatorState>();

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    debugPrint('MyApp.build() called');
    return ScreenUtilInit(
        designSize: const Size(360, 690),
        minTextAdapt: true,
        splitScreenMode: true,
        builder: (_, child) {
          debugPrint('ScreenUtilInit builder called');
          return MaterialApp(
            navigatorKey: navigatorKey,
            debugShowCheckedModeBanner: false,
            title: CommonString.app_name,
            theme: ThemeData(
              useMaterial3: true,
              scaffoldBackgroundColor: CommonColor.bg_main,
              primaryColor: CommonColor.primary,
              // Define the ColorScheme explicitly
              colorScheme: ColorScheme.fromSeed(
                seedColor: CommonColor.primary,
                primary: CommonColor.primary,
                secondary: CommonColor.secondary,
                surface: CommonColor.bg_main,
                onSurface: CommonColor.text_primary,
                onPrimary: CommonColor.white, // Text on Orange button should be white
              ),
              // Ensure app bar and status bar are transparent
              appBarTheme: AppBarTheme(
                backgroundColor: Colors.transparent,
                elevation: 0,
                systemOverlayStyle: SystemUiOverlayStyle(
                  statusBarColor: Colors.transparent,
                  statusBarIconBrightness: Brightness.dark,
                  systemNavigationBarColor: Colors.transparent,
                  systemNavigationBarIconBrightness: Brightness.dark,
                ),
              ),
              fontFamily: 'Manrope', // Unified Font
              textTheme: TextTheme(
                displayLarge: TextStyle(fontFamily: 'Manrope', color: CommonColor.text_primary, fontWeight: FontWeight.bold),
                displayMedium: TextStyle(fontFamily: 'Manrope', color: CommonColor.text_primary, fontWeight: FontWeight.bold),
                displaySmall: TextStyle(fontFamily: 'Manrope', color: CommonColor.text_primary, fontWeight: FontWeight.bold),
                headlineLarge: TextStyle(fontFamily: 'Manrope', color: CommonColor.text_primary, fontWeight: FontWeight.bold),
                headlineMedium: TextStyle(fontFamily: 'Manrope', color: CommonColor.text_primary, fontWeight: FontWeight.bold),
                headlineSmall: TextStyle(fontFamily: 'Manrope', color: CommonColor.text_primary, fontWeight: FontWeight.bold),
                titleLarge: TextStyle(fontFamily: 'Manrope', color: CommonColor.text_primary, fontWeight: FontWeight.bold),
                titleMedium: TextStyle(fontFamily: 'Manrope', color: CommonColor.text_primary, fontWeight: FontWeight.bold),
                bodyLarge: TextStyle(fontFamily: 'Manrope', color: CommonColor.text_primary),
                bodyMedium: TextStyle(fontFamily: 'Manrope', color: CommonColor.text_secondary),
              ),
              elevatedButtonTheme: ElevatedButtonThemeData(
                style: ElevatedButton.styleFrom(
                  backgroundColor: CommonColor.primary,
                  foregroundColor: CommonColor.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(30)),
                  padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                  textStyle: TextStyle(fontFamily: 'Manrope', fontWeight: FontWeight.bold, fontSize: 16),
                ),
              ),
            ),
            home: const SplashScreen(),
          );
        });
  }
}
