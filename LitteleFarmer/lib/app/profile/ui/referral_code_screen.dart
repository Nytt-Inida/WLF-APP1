import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:little_farmer/app/profile/provider/profile_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';
import 'package:share_plus/share_plus.dart';

class ReferralCodeScreen extends StatefulWidget {
  const ReferralCodeScreen({super.key});

  @override
  State<ReferralCodeScreen> createState() => _ReferralCodeScreenState();
}

class _ReferralCodeScreenState extends State<ReferralCodeScreen> {
  bool _hasFetchedData = false;

  @override
  void initState() {
    super.initState();
    // Fetch profile data to get referral status and code (only once)
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (!_hasFetchedData) {
        _hasFetchedData = true;
        Provider.of<ProfileProvider>(context, listen: false).fetchProfileData();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        backgroundColor: CommonColor.bg_main,
        extendBodyBehindAppBar: false,
        appBar: AppBar(
          title: Text(
            "Referral Code",
            style: TextStyle(
              color: CommonColor.black,
              fontSize: 18.sp,
              fontWeight: FontWeight.bold,
              fontFamily: Constant.manrope,
            ),
          ),
          backgroundColor: CommonColor.white,
          elevation: 0,
          leading: IconButton(
            icon: Icon(Icons.arrow_back, color: CommonColor.black),
            onPressed: () => Navigator.pop(context),
          ),
        ),
        body: SafeArea(
          child: Selector<ProfileProvider, bool>(
            selector: (_, provider) => provider.isFetchingProfile,
            builder: (context, isFetching, _) {
            if (isFetching) {
              return Center(
                child: CircularProgressIndicator(color: CommonColor.bg_button),
              );
            }
            return Consumer<ProfileProvider>(
              builder: (context, provider, _) {
                // Check if referral is enabled
                if (!provider.isReferralEnabled) {
                  return _buildDisabledView();
                }

                // Show referral code if enabled
                return SingleChildScrollView(
              padding: EdgeInsets.all(20.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  SizedBox(height: 20.h),
                  // Illustration/Image
                  Container(
                    width: 200.w,
                    height: 200.h,
                    decoration: BoxDecoration(
                      color: Colors.green.shade50,
                      borderRadius: BorderRadius.circular(20.r),
                    ),
                    child: Icon(
                      Icons.card_giftcard,
                      size: 100.h,
                      color: Colors.green.shade300,
                    ),
                  ),
                  SizedBox(height: 30.h),
                  Text(
                    "Share Your Referral Code",
                    style: TextStyle(
                      color: CommonColor.black,
                      fontSize: 20.sp,
                      fontWeight: FontWeight.bold,
                      fontFamily: Constant.manrope,
                    ),
                    textAlign: TextAlign.center,
                  ),
                  SizedBox(height: 10.h),
                  Padding(
                    padding: EdgeInsets.symmetric(horizontal: 20.w),
                    child: Text(
                      "Share your referral code with friends! When they sign up and purchase a course, you get a reward coupon.",
                      style: TextStyle(
                        color: Colors.grey.shade700,
                        fontSize: 14.sp,
                        fontFamily: Constant.manrope,
                        height: 1.5,
                      ),
                      textAlign: TextAlign.center,
                    ),
                  ),
                  SizedBox(height: 40.h),
                  // Referral Code Display
                  Container(
                    padding: EdgeInsets.all(20.w),
                    decoration: BoxDecoration(
                      color: CommonColor.white,
                      borderRadius: BorderRadius.circular(15.r),
                      border: Border.all(
                        color: Colors.green.shade300,
                        width: 2,
                        style: BorderStyle.solid,
                      ),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.05),
                          blurRadius: 10,
                          offset: Offset(0, 5),
                        ),
                      ],
                    ),
                    child: Column(
                      children: [
                        Text(
                          "Your Referral Code",
                          style: TextStyle(
                            color: Colors.grey.shade600,
                            fontSize: 12.sp,
                            fontFamily: Constant.manrope,
                          ),
                        ),
                        SizedBox(height: 10.h),
                        Text(
                          provider.referralCode.isEmpty ? "Loading..." : provider.referralCode,
                          style: TextStyle(
                            color: Colors.green.shade700,
                            fontSize: 28.sp,
                            fontWeight: FontWeight.bold,
                            fontFamily: Constant.manrope,
                            letterSpacing: 2,
                          ),
                        ),
                        SizedBox(height: 20.h),
                        SizedBox(
                          width: double.infinity,
                          child: ElevatedButton.icon(
                            onPressed: provider.referralCode.isEmpty
                                ? null
                                : () async {
                                    await Clipboard.setData(
                                      ClipboardData(text: provider.referralCode),
                                    );
                                    Utils.showSnackbarMessage(
                                      message: "Referral code copied to clipboard!",
                                    );
                                  },
                            icon: Icon(Icons.copy, color: Colors.white, size: 20.h),
                            label: Text(
                              "Copy Code",
                              style: TextStyle(
                                color: Colors.white,
                                fontSize: 16.sp,
                                fontFamily: Constant.manrope,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: CommonColor.bg_button,
                              padding: EdgeInsets.symmetric(vertical: 15.h),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(10.r),
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                  SizedBox(height: 30.h),
                  // Referral Link Section
                  Text(
                    "Or share this link:",
                    style: TextStyle(
                      color: CommonColor.black,
                      fontSize: 14.sp,
                      fontWeight: FontWeight.w600,
                      fontFamily: Constant.manrope,
                    ),
                  ),
                  SizedBox(height: 15.h),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton.icon(
                      onPressed: provider.referralCode.isEmpty
                          ? null
                          : () async {
                              final referralLink =
                                  "https://welittlefarmers.com/signup?ref=${provider.referralCode}";
                              await Clipboard.setData(
                                ClipboardData(text: referralLink),
                              );
                              Utils.showSnackbarMessage(
                                message: "Referral link copied to clipboard!",
                              );
                            },
                      icon: Icon(Icons.link, color: Colors.white, size: 20.h),
                      label: Text(
                        "Copy Referral Link",
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 16.sp,
                          fontFamily: Constant.manrope,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.blue.shade600,
                        padding: EdgeInsets.symmetric(vertical: 15.h),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(10.r),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: 20.h),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton.icon(
                      onPressed: provider.referralCode.isEmpty
                          ? null
                          : () async {
                              final referralLink =
                                  "https://welittlefarmers.com/signup?ref=${provider.referralCode}";
                              final shareText =
                                  "Join Little Farmers Academy using my referral code: ${provider.referralCode}\n\nGet amazing courses on farming, agriculture, and sustainability!\n\n$referralLink";
                              await Share.share(shareText, subject: 'Join Little Farmers Academy');
                            },
                      icon: Icon(Icons.share, color: Colors.white, size: 20.h),
                      label: Text(
                        "Share via",
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 16.sp,
                          fontFamily: Constant.manrope,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.green.shade600,
                        padding: EdgeInsets.symmetric(vertical: 15.h),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(10.r),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(height: 40.h),
                ],
              ),
            );
              },
            );
          },
        ),
        ),
      );
  }

  Widget _buildDisabledView() {
    return SingleChildScrollView(
      padding: EdgeInsets.all(20.w),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          SizedBox(height: 40.h),
          // Illustration
          Container(
            width: 200.w,
            height: 200.h,
            decoration: BoxDecoration(
              color: Colors.grey.shade100,
              borderRadius: BorderRadius.circular(20.r),
            ),
            child: Icon(
              Icons.info_outline,
              size: 100.h,
              color: Colors.grey.shade400,
            ),
          ),
          SizedBox(height: 40.h),
          Container(
            padding: EdgeInsets.all(20.w),
            decoration: BoxDecoration(
              color: Colors.orange.shade50,
              borderRadius: BorderRadius.circular(15.r),
              border: Border.all(
                color: Colors.orange.shade300,
                width: 2,
              ),
            ),
            child: Column(
              children: [
                Icon(
                  Icons.info,
                  size: 40.h,
                  color: Colors.orange.shade700,
                ),
                SizedBox(height: 15.h),
                Text(
                  "Referral System Currently Disabled",
                  style: TextStyle(
                    color: Colors.orange.shade900,
                    fontSize: 18.sp,
                    fontWeight: FontWeight.bold,
                    fontFamily: Constant.manrope,
                  ),
                  textAlign: TextAlign.center,
                ),
                SizedBox(height: 10.h),
                Text(
                  "Currently no referral bonus is active. The referral system has been disabled by the administrator. Please check back later or contact support for more information.",
                  style: TextStyle(
                    color: Colors.orange.shade800,
                    fontSize: 14.sp,
                    fontFamily: Constant.manrope,
                    height: 1.5,
                  ),
                  textAlign: TextAlign.center,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
