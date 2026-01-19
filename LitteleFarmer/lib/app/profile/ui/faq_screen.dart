import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/profile/model/faq_data.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/constant.dart';

class FaqScreen extends StatefulWidget {
  const FaqScreen({super.key});

  @override
  State<FaqScreen> createState() => _FaqScreenState();
}

class _FaqScreenState extends State<FaqScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
        backgroundColor: CommonColor.bg_main,
        extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Column(
          children: [
            Container(
              margin: EdgeInsets.all(10.h),
              child: Row(
                children: [
                  InkWell(
                    onTap: () {
                      Navigator.pop(context);
                    },
                    child: Container(
                      height: 40.h,
                      width: 40.h,
                      padding: EdgeInsets.all(10.h),
                      decoration: BoxDecoration(
                          color: CommonColor.white,
                          borderRadius: BorderRadius.circular(50.h)),
                      child: SvgPicture.asset(CommonImage.ic_back,
                          fit: BoxFit.cover,
                          colorFilter: ColorFilter.mode(
                              CommonColor.black, BlendMode.srcIn)),
                    ),
                  ),
                  Expanded(
                    child: Text(
                      "Frequently Asked Questions",
                      textAlign: TextAlign.center,
                      style: TextStyle(
                          color: CommonColor.black,
                          fontSize: 18.sp,
                          fontWeight: FontWeight.w900,
                          fontFamily: Constant.manrope),
                    ),
                  ),
                  SizedBox(width: 40.h), // Balance the back button
                ],
              ),
            ),
            Expanded(
              child: ListView.builder(
                padding: EdgeInsets.symmetric(horizontal: 10.w, vertical: 10.h),
                itemCount: FaqData.questions.length,
                itemBuilder: (context, index) {
                  return Container(
                    margin: EdgeInsets.only(bottom: 10.h),
                    decoration: BoxDecoration(
                      color: CommonColor.white,
                      borderRadius: BorderRadius.circular(10.h),
                    ),
                    child: Theme(
                      data: Theme.of(context)
                          .copyWith(dividerColor: Colors.transparent),
                      child: ExpansionTile(
                        title: Text(
                          FaqData.questions[index]["question"]!,
                          style: TextStyle(
                              color: CommonColor.black,
                              fontSize: 14.sp,
                              fontWeight: FontWeight.bold,
                              fontFamily: Constant.manrope),
                        ),
                        children: [
                          Padding(
                            padding: EdgeInsets.fromLTRB(16.w, 0, 16.w, 16.h),
                            child: Text(
                              FaqData.questions[index]["answer"]!,
                              style: TextStyle(
                                  color: CommonColor.black,
                                  fontSize: 13.sp,
                                  height: 1.5,
                                  fontWeight: FontWeight.normal,
                                  fontFamily: Constant.manrope),
                            ),
                          ),
                        ],
                      ),
                    ),
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }
}
