import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:little_farmer/app/main_home/ui/main_home_screen.dart';
import 'package:little_farmer/app/purchase_login/provider/purchase_login_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:provider/provider.dart';

class PurchaseLoginScreen extends StatefulWidget {
  const PurchaseLoginScreen({super.key});

  @override
  State<PurchaseLoginScreen> createState() => _PurchaseLoginScreenState();
}

class _PurchaseLoginScreenState extends State<PurchaseLoginScreen> with WidgetsBindingObserver {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    super.dispose();
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    super.didChangeAppLifecycleState(state);
    if (state == AppLifecycleState.resumed) {
      Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => MainHomeScreen()));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: CommonColor.bg_main,
      extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Consumer(
        builder: (context, PurchaseLoginProvider provider, _) {
          return Container(
            margin: EdgeInsets.all(10.h),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Image.asset(CommonImage.img_payment_failed, height: 350.h, width: MediaQuery.of(context).size.width, fit: BoxFit.cover),
                SizedBox(height: 10.h),
                Text(
                  CommonString.payment_fail,
                  textAlign: TextAlign.center,
                  style: TextStyle(color: CommonColor.black, fontSize: 22.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                ),
                SizedBox(height: 10.h),
                Text(
                  CommonString.login_into_web,
                  textAlign: TextAlign.center,
                  style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                ),
                SizedBox(height: 50.h),
                Container(
                  height: 45.h,
                  width: MediaQuery.of(context).size.width,
                  alignment: Alignment.center,
                  decoration: BoxDecoration(color: CommonColor.bg_button, borderRadius: BorderRadius.circular(10.h)),
                  child: Material(
                    color: CommonColor.transparent,
                    child: InkWell(
                      borderRadius: BorderRadius.circular(10.h),
                      splashColor: CommonColor.rippleColor,
                      onTap: () {
                        provider.launchArticleUrl(context: context);
                      },
                      child: Container(
                        height: double.infinity,
                        width: MediaQuery.of(context).size.width,
                        alignment: Alignment.center,
                        child: Text(CommonString.login_to_web, style: TextStyle(color: CommonColor.white, fontSize: 18.sp, fontWeight: FontWeight.w500, fontFamily: Constant.manrope)),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          );
        },
      ),
    ));
  }
}
