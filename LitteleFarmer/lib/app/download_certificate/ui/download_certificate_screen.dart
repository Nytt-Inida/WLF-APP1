import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/download_certificate/provider/download_certificate_provider.dart';
import 'package:little_farmer/utils/SharedPreferencesUtil.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';

// ignore: must_be_immutable
class DownloadCertificateScreen extends StatefulWidget {
  String title;
  DownloadCertificateScreen({super.key, required this.title});

  @override
  State<DownloadCertificateScreen> createState() => _DownloadCertificateScreenState();
}

class _DownloadCertificateScreenState extends State<DownloadCertificateScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: CommonColor.bg_main,
      extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Consumer(
        builder: (context, DownloadCertificateProvider provider, _) {
          return Stack(
            children: [
              Container(height: (MediaQuery.of(context).size.height / 2.5), color: CommonColor.bg_button),
              SingleChildScrollView(
                child: Container(
                  margin: EdgeInsets.all(10.h),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SizedBox(height: 10.h),
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.center,
                        children: [
                          InkWell(
                            onTap: () {
                              Navigator.of(context).pop();
                            },
                            child: Container(
                              height: 24.h,
                              width: 24.h,
                              padding: EdgeInsets.only(top: 5.h, bottom: 5.h, right: 18.w),
                              child: SvgPicture.asset(CommonImage.ic_back, fit: BoxFit.fill),
                            ),
                          ),
                          Flexible(
                            child: Text(
                              widget.title,
                              style: TextStyle(fontWeight: FontWeight.w900, color: CommonColor.black, fontSize: 15.sp, fontFamily: Constant.manrope),
                              overflow: TextOverflow.ellipsis,
                              maxLines: 2,
                            ),
                          ),
                        ],
                      ),
                      SizedBox(height: 30.h),
                      Container(
                        decoration: BoxDecoration(color: CommonColor.white, borderRadius: BorderRadius.circular(10.h), boxShadow: [
                          BoxShadow(color: CommonColor.grey.withAlpha(50), spreadRadius: 5, blurRadius: 7, offset: Offset(0, 3)),
                        ]),
                        child: ClipRRect(
                          borderRadius: BorderRadius.circular(10.h),
                          child: Stack(
                            children: [
                              // Certificate background image from backend (matching website)
                              CachedNetworkImage(
                                imageUrl: 'https://welittlefarmers.com/assets/img/certificate_background.png',
                                width: double.infinity,
                                height: 450.h,
                                fit: BoxFit.contain,
                                placeholder: (context, url) => Container(
                                  height: 450.h,
                                  color: CommonColor.grey.withOpacity(0.2),
                                  child: Center(child: CircularProgressIndicator()),
                                ),
                                errorWidget: (context, url, error) => Container(
                                  height: 450.h,
                                  color: CommonColor.grey.withOpacity(0.2),
                                  child: Center(child: Icon(Icons.error)),
                                ),
                              ),
                              // User's first name overlay - matching website design
                              Positioned.fill(
                                child: Center(
                                  child: Builder(
                                    builder: (context) {
                                      final userName = SharedPreferencesUtil.getString(SharedPreferencesKey.name);
                                      if (userName.isEmpty) {
                                        return SizedBox.shrink();
                                      }
                                      final firstName = userName.trim().split(' ').first;
                                      final displayName = firstName.isNotEmpty
                                          ? (firstName[0].toUpperCase() + (firstName.length > 1 ? firstName.substring(1).toLowerCase() : ''))
                                          : '';
                                        return Text(
                                          displayName,
                                          style: TextStyle(
                                            fontSize: 42.sp, // Elegant font size for certificate preview
                                            fontWeight: FontWeight.normal,
                                            color: Color(0xFF383630), // Matching website color #383630
                                            fontFamily: 'GreatVibes', // Use Great Vibes cursive font
                                            fontStyle: FontStyle.normal,
                                            letterSpacing: 1.0,
                                            height: 1.2, // Slightly tighter line height for cursive
                                          ),
                                          textAlign: TextAlign.center,
                                        );
                                    },
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                      SizedBox(height: 40.h),
                      SizedBox(
                        width: double.infinity,
                        child: Text(
                          CommonString.congratulation,
                          textAlign: TextAlign.center,
                          style: TextStyle(fontSize: 30.sp, fontWeight: FontWeight.bold, fontFamily: Constant.sourceSerif4, color: CommonColor.bg_button),
                        ),
                      ),
                      SizedBox(height: 5.h),
                      SizedBox(
                        width: double.infinity,
                        child: Text(
                          CommonString.message_congratulation,
                          textAlign: TextAlign.center,
                          style: TextStyle(fontSize: 13.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope, color: CommonColor.black),
                        ),
                      ),
                      SizedBox(height: 130.h),
                      SizedBox(
                        width: double.infinity,
                        child: TextButton(
                          onPressed: () {
                            provider.savePdf(title: widget.title);
                          },
                          style: TextButton.styleFrom(
                            backgroundColor: CommonColor.bg_button,
                            padding: EdgeInsets.all(10.h),
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10.w)),
                          ),
                          child: Text(CommonString.download.toUpperCase(),
                              style: TextStyle(color: CommonColor.white, fontSize: 16.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope)),
                        ),
                      )
                    ],
                  ),
                ),
              ),
            ],
          );
        },
      ),
    ));
  }
}
