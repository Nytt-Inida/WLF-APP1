import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/splash/provider/splash_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:provider/provider.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    debugPrint('SplashScreen initState called');
    
    // Use the provider from Provider tree, not create a new one
    // Use a delayed callback to ensure widget is fully mounted and context is valid
    WidgetsBinding.instance.addPostFrameCallback((_) {
      debugPrint('SplashScreen postFrameCallback called');
      
      // Add a small delay to ensure widget is fully mounted during cold start
      Future.delayed(const Duration(milliseconds: 300), () {
        if (!mounted) {
          debugPrint('SplashScreen not mounted, aborting initialization');
          return;
        }
        
        debugPrint('SplashScreen initializing provider');
        try {
          final provider = Provider.of<SplashProvider>(context, listen: false);
          debugPrint('SplashProvider obtained, calling getApi()');
          provider.getApi();
          debugPrint('getApi() called, calling gotoMainScreen()');
          // Navigate after ensuring everything is ready
          provider.gotoMainScreen(context: context);
        } catch (e, stackTrace) {
          debugPrint('Error in splash screen initialization: $e');
          debugPrint('Stack trace: $stackTrace');
          // If navigation fails, try again after a delay
          Future.delayed(const Duration(seconds: 2), () {
            if (!mounted) {
              debugPrint('SplashScreen not mounted during retry');
              return;
            }
            try {
              debugPrint('Retrying splash screen initialization');
              final provider = Provider.of<SplashProvider>(context, listen: false);
              provider.gotoMainScreen(context: context);
            } catch (e2, stackTrace2) {
              debugPrint('Error retrying navigation: $e2');
              debugPrint('Stack trace: $stackTrace2');
            }
          });
        }
      });
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: CommonColor.bg_main,
      extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Consumer(
        builder: (context, SplashProvider provider, _) {
          return Container(
            height: MediaQuery.of(context).size.height,
            width: MediaQuery.of(context).size.width,
            alignment: Alignment.center,
            margin: EdgeInsets.symmetric(horizontal: 20.w),
            child: SvgPicture.asset(CommonImage.app_logo, height: 150.h, fit: BoxFit.contain),
          );
        },
        ),
      ),
    );
  }
}
