import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/certificate/provider/certificate_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/toolbar.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';

class CertificateScreen extends StatefulWidget {
  const CertificateScreen({super.key});

  @override
  State<CertificateScreen> createState() => _CertificateScreenState();
}

class _CertificateScreenState extends State<CertificateScreen> {
  late CertificateProvider provider;
  bool isTablet = false;

  @override
  void initState() {
    super.initState();
    provider = Provider.of<CertificateProvider>(context, listen: false);
    provider.fetchCompleteCourseCertificate();
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
        builder: (context, CertificateProvider provider, _) {
          isTablet = Utils.isTablet(context: context);
          return Container(
            margin: EdgeInsets.all(10.h),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Toolbar(title: CommonString.my_certificate),
                SizedBox(height: 10.h),
                Expanded(
                  child: provider.isCompleteCourseFetching
                      ? Container(
                          alignment: Alignment.center,
                          height: MediaQuery.of(context).size.height - 60.h,
                          child: CircularProgressIndicator(color: CommonColor.bg_button),
                        )
                      : provider.completedCoursesList.isEmpty
                          ? Container(
                              alignment: Alignment.center,
                              height: MediaQuery.of(context).size.height - 60.h,
                              child: Text(CommonString.no_course_found,
                                  style: TextStyle(fontSize: 18.sp, fontWeight: FontWeight.bold, color: CommonColor.black, fontFamily: Constant.manrope)),
                            )
                          : ListView.builder(
                              shrinkWrap: true,
                              itemCount: provider.completedCoursesList.length,
                              physics: const AlwaysScrollableScrollPhysics(),
                              itemBuilder: (context, index) {
                                return Container(
                                  margin: EdgeInsets.only(bottom: index == (provider.completedCoursesList.length - 1) ? 60.h : 10.h),
                                  alignment: Alignment.center,
                                  decoration:
                                      BoxDecoration(color: CommonColor.white, border: Border.all(color: CommonColor.grey, width: 2.w), borderRadius: BorderRadius.circular(15.h)),
                                  child: Container(
                                    padding: EdgeInsets.all(5.w),
                                    child: Column(
                                      children: [
                                        SizedBox(
                                          height: isTablet ? 200.h : 120.h,
                                          width: MediaQuery.of(context).size.width,
                                          child: Stack(
                                            children: [
                                              ClipRRect(
                                                borderRadius: BorderRadius.only(topLeft: Radius.circular(12.h), topRight: Radius.circular(12.h)),
                                                child: Container(
                                                  color: CommonColor.bg_main,
                                                  alignment: Alignment.center,
                                                  child: CachedNetworkImage(
                                                    imageUrl: provider.completedCoursesList[index].image,
                                                    fit: BoxFit.contain,
                                                    height: isTablet ? 200.h : 120.h,
                                                    width: MediaQuery.of(context).size.width,
                                                    placeholder: (context, url) => Container(
                                                      height: isTablet ? 200.h : 120.h,
                                                      width: MediaQuery.of(context).size.width,
                                                      alignment: Alignment.center,
                                                      child: CircularProgressIndicator(color: CommonColor.bg_button),
                                                    ),
                                                    errorWidget: (context, url, error) => SvgPicture.asset(
                                                      CommonImage.app_logo,
                                                      height: isTablet ? 200.h : 120.h,
                                                      width: MediaQuery.of(context).size.width,
                                                      fit: BoxFit.cover,
                                                    ),
                                                  ),
                                                ),
                                              ),
                                            ],
                                          ),
                                        ),
                                        SizedBox(height: 10.h),
                                        Container(
                                          margin: EdgeInsets.symmetric(horizontal: 10.w),
                                          child: Column(
                                            crossAxisAlignment: CrossAxisAlignment.start,
                                            children: [
                                              Text(
                                                provider.completedCoursesList[index].title,
                                                maxLines: 2,
                                                overflow: TextOverflow.ellipsis,
                                                style: TextStyle(color: CommonColor.black, fontSize: 15.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                              ),
                                              SizedBox(height: 10.h),
                                              Container(
                                                height: 45.h,
                                                width: MediaQuery.of(context).size.width,
                                                alignment: Alignment.center,
                                                decoration: BoxDecoration(color: CommonColor.bg_button, borderRadius: BorderRadius.circular(10.h)),
                                                child: Material(
                                                  color: Colors.transparent,
                                                  child: InkWell(
                                                      splashColor: CommonColor.rippleColor,
                                                      borderRadius: BorderRadius.circular(10.h),
                                                      onTap: () {
                                                        provider.gotoCertificateScreen(context: context, title: provider.completedCoursesList[index].title);
                                                      },
                                                      child: Container(
                                                        height: double.infinity,
                                                        width: MediaQuery.of(context).size.width,
                                                        alignment: Alignment.center,
                                                        child: Text(
                                                          CommonString.download_my_certificate,
                                                          style: TextStyle(color: CommonColor.white, fontWeight: FontWeight.bold, fontSize: 14.sp, fontFamily: Constant.manrope),
                                                        ),
                                                      )),
                                                ),
                                              ),
                                            ],
                                          ),
                                        )
                                      ],
                                    ),
                                  ),
                                );
                              },
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
