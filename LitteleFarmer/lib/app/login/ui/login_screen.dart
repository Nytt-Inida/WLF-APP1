import 'dart:ui';

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:little_farmer/app/login/provider/login_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  late LoginProvider provider;

  @override
  void initState() {
    super.initState();
    provider = Provider.of<LoginProvider>(context, listen: false);
  }

  @override
  void dispose() {
    super.dispose();
    provider.resetProvider();
  }

  @override
  Widget build(BuildContext context) {
    return PopScope(
      canPop: false,
      onPopInvokedWithResult: (didPop, result) async {
        if (didPop) return;
        Utils.showExitConfirmationDialog(context: context);
      },
      child: SafeArea(
          child: Scaffold(
        backgroundColor: CommonColor.bg_main,
        body: Consumer(
          builder: (context, LoginProvider provider, _) {
            return Stack(
              children: [
                SingleChildScrollView(
                  child: Container(
                    margin: EdgeInsets.all(10.h),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        SizedBox(height: 20.h),
                        Text(CommonString.login, style: TextStyle(fontWeight: FontWeight.w900, color: CommonColor.black, fontSize: 20.sp, fontFamily: Constant.manrope)),
                        SizedBox(height: 30.h),
                        Text(CommonString.email, style: TextStyle(fontWeight: FontWeight.normal, color: CommonColor.black, fontSize: 12.sp, fontFamily: Constant.manrope)),
                        SizedBox(height: 3.h),
                        Container(
                          decoration: BoxDecoration(color: CommonColor.white, borderRadius: BorderRadius.circular(5.h)),
                          child: TextField(
                            controller: provider.emailController,
                            cursorColor: CommonColor.black,
                            keyboardType: TextInputType.emailAddress,
                            textInputAction: TextInputAction.done,
                            cursorHeight: 20.h,
                            style: TextStyle(color: CommonColor.black, fontSize: 15.sp, fontWeight: FontWeight.w800, fontFamily: Constant.manrope),
                            decoration: InputDecoration(
                              contentPadding: EdgeInsets.symmetric(vertical: 10.h, horizontal: 16.w),
                              focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10.w), borderSide: BorderSide(color: CommonColor.et_border_grey)),
                              enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10.w), borderSide: BorderSide(color: CommonColor.et_border_grey)),
                              border: OutlineInputBorder(borderSide: BorderSide(color: CommonColor.et_border_grey), borderRadius: BorderRadius.circular(10.w)),
                            ),
                          ),
                        ),
                        SizedBox(height: 20.h),
                        if (provider.isOtpApiCalled)
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(CommonString.otp, style: TextStyle(fontWeight: FontWeight.normal, color: CommonColor.black, fontSize: 12.sp, fontFamily: Constant.manrope)),
                              SizedBox(height: 3.h),
                              Theme(
                                data: Theme.of(context).copyWith(iconTheme: IconThemeData(color: CommonColor.black)),
                                child: Container(
                                  decoration: BoxDecoration(color: CommonColor.white, borderRadius: BorderRadius.circular(5.h)),
                                  child: TextField(
                                    controller: provider.otpController,
                                    keyboardType: TextInputType.text,
                                    cursorColor: CommonColor.black,
                                    readOnly: provider.isOtpNotAvailable,
                                    textInputAction: TextInputAction.done,
                                    style: TextStyle(color: CommonColor.black, fontSize: 15.sp, fontWeight: FontWeight.w800, fontFamily: Constant.manrope),
                                    decoration: InputDecoration(
                                      contentPadding: EdgeInsets.symmetric(vertical: 10.h, horizontal: 16.w),
                                      focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10.w), borderSide: BorderSide(color: CommonColor.et_border_grey)),
                                      enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10.w), borderSide: BorderSide(color: CommonColor.et_border_grey)),
                                      border: OutlineInputBorder(borderSide: BorderSide(color: CommonColor.et_border_grey), borderRadius: BorderRadius.circular(10.w)),
                                    ),
                                  ),
                                ),
                              ),
                            ],
                          ),
                        SizedBox(height: 25.h),
                        if (provider.isOtpApiCalled)
                          Text(
                            CommonString.message_otp_send_on_mail,
                            style: TextStyle(fontWeight: FontWeight.normal, color: CommonColor.black, fontSize: 12.sp, fontFamily: Constant.manrope),
                          ),
                        Container(
                          width: double.infinity,
                          height: 50.h,
                          child: TextButton(
                            onPressed: () {
                              if (!provider.isApiCalling) {
                                if (provider.isOtpApiCalled) {
                                  provider.onLogin(context: context);
                                } else {
                                  provider.sendOtp(context: context);
                                }
                              }
                            },
                            style: TextButton.styleFrom(
                              backgroundColor: CommonColor.bg_button,
                              padding: EdgeInsets.all(10.h),
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10.w)),
                            ),
                            child: Text(
                              provider.isOtpApiCalled ? CommonString.login.toUpperCase() : CommonString.get_otp.toUpperCase(),
                              style: TextStyle(color: CommonColor.white, fontSize: 16.sp, fontWeight: FontWeight.w900, fontFamily: Constant.manrope),
                            ),
                          ),
                        ),
                        SizedBox(height: 20.h),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Flexible(
                              child: Text(
                                CommonString.noAccount,
                                style: TextStyle(color: CommonColor.black, fontWeight: FontWeight.bold, fontSize: 13.sp, fontFamily: Constant.manrope),
                                overflow: TextOverflow.ellipsis,
                              ),
                            ),
                            SizedBox(width: 5.w),
                            GestureDetector(
                              onTap: () {
                                provider.gotoSignupScreen(context: context);
                              },
                              child: Text(
                                CommonString.registerNow,
                                style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold, fontSize: 13.sp, fontFamily: Constant.manrope),
                              ),
                            ),
                          ],
                        )
                      ],
                    ),
                  ),
                ),
                if (provider.isApiCalling)
                  SizedBox(
                      height: MediaQuery.of(context).size.height,
                      width: MediaQuery.of(context).size.width,
                      child: Stack(
                        children: [
                          BackdropFilter(filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10), child: Container(color: CommonColor.white.withAlpha(10))),
                          Center(child: CircularProgressIndicator(valueColor: AlwaysStoppedAnimation<Color>(CommonColor.black))),
                        ],
                      ))
              ],
            );
          },
        ),
      )),
    );
  }
}
