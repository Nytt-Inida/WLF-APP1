import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_device_type/flutter_device_type.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:little_farmer/main.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';

class Utils {
  static Timer? timer;
  static List<String> recentTaskIdList = [];

  static bool isTablet({required BuildContext context}) {
    Device device = Device.get();
    if (device.isTablet) {
      return true;
    } else {
      return false;
    }
  }

  static void showSnackbarMessage({required String message}) {
    final context = navigatorKey.currentState?.context;
    if (context != null) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(message), duration: const Duration(seconds: 2)));
    }
  }

  static bool isStrongPassword(String password) {
    final bool hasUppercase = password.contains(RegExp(r'[A-Z]'));
    final bool hasLowercase = password.contains(RegExp(r'[a-z]'));
    final bool hasDigits = password.contains(RegExp(r'[0-9]'));
    final bool hasSpecialCharacters = password.contains(RegExp(r'[!@#$%^&*(),.?":{}|<>]'));
    final bool hasMinLength = password.length >= 8;

    return hasUppercase && hasLowercase && hasDigits && hasSpecialCharacters && hasMinLength;
  }

  static bool isEmailValid(String email) {
    final RegExp emailRegex = RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$');
    return emailRegex.hasMatch(email);
  }

  static bool isValidAge(String s) {
    final RegExp _numeric = RegExp(r'^[0-9]+$');
    return _numeric.hasMatch(s);
  }

  static Future<bool> showExitConfirmationDialog({required BuildContext context}) async {
    return await showDialog(
            context: context,
            builder: (BuildContext context) {
              return Dialog(
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15.w)),
                child: DecoratedBox(
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(15.w),
                    color: CommonColor.white,
                  ),
                  child: Container(
                    padding: EdgeInsets.all(15.w),
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(CommonString.message_exit_app, style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15.sp, color: CommonColor.black, fontFamily: Constant.manrope)),
                        SizedBox(height: 15.h),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.end,
                          children: [
                            GestureDetector(
                              onTap: () {
                                Navigator.of(context).pop();
                              },
                              child: Text(CommonString.cancel.toUpperCase(), style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15.sp, color: CommonColor.black, fontFamily: Constant.manrope)),
                            ),
                            SizedBox(width: 15.w),
                            GestureDetector(
                              onTap: () async {
                                Navigator.of(context).pop();
                                Future.delayed(const Duration(milliseconds: 10), () {
                                  SystemNavigator.pop();
                                });
                              },
                              child: Text(CommonString.exit.toUpperCase(), style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15.sp, color: CommonColor.bg_button, fontFamily: Constant.manrope)),
                            )
                          ],
                        )
                      ],
                    ),
                  ),
                ),
              );
            }) ??
        false;
  }

  static Future<bool?> showLogoutConfirmationDialog({required BuildContext context}) async {
    return await showDialog<bool>(
            context: context,
            builder: (BuildContext context) {
              return Dialog(
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15.w)),
                child: DecoratedBox(
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(15.w),
                    color: CommonColor.white,
                  ),
                  child: Container(
                    padding: EdgeInsets.all(15.w),
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text("Are you sure you want to logout?", style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15.sp, color: CommonColor.black, fontFamily: Constant.manrope)),
                        SizedBox(height: 15.h),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.end,
                          children: [
                            GestureDetector(
                              onTap: () {
                                Navigator.of(context).pop(false);
                              },
                              child: Text(CommonString.cancel.toUpperCase(), style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15.sp, color: CommonColor.black, fontFamily: Constant.manrope)),
                            ),
                            SizedBox(width: 15.w),
                            GestureDetector(
                              onTap: () {
                                Navigator.of(context).pop(true);
                              },
                              child: Text(CommonString.confirm.toUpperCase(), style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15.sp, color: CommonColor.bg_button, fontFamily: Constant.manrope)),
                            )
                          ],
                        )
                      ],
                    ),
                  ),
                ),
              );
            });
  }

  static Future<bool> showCompleteRemainLessonDialog({required BuildContext context}) async {
    return await showDialog(
            context: context,
            builder: (BuildContext context) {
              return Dialog(
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15.w)),
                child: DecoratedBox(
                  decoration: BoxDecoration(borderRadius: BorderRadius.circular(15.w), color: CommonColor.white),
                  child: Container(
                    padding: EdgeInsets.all(15.w),
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(CommonString.message_complete_lesson_first, style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15.sp, color: CommonColor.black, fontFamily: Constant.manrope)),
                        SizedBox(height: 15.h),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.end,
                          children: [
                            GestureDetector(
                              onTap: () async {
                                Navigator.of(context).pop();
                              },
                              child: Text(CommonString.okay.toUpperCase(), style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15.sp, color: CommonColor.bg_button, fontFamily: Constant.manrope)),
                            )
                          ],
                        )
                      ],
                    ),
                  ),
                ),
              );
            }) ??
        false;
  }
  static Future<void> showAlertDialog({required String message, String title = "Message", VoidCallback? onOk}) async {
    final context = navigatorKey.currentState?.context;
    if (context != null) {
      await showDialog(
        context: context,
        builder: (context) => AlertDialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15.r)),
          title: Text(title, style: TextStyle(fontFamily: Constant.manrope, fontWeight: FontWeight.bold, fontSize: 18.sp)),
          content: Text(message, style: TextStyle(fontFamily: Constant.manrope, fontSize: 14.sp)),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.pop(context);
                if (onOk != null) onOk();
              },
              child: Text(CommonString.okay, style: TextStyle(fontFamily: Constant.manrope, color: CommonColor.bg_button, fontWeight: FontWeight.bold)),
            )
          ],
        ),
      );
    }
  }
}
