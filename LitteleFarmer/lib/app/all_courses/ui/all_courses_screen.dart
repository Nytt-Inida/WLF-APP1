import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/all_courses/provider/all_courses_provider.dart';
import 'package:little_farmer/app/home/model/course_by_age_model.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';

class AllCoursesScreen extends StatefulWidget {
  const AllCoursesScreen({super.key});

  @override
  State<AllCoursesScreen> createState() => _AllCoursesScreenState();
}

class _AllCoursesScreenState extends State<AllCoursesScreen> {
  late AllCoursesProvider provider;
  bool isTablet = false;

  @override
  void initState() {
    super.initState();
    provider = Provider.of<AllCoursesProvider>(context, listen: false);
    provider.fetchAllCourses();
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
        child: Consumer<AllCoursesProvider>(
          builder: (context, provider, _) {
            isTablet = Utils.isTablet(context: context);
            return Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  margin: EdgeInsets.symmetric(horizontal: 10.w, vertical: 10.h),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Expanded(
                        child: Text(
                          "All Courses",
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                          textAlign: TextAlign.center,
                          style: TextStyle(color: CommonColor.black, fontSize: 18.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                        ),
                      ),
                    ],
                  ),
                ),
                Expanded(
                  child: provider.isCourseFetchApiCalling
                      ? Center(
                          child: CircularProgressIndicator(color: CommonColor.bg_button),
                        )
                      : provider.allCoursesList.isEmpty
                          ? Container(
                              alignment: Alignment.center,
                              height: MediaQuery.of(context).size.height - 60.h,
                              child: Text(
                                CommonString.no_course_found,
                                style: TextStyle(
                                  fontSize: 18.sp,
                                  fontWeight: FontWeight.bold,
                                  color: CommonColor.black,
                                  fontFamily: Constant.manrope,
                                ),
                              ),
                            )
                          : GridView.builder(
                              gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                                crossAxisCount: isTablet ? 3 : 1, // Single column on phones for full width
                                crossAxisSpacing: 10.w,
                                mainAxisSpacing: 10.h,
                                childAspectRatio: isTablet ? 0.75 : 2.2, // Adjust aspect ratio for single column
                              ),
                              padding: EdgeInsets.fromLTRB(10.w, 0, 10.w, 100.h), // Add bottom padding for navbar, horizontal padding for cards
                              itemCount: provider.allCoursesList.length,
                              physics: const AlwaysScrollableScrollPhysics(),
                              cacheExtent: 500,
                              itemBuilder: (context, index) {
                                return RepaintBoundary(
                                  child: _AllCourseItem(
                                    course: provider.allCoursesList[index],
                                    index: index,
                                    provider: provider,
                                    allCoursesList: provider.allCoursesList,
                                    isTablet: isTablet,
                                  ),
                                );
                              },
                            ),
                )
              ],
            );
          },
        ),
      ),
    );
  }
}

// Extracted course item widget for better performance
class _AllCourseItem extends StatelessWidget {
  final Course course;
  final int index;
  final AllCoursesProvider provider;
  final List<Course> allCoursesList;
  final bool isTablet;

  const _AllCourseItem({
    required this.course,
    required this.index,
    required this.provider,
    required this.allCoursesList,
    required this.isTablet,
  });

