import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/favorite/provider/favorite_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';

class FavoriteScreen extends StatefulWidget {
  const FavoriteScreen({super.key});

  @override
  State<FavoriteScreen> createState() => _FavoriteScreenState();
}

class _FavoriteScreenState extends State<FavoriteScreen> {
  late FavoriteProvider provider;
  bool isTablet = false;

  @override
  void initState() {
    super.initState();
    provider = Provider.of<FavoriteProvider>(context, listen: false);
    provider.fetchFavoriteCourse();
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
        builder: (context, FavoriteProvider provider, _) {
          isTablet = Utils.isTablet(context: context);
          return SingleChildScrollView(
            child: Container(
              margin: EdgeInsets.all(10.h),
              child: Column(
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        CommonString.favorite_courses,
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
                  provider.isFetchFavoriteCourseApiCalling
                      ? Container(alignment: Alignment.center, height: MediaQuery.of(context).size.height - 150.h, child: CircularProgressIndicator(color: CommonColor.bg_button))
                      : provider.favoriteCoursesList.isEmpty
                          ? Container(
                              alignment: Alignment.center,
                              height: MediaQuery.of(context).size.height - 60.h,
                              child: Text(CommonString.no_course_found,
                                  style: TextStyle(fontSize: 18.sp, fontWeight: FontWeight.w900, color: CommonColor.black, fontFamily: Constant.manrope)),
                            )
                          : ListView.builder(
                              shrinkWrap: true,
                              itemCount: provider.favoriteCoursesList.length,
                              physics: const NeverScrollableScrollPhysics(),
                              itemBuilder: (context, index) {
                                return Container(
                                  margin: EdgeInsets.only(bottom: 10.h),
                                  child: Container(
                                    alignment: Alignment.center,
                                    decoration: BoxDecoration(
                                        color: CommonColor.white,
                                        boxShadow: [
                                          BoxShadow(color: CommonColor.grey.withAlpha(50), spreadRadius: 5, blurRadius: 7, offset: Offset(0, 3)),
                                        ],
                                        borderRadius: BorderRadius.circular(20.h)),
                                    child: Material(
                                      color: CommonColor.transparent,
                                      child: InkWell(
                                        borderRadius: BorderRadius.circular(20.h),
                                        onTap: () {
                                          provider.gotoCourseDetailScreen(
                                            context: context,
                                            courseId: provider.favoriteCoursesList[index].courseId,
                                            title: provider.favoriteCoursesList[index].title,
                                            price: provider.favoriteCoursesList[index].price,
                                            isPurchased: provider.favoriteCoursesList[index].isPurchase,
                                          );
                                        },
                                        child: Container(
                                          padding: EdgeInsets.all(8.w),
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
                                                        child: Image.network(
                                                          provider.favoriteCoursesList[index].image,
                                                          fit: BoxFit.contain,
                                                          loadingBuilder: (BuildContext context, Widget child, ImageChunkEvent? loadingProgress) {
                                                            if (loadingProgress == null) {
                                                              return child;
                                                            } else {
                                                              return Container(
                                                                height: isTablet ? 200.h : 120.h,
                                                                width: MediaQuery.of(context).size.width,
                                                                alignment: Alignment.center,
                                                                child: CircularProgressIndicator(
                                                                  color: CommonColor.bg_button,
                                                                  value: loadingProgress.expectedTotalBytes != null
                                                                      ? loadingProgress.cumulativeBytesLoaded / (loadingProgress.expectedTotalBytes ?? 1)
                                                                      : null,
                                                                ),
                                                              );
                                                            }
                                                          },
                                                          errorBuilder: (BuildContext context, Object error, StackTrace? stackTrace) {
                                                            return SvgPicture.asset(CommonImage.app_logo,
                                                                height: isTablet ? 200.h : 120.h, width: MediaQuery.of(context).size.width, fit: BoxFit.cover);
                                                          },
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
                                                            provider.addRemoveCourseInFavorite(
                                                                position: index,
                                                                courseId: provider.favoriteCoursesList[index].id,
                                                                isFavorite: !provider.favoriteCoursesList[index].isFavorite);
                                                          }
                                                        },
                                                        child: Container(
                                                          padding: EdgeInsets.symmetric(horizontal: 2.h, vertical: 2.h),
                                                          decoration: BoxDecoration(borderRadius: BorderRadius.circular(5.w), color: CommonColor.white),
                                                          child: provider.selectedItemForFavorite == index
                                                              ? provider.isFavoriteCourseApiCalling
                                                                  ? Container(
                                                                      height: 20.h,
                                                                      width: 20.h,
                                                                      padding: EdgeInsets.all(2.h),
                                                                      child: CircularProgressIndicator(color: CommonColor.bg_button))
                                                                  : SvgPicture.asset(
                                                                      provider.favoriteCoursesList[index].isFavorite ? CommonImage.ic_full_wishlist : CommonImage.ic_wishlist,
                                                                      height: 20.h,
                                                                      width: 20.h,
                                                                      fit: BoxFit.cover,
                                                                      colorFilter: ColorFilter.mode(CommonColor.bg_button, BlendMode.srcIn))
                                                              : SvgPicture.asset(
                                                                  provider.favoriteCoursesList[index].isFavorite ? CommonImage.ic_full_wishlist : CommonImage.ic_wishlist,
                                                                  height: 20.h,
                                                                  width: 20.h,
                                                                  fit: BoxFit.cover,
                                                                  colorFilter: ColorFilter.mode(CommonColor.bg_button, BlendMode.srcIn)),
                                                        ),
                                                      ),
                                                    )
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
                                                          decoration: BoxDecoration(color: CommonColor.red.withAlpha(10), borderRadius: BorderRadius.circular(15.h)),
                                                          child: Text(
                                                            provider.favoriteCoursesList[index].ageGroup,
                                                            style: TextStyle(color: CommonColor.red, fontSize: 12.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                                                          ),
                                                        ),
                                                        Text("\$${provider.favoriteCoursesList[index].price}",
                                                            style: TextStyle(
                                                                fontSize: 18.sp, fontWeight: FontWeight.w900, color: CommonColor.bg_button, fontFamily: Constant.manrope)),
                                                      ],
                                                    ),
                                                    SizedBox(height: 5.h),
                                                    Text(
                                                      provider.favoriteCoursesList[index].title,
                                                      maxLines: 2,
                                                      overflow: TextOverflow.ellipsis,
                                                      style: TextStyle(color: CommonColor.black, fontSize: 15.sp, fontWeight: FontWeight.w900, fontFamily: Constant.manrope),
                                                    ),
                                                    SizedBox(height: 5.h),
                                                    Column(
                                                      crossAxisAlignment: CrossAxisAlignment.start,
                                                      children: [
                                                        Row(
                                                          children: [
                                                            SvgPicture.asset(CommonImage.ic_timer,
                                                                height: 16.h, width: 16.h, fit: BoxFit.cover, colorFilter: ColorFilter.mode(CommonColor.black, BlendMode.srcIn)),
                                                            SizedBox(width: 7.w),
                                                            Text(
                                                              "${provider.favoriteCoursesList[index].numberOfClasses} ${CommonString.classes}",
                                                              style:
                                                                  TextStyle(fontSize: 12.sp, fontWeight: FontWeight.normal, color: CommonColor.black, fontFamily: Constant.manrope),
                                                            ),
                                                          ],
                                                        ),
                                                        SizedBox(height: 5.h),
                                                        Row(
                                                          children: [
                                                            SvgPicture.asset(CommonImage.ic_download_certificate,
                                                                height: 16.h, width: 16.h, fit: BoxFit.cover, colorFilter: ColorFilter.mode(CommonColor.black, BlendMode.srcIn)),
                                                            SizedBox(width: 7.w),
                                                            Text(CommonString.download_certificate,
                                                                style: TextStyle(
                                                                    fontSize: 12.sp, fontWeight: FontWeight.normal, color: CommonColor.black, fontFamily: Constant.manrope)),
                                                          ],
                                                        )
                                                      ],
                                                    )
                                                  ],
                                                ),
                                              )
                                            ],
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                );
                              },
                            ),
                  SizedBox(height: 50.h),
                ],
              ),
            ),
          );
        },
      ),
    ));
  }
}
