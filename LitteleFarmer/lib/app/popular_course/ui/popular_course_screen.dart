import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/home/model/popular_course_model.dart';
import 'package:little_farmer/app/popular_course/provider/popular_course_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/toolbar.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';

// ignore: must_be_immutable
class PopularCourseScreen extends StatefulWidget {
  List<PopularCourse> popularCoursesList;

  PopularCourseScreen({super.key, required this.popularCoursesList});

  @override
  State<PopularCourseScreen> createState() => _PopularCourseScreenState();
}

class _PopularCourseScreenState extends State<PopularCourseScreen> {
  late PopularCourseProvider provider;
  bool isTablet = false;
  @override
  void initState() {
    super.initState();
    provider = Provider.of<PopularCourseProvider>(context, listen: false);
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
        builder: (context, PopularCourseProvider provider, _) {
          isTablet = Utils.isTablet(context: context);
          return Container(
            margin: EdgeInsets.all(10.h),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Toolbar(title: CommonString.popular_courses),
                SizedBox(height: 10.h),
                Expanded(
                  child: widget.popularCoursesList.isEmpty
                      ? Container(
                          alignment: Alignment.center,
                          height: MediaQuery.of(context).size.height - 60.h,
                          child: Text(CommonString.no_course_found,
                              style: TextStyle(fontSize: 18.sp, fontWeight: FontWeight.bold, color: CommonColor.black, fontFamily: Constant.manrope)),
                        )
                      : ListView.builder(
                          shrinkWrap: false,
                          itemCount: widget.popularCoursesList.length,
                          physics: const AlwaysScrollableScrollPhysics(),
                          cacheExtent: 500, // Cache items for smoother scrolling
                          itemBuilder: (context, index) {
                            return RepaintBoundary(
                              child: _PopularCourseItem(
                                course: widget.popularCoursesList[index],
                                index: index,
                                provider: provider,
                                popularCoursesList: widget.popularCoursesList,
                                isTablet: isTablet,
                              ),
                            );
                          },
                        ),
                )
              ],
            ),
          );
        },
      ),
    ));
  }
}

// Extracted popular course item widget for better performance
class _PopularCourseItem extends StatelessWidget {
  final PopularCourse course;
  final int index;
  final PopularCourseProvider provider;
  final List<PopularCourse> popularCoursesList;
  final bool isTablet;

  const _PopularCourseItem({
    required this.course,
    required this.index,
    required this.provider,
    required this.popularCoursesList,
    required this.isTablet,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: EdgeInsets.only(bottom: 10.h),
      child: Container(
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
          borderRadius: BorderRadius.circular(20.h),
        ),
        child: Material(
          color: CommonColor.transparent,
          child: InkWell(
            borderRadius: BorderRadius.circular(20.h),
            onTap: () {
              provider.gotoCourseDetailScreen(
                context: context,
                courseId: course.id,
                title: course.title,
                price: course.price,
                isPurchased: course.isPurchase,
              );
            },
            child: Container(
              padding: EdgeInsets.all(8.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  SizedBox(
                    height: isTablet ? 170.h : 120.h,
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
                              if (!provider.isFavoriteApiCalling) {
                                provider.selectedItemForFavorite = index;
                                if (course.isFavorite == 0) {
                                  provider.addRemoveCourseInFavorite(
                                    position: index,
                                    courseId: course.id,
                                    isFavorite: true,
                                    popularCoursesList: popularCoursesList,
                                  );
                                } else {
                                  provider.addRemoveCourseInFavorite(
                                    position: index,
                                    courseId: course.id,
                                    isFavorite: false,
                                    popularCoursesList: popularCoursesList,
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
                                  ? provider.isFavoriteApiCalling
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
                  SizedBox(height: 5.h),
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
                                fontWeight: FontWeight.w500,
                                color: CommonColor.bg_button,
                                fontFamily: Constant.manrope,
                              ),
                            ),
                          ],
                        ),
                        SizedBox(height: 5.h),
                        Text(
                          course.title,
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                          style: TextStyle(
                            color: CommonColor.black,
                            fontSize: 15.sp,
                            fontWeight: FontWeight.bold,
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
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
