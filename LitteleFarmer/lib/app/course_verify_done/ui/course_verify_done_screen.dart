import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:little_farmer/app/course_verify_done/provider/course_verify_done_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:provider/provider.dart';

class CourseVerifyDoneScreen extends StatefulWidget {
  const CourseVerifyDoneScreen({super.key});

  @override
  State<CourseVerifyDoneScreen> createState() => _CourseVerifyDoneScreenState();
}

class _CourseVerifyDoneScreenState extends State<CourseVerifyDoneScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: CommonColor.bg_main,
      extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Consumer(
        builder: (context, CourseVerifyDoneProvider provider, _) {
          return Container(
            margin: EdgeInsets.all(10.h),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Image.asset(CommonImage.img_payment_success, height: 350.h, width: MediaQuery.of(context).size.width, fit: BoxFit.cover),
                SizedBox(height: 10.h),
                Text(
                  CommonString.congratulation_enjoy_class,
                  textAlign: TextAlign.center,
                  style: TextStyle(color: CommonColor.black, fontSize: 22.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                ),
                SizedBox(height: 50.h),
                Container(
                  height: 45.h,
                  width: MediaQuery.of(context).size.width,
                  alignment: Alignment.center,
                  decoration: BoxDecoration(color: CommonColor.bg_button, borderRadius: BorderRadius.circular(10.h)),
                  child: Material(
                    color: Colors.transparent,
                    child: InkWell(
                      borderRadius: BorderRadius.circular(10.h),
                      splashColor: CommonColor.rippleColor,
                      onTap: () {
                        provider.gotoMainScreen(context: context);
                      },
                      child: Container(
                        height: double.infinity,
                        width: MediaQuery.of(context).size.width,
                        alignment: Alignment.center,
                        child: Text(CommonString.go_to_home_screen, style: TextStyle(color: CommonColor.white, fontSize: 18.sp, fontWeight: FontWeight.w500, fontFamily: Constant.manrope)),
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
