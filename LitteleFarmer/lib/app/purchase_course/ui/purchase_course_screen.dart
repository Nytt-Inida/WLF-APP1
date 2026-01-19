import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/purchase_course/provider/purchase_course_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:little_farmer/app/purchase_course/model/purchase_course_model.dart';

class PurchaseCourseScreen extends StatefulWidget {
  const PurchaseCourseScreen({super.key});

  @override
  State<PurchaseCourseScreen> createState() => _PurchaseCourseScreenState();
}

class _PurchaseCourseScreenState extends State<PurchaseCourseScreen> {
  late PurchaseCourseProvider provider;
  bool isTablet = false;
  @override
  void initState() {
    super.initState();
    provider = Provider.of<PurchaseCourseProvider>(context, listen: false);
    provider.fetchPurchaseCourse();
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
        builder: (context, PurchaseCourseProvider provider, child) {
          isTablet = Utils.isTablet(context: context);
          return Container(
            margin: EdgeInsets.all(10.h),
            child: Column(
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      CommonString.purchase_courses,
                      style: TextStyle(color: CommonColor.black, fontSize: 20.sp, fontWeight: FontWeight.w900, fontFamily: Constant.manrope),
                    ),
                    InkWell(
                        onTap: () {
                          provider.gotoSearchScreen(context: context);
                        },
                        child: Container(
                            height: 24.h,
                            width: 24.h,
                            child: SvgPicture.asset(CommonImage.ic_search, fit: BoxFit.cover, colorFilter: ColorFilter.mode(CommonColor.black, BlendMode.srcIn)))),
                  ],
                ),
                SizedBox(height: 10.h),
                Expanded(
                  child: provider.isFetchPurchaseCourseApiCalling
                      ? Container(alignment: Alignment.center, height: MediaQuery.of(context).size.height - 60.h, child: CircularProgressIndicator(color: CommonColor.bg_button))
                      : provider.purchaseCoursesList.isEmpty
                          ? Container(
                              alignment: Alignment.center,
                              height: MediaQuery.of(context).size.height - 60.h,
                              child: Text(CommonString.no_course_found, style: TextStyle(fontSize: 18.sp, fontWeight: FontWeight.bold, color: CommonColor.black)),
                            )
                          : ListView.builder(
                              shrinkWrap: false,
                              itemCount: provider.purchaseCoursesList.length,
                              physics: const AlwaysScrollableScrollPhysics(),
                              cacheExtent: 500, // Cache items for smoother scrolling
                              itemBuilder: (context, index) {
                                return RepaintBoundary(
                                  child: _PurchaseCourseItem(
                                    course: provider.purchaseCoursesList[index],
                                    index: index,
                                    isLast: index == (provider.purchaseCoursesList.length - 1),
                                    provider: provider,
                                    isTablet: isTablet,
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

// Extracted purchase course item widget for better performance
class _PurchaseCourseItem extends StatelessWidget {
  final PurchaseCourse course;
  final int index;
  final bool isLast;
  final PurchaseCourseProvider provider;
  final bool isTablet;

  const _PurchaseCourseItem({
    required this.course,
    required this.index,
    required this.isLast,
    required this.provider,
    required this.isTablet,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: EdgeInsets.only(bottom: isLast ? 60.h : 10.h),
      alignment: Alignment.center,
      decoration: BoxDecoration(
        color: CommonColor.white,
        boxShadow: [
          BoxShadow(
            color: CommonColor.grey.withAlpha(50),
            spreadRadius: 5,
            blurRadius: 7,
            offset: const Offset(0, 3),
          ),
        ],
        borderRadius: BorderRadius.circular(15.h),
      ),
      child: Material(
        color: CommonColor.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(15.h),
          onTap: () {
            provider.gotoPurchaseCourseDetailScreen(
              context: context,
              courseId: course.courseId,
              title: course.title,
            );
          },
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
                        borderRadius: BorderRadius.only(
                          topLeft: Radius.circular(12.h),
                          topRight: Radius.circular(12.h),
                        ),
                        child: Container(
                          color: CommonColor.bg_main,
                          alignment: Alignment.center,
                          child: CachedNetworkImage(
                            imageUrl: course.image,
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
                      Container(
                        margin: EdgeInsets.symmetric(horizontal: 10.h, vertical: 5.h),
                        alignment: Alignment.topRight,
                        child: InkWell(
                          onTap: () {
                            if (!provider.isFavoriteCourseApiCalling) {
                              provider.selectedItemForFavorite = index;
                              if (course.isFavorite == 0) {
                                provider.addRemoveCourseInFavorite(
                                  position: index,
                                  courseId: course.id,
                                  isFavorite: true,
                                );
                              } else {
                                provider.addRemoveCourseInFavorite(
                                  position: index,
                                  courseId: course.id,
                                  isFavorite: false,
                                );
                              }
                            }
                          },
                          child: Container(
                            padding: EdgeInsets.symmetric(horizontal: 2.h, vertical: 2.h),
                            decoration: BoxDecoration(
                              borderRadius: BorderRadius.circular(5.w),
                              color: CommonColor.white,
                            ),
                            child: provider.selectedItemForFavorite == index
                                ? provider.isFavoriteCourseApiCalling
                                    ? Container(
                                        height: 20.h,
                                        width: 20.h,
                                        padding: EdgeInsets.all(2.h),
                                        child: CircularProgressIndicator(color: CommonColor.bg_button),
                                      )
                                    : SvgPicture.asset(
                                        course.isFavorite == 0 ? CommonImage.ic_wishlist : CommonImage.ic_full_wishlist,
                                        height: 20.h,
                                        width: 20.h,
                                        fit: BoxFit.cover,
                                        colorFilter: ColorFilter.mode(CommonColor.bg_button, BlendMode.srcIn),
                                      )
                                : SvgPicture.asset(
                                    course.isFavorite == 0 ? CommonImage.ic_wishlist : CommonImage.ic_full_wishlist,
                                    height: 20.h,
                                    width: 20.h,
                                    fit: BoxFit.cover,
                                    colorFilter: ColorFilter.mode(CommonColor.bg_button, BlendMode.srcIn),
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
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Container(
                            padding: EdgeInsets.symmetric(horizontal: 10.w, vertical: 5.h),
                            decoration: BoxDecoration(
                              color: CommonColor.red.withAlpha(10),
                              borderRadius: BorderRadius.circular(15.h),
                            ),
                            child: Text(
                              course.ageGroup,
                              style: TextStyle(
                                color: CommonColor.red,
                                fontSize: 12.sp,
                                fontWeight: FontWeight.normal,
                                fontFamily: Constant.manrope,
                              ),
                            ),
                          ),
                          Text(
                            course.displayPrice,
                            style: TextStyle(
                              fontSize: 18.sp,
                              fontWeight: FontWeight.w900,
                              color: CommonColor.bg_button,
                              fontFamily: Constant.manrope,
                            ),
                          ),
                        ],
                      ),
                      SizedBox(height: 10.h),
                      Text(
                        course.title,
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: TextStyle(
                          color: CommonColor.black,
                          fontSize: 15.sp,
                          fontWeight: FontWeight.w900,
                          fontFamily: Constant.manrope,
                        ),
                      ),
                      SizedBox(height: 5.h),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              SvgPicture.asset(
                                CommonImage.ic_timer,
                                height: 16.h,
                                width: 16.h,
                                fit: BoxFit.cover,
                                colorFilter: ColorFilter.mode(CommonColor.black, BlendMode.srcIn),
                              ),
                              SizedBox(width: 7.w),
                              Text(
                                "${course.numberOfClasses} ${CommonString.classes}",
                                style: TextStyle(
                                  fontSize: 12.sp,
                                  fontWeight: FontWeight.normal,
                                  color: CommonColor.black,
                                  fontFamily: Constant.manrope,
                                ),
                              ),
                            ],
                          ),
                          SizedBox(height: 5.h),
                          Row(
                            children: [
                              SvgPicture.asset(
                                CommonImage.ic_download_certificate,
                                height: 16.h,
                                width: 16.h,
                                fit: BoxFit.cover,
                                colorFilter: ColorFilter.mode(CommonColor.black, BlendMode.srcIn),
                              ),
                              SizedBox(width: 7.w),
                              Text(
                                CommonString.download_certificate,
                                style: TextStyle(
                                  fontSize: 12.sp,
                                  fontWeight: FontWeight.normal,
                                  color: CommonColor.black,
                                  fontFamily: Constant.manrope,
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                      SizedBox(height: 10.h),
                      Container(
                        padding: EdgeInsets.symmetric(horizontal: 15.h, vertical: 8.h),
                        decoration: BoxDecoration(
                          color: CommonColor.bg_button.withAlpha(15),
                          borderRadius: BorderRadius.circular(25.h),
                        ),
                        child: Text(
                          CommonString.enrolled.toUpperCase(),
                          style: TextStyle(
                            fontSize: 12.sp,
                            fontWeight: FontWeight.w900,
                            color: CommonColor.bg_button,
                            fontFamily: Constant.manrope,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
