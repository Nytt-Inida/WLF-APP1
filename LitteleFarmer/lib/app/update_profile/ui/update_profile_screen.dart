import 'dart:ui';

import 'package:country_picker/country_picker.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/update_profile/provider/update_profile_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:provider/provider.dart';

class UpdateProfileScreen extends StatefulWidget {
  const UpdateProfileScreen({super.key});

  @override
  State<UpdateProfileScreen> createState() => _UpdateProfileScreenState();
}

class _UpdateProfileScreenState extends State<UpdateProfileScreen> {
  late UpdateProfileProvider provider;

  @override
  void initState() {
    super.initState();
    provider = Provider.of<UpdateProfileProvider>(context, listen: false);
    provider.assignValue();
  }

  @override
  void dispose() {
    provider.resetProvider();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: CommonColor.bg_main,
      extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Consumer(
        builder: (context, UpdateProfileProvider provider, _) {
          return Stack(
            children: [
              SingleChildScrollView(
                child: Container(
                  margin: EdgeInsets.all(10.h),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SizedBox(height: 20.h),
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.center,
                        children: [
                          InkWell(
                            onTap: () {
                              Navigator.of(context).pop(false);
                            },
                            child: SvgPicture.asset(CommonImage.ic_back, height: 20.h, width: 20.h, fit: BoxFit.cover, color: CommonColor.black),
                          ),
                          SizedBox(width: 10.w),
                          Text(
                            CommonString.update_profile,
                            style: TextStyle(fontWeight: FontWeight.bold, color: CommonColor.black, fontSize: 20.sp, fontFamily: Constant.manrope),
                          ),
                        ],
                      ),
                      SizedBox(height: 30.h),
                      Text(
                        CommonString.username,
                        style: TextStyle(fontWeight: FontWeight.normal, color: CommonColor.black, fontSize: 12.sp, fontFamily: Constant.manrope),
                      ),
                      SizedBox(height: 3.h),
                      Container(
                        decoration: BoxDecoration(color: CommonColor.white, borderRadius: BorderRadius.circular(5.h)),
                        child: TextField(
                          controller: provider.userNameController,
                          cursorColor: CommonColor.black,
                          cursorHeight: 20.h,
                          keyboardType: TextInputType.text,
                          textInputAction: TextInputAction.next,
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
                      Text(
                        CommonString.email,
                        style: TextStyle(fontWeight: FontWeight.normal, color: CommonColor.black, fontSize: 12.sp, fontFamily: Constant.manrope),
                      ),
                      SizedBox(height: 3.h),
                      Container(
                        decoration: BoxDecoration(color: CommonColor.white, borderRadius: BorderRadius.circular(5.h)),
                        child: TextField(
                          controller: provider.emailController,
                          cursorColor: CommonColor.black,
                          keyboardType: TextInputType.emailAddress,
                          textInputAction: TextInputAction.next,
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
                      Text(
                        CommonString.school_name,
                        style: TextStyle(fontWeight: FontWeight.normal, color: CommonColor.black, fontSize: 12.sp, fontFamily: Constant.manrope),
                      ),
                      SizedBox(height: 3.h),
                      Container(
                        decoration: BoxDecoration(color: CommonColor.white, borderRadius: BorderRadius.circular(5.h)),
                        child: TextField(
                          controller: provider.schoolNameController,
                          cursorColor: CommonColor.black,
                          keyboardType: TextInputType.text,
                          textInputAction: TextInputAction.next,
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
                      Text(
                        CommonString.country,
                        style: TextStyle(fontWeight: FontWeight.normal, color: CommonColor.black, fontSize: 12.sp, fontFamily: Constant.manrope),
                      ),
                      SizedBox(height: 3.h),
                      InkWell(
                        onTap: () {
                          showCountryPicker(
                            context: context,
                            countryListTheme: CountryListThemeData(
                              flagSize: 25,
                              backgroundColor: CommonColor.white,
                              textStyle: TextStyle(fontSize: 14.sp, color: Colors.grey, fontFamily: Constant.manrope),
                              bottomSheetHeight: 500,
                              borderRadius: BorderRadius.only(topLeft: Radius.circular(20.0), topRight: Radius.circular(20.0)),
                              inputDecoration: InputDecoration(
                                hintText: CommonString.country,
                                hintStyle: TextStyle(fontSize: 14.sp, color: Colors.grey, fontFamily: Constant.manrope),
                                prefixIcon: const Icon(Icons.search),
                                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10.w), borderSide: BorderSide(color: CommonColor.et_border_grey)),
                              ),
                            ),
                            onSelect: (Country country) {
                              setState(() {
                                List<String> countryList = country.displayName.split("(");
                                provider.country = countryList[0];
                              });
                            },
                          );
                        },
                        child: Container(
                          width: MediaQuery.of(context).size.width,
                          height: 40.h,
                          alignment: Alignment.centerLeft,
                          padding: EdgeInsets.only(left: 16.w),
                          decoration: BoxDecoration(color: CommonColor.white, borderRadius: BorderRadius.circular(5.h), border: Border.all(color: CommonColor.et_border_grey)),
                          child: Text(
                            provider.country,
                            style: TextStyle(color: CommonColor.black, fontSize: 15.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                          ),
                        ),
                      ),
                      SizedBox(height: 20.h),
                      Text(
                        CommonString.ages,
                        style: TextStyle(fontWeight: FontWeight.normal, color: CommonColor.black, fontSize: 12.sp, fontFamily: Constant.manrope),
                      ),
                      SizedBox(height: 3.h),
                      Container(
                        decoration: BoxDecoration(color: CommonColor.white, borderRadius: BorderRadius.circular(5.h)),
                        child: TextField(
                          controller: provider.ageController,
                          cursorColor: CommonColor.black,
                          keyboardType: TextInputType.number,
                          cursorHeight: 20.h,
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
                      SizedBox(height: 60.h),
                      SizedBox(
                        width: double.infinity,
                        height: 50.h,
                        child: TextButton(
                          onPressed: () {
                            provider.updateProfile(context: context);
                          },
                          style: TextButton.styleFrom(
                            backgroundColor: CommonColor.bg_button,
                            padding: EdgeInsets.all(10.h),
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10.w)),
                          ),
                          child: Text(CommonString.confirm.toUpperCase(), style: TextStyle(color: CommonColor.white, fontSize: 16.sp, fontWeight: FontWeight.w900, fontFamily: Constant.manrope)),
                        ),
                      )
                    ],
                  ),
                ),
              ),
              provider.isApiCalling
                  ? SizedBox(
                      height: MediaQuery.of(context).size.height,
                      width: MediaQuery.of(context).size.width,
                      child: Stack(
                        children: [
                          BackdropFilter(filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10), child: Container(color: CommonColor.white.withOpacity(0.1))),
                          Center(
                            child: CircularProgressIndicator(valueColor: AlwaysStoppedAnimation<Color>(CommonColor.black)),
                          ),
                        ],
                      ))
                  : Container()
            ],
          );
        },
      ),
    ));
  }
}