  @override
  Widget build(BuildContext context) {
    // For single column (full width), use horizontal layout; for tablet grid, use vertical layout
    if (!isTablet) {
      // Horizontal layout for full-width single column
      return Container(
        margin: EdgeInsets.only(bottom: 10.h),
        decoration: BoxDecoration(
          color: CommonColor.white,
          boxShadow: [
            BoxShadow(
              color: CommonColor.grey.withAlpha(50),
              spreadRadius: 2,
              blurRadius: 5,
              offset: const Offset(0, 2),
            ),
          ],
          borderRadius: BorderRadius.circular(15.h),
        ),
        child: Material(
          color: CommonColor.transparent,
          child: InkWell(
            borderRadius: BorderRadius.circular(15.h),
            onTap: () {
              provider.gotoCourseDetailScreen(
                context: context,
                courseId: course.id,
                title: course.title,
                price: course.displayPrice,
                isPurchased: course.isPurchase,
              );
            },
            child: Padding(
              padding: EdgeInsets.all(8.w),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  SizedBox(
                    height: 100.h,
                    width: 100.h,
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(12.h),
                      child: Container(
                        color: CommonColor.bg_main,
                        child: CachedNetworkImage(
                          imageUrl: course.image,
                          fit: BoxFit.contain,
                          placeholder: (context, url) => Container(
                            alignment: Alignment.center,
                            child: CircularProgressIndicator(color: CommonColor.bg_button),
                          ),
                          errorWidget: (context, url, error) => SvgPicture.asset(
                            CommonImage.app_logo,
                            fit: BoxFit.cover,
                          ),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(width: 10.w),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Container(
                          padding: EdgeInsets.symmetric(horizontal: 6.w, vertical: 3.h),
                          decoration: BoxDecoration(
                            color: CommonColor.red.withAlpha(10),
                            borderRadius: BorderRadius.circular(8.h),
                          ),
                          child: Text(
                            course.ageGroup,
                            style: TextStyle(
                              color: CommonColor.red,
                              fontSize: 10.sp,
                              fontWeight: FontWeight.normal,
                              fontFamily: Constant.manrope,
                            ),
                          ),
                        ),
                        SizedBox(height: 5.h),
                        Text(
                          course.title,
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                          style: TextStyle(
                            color: CommonColor.black,
                            fontSize: 14.sp,
                            fontWeight: FontWeight.bold,
                            fontFamily: Constant.manrope,
                          ),
                        ),
                        SizedBox(height: 8.h),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Row(
                              children: [
                                SvgPicture.asset(
                                  CommonImage.ic_timer,
                                  height: 12.h,
                                  width: 12.w,
                                  fit: BoxFit.cover,
                                  colorFilter: ColorFilter.mode(CommonColor.black, BlendMode.srcIn),
                                ),
                                SizedBox(width: 4.w),
                                Text(
                                  "${course.numberOfClasses} ${CommonString.classes}",
                                  style: TextStyle(
                                    fontSize: 11.sp,
                                    fontWeight: FontWeight.normal,
                                    color: CommonColor.black,
                                    fontFamily: Constant.manrope,
                                  ),
                                ),
                              ],
                            ),
                            Text(
                              course.displayPrice,
                              style: TextStyle(
                                fontSize: 15.sp,
                                fontWeight: FontWeight.w500,
                                color: CommonColor.bg_button,
                                fontFamily: Constant.manrope,
                              ),
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
      );
    } else {
      // Vertical layout for tablet grid
      return Container(
        decoration: BoxDecoration(
          color: CommonColor.white,
          boxShadow: [
            BoxShadow(
              color: CommonColor.grey.withAlpha(50),
              spreadRadius: 2,
              blurRadius: 5,
              offset: const Offset(0, 2),
            ),
          ],
          borderRadius: BorderRadius.circular(15.h),
        ),
        child: Material(
          color: CommonColor.transparent,
          child: InkWell(
            borderRadius: BorderRadius.circular(15.h),
            onTap: () {
              provider.gotoCourseDetailScreen(
                context: context,
                courseId: course.id,
                title: course.title,
                price: course.displayPrice,
                isPurchased: course.isPurchase,
              );
            },
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Expanded(
                  flex: 3,
                  child: Stack(
                    children: [
                      ClipRRect(
                        borderRadius: BorderRadius.only(
                          topLeft: Radius.circular(15.h),
                          topRight: Radius.circular(15.h),
                        ),
                        child: Container(
                          color: CommonColor.bg_main,
                          width: double.infinity,
                          child: CachedNetworkImage(
                            imageUrl: course.image,
                            fit: BoxFit.contain,
                            placeholder: (context, url) => Container(
                              alignment: Alignment.center,
                              child: CircularProgressIndicator(color: CommonColor.bg_button),
                            ),
                            errorWidget: (context, url, error) => SvgPicture.asset(
                              CommonImage.app_logo,
                              fit: BoxFit.cover,
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
                Expanded(
                  flex: 2,
                  child: Container(
                    padding: EdgeInsets.all(8.w),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Container(
                          padding: EdgeInsets.symmetric(horizontal: 6.w, vertical: 3.h),
                          decoration: BoxDecoration(
                            color: CommonColor.red.withAlpha(10),
                            borderRadius: BorderRadius.circular(8.h),
                          ),
                          child: Text(
                            course.ageGroup,
                            style: TextStyle(
                              color: CommonColor.red,
                              fontSize: 10.sp,
                              fontWeight: FontWeight.normal,
                              fontFamily: Constant.manrope,
                            ),
                          ),
                        ),
                        Text(
                          course.title,
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                          style: TextStyle(
                            color: CommonColor.black,
                            fontSize: 13.sp,
                            fontWeight: FontWeight.bold,
                            fontFamily: Constant.manrope,
                          ),
                        ),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Row(
                              children: [
                                SvgPicture.asset(
                                  CommonImage.ic_timer,
                                  height: 12.h,
                                  width: 12.w,
                                  fit: BoxFit.cover,
                                  colorFilter: ColorFilter.mode(CommonColor.black, BlendMode.srcIn),
                                ),
                                SizedBox(width: 4.w),
                                Text(
                                  "${course.numberOfClasses} ${CommonString.classes}",
                                  style: TextStyle(
                                    fontSize: 10.sp,
                                    fontWeight: FontWeight.normal,
                                    color: CommonColor.black,
                                    fontFamily: Constant.manrope,
                                  ),
                                ),
                              ],
                            ),
                            Text(
                              course.displayPrice,
                              style: TextStyle(
                                fontSize: 14.sp,
                                fontWeight: FontWeight.w500,
                                color: CommonColor.bg_button,
                                fontFamily: Constant.manrope,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      );
    }
  }
}
