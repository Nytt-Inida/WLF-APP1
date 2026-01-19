import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:flutter_floating_bottom_bar/flutter_floating_bottom_bar.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/home/ui/home_screen.dart';
import 'package:little_farmer/app/main_home/provider/main_home_provider.dart';
import 'package:little_farmer/app/profile/ui/profile_screen.dart';
import 'package:little_farmer/app/all_courses/ui/all_courses_screen.dart';
import 'package:little_farmer/app/profile/ui/about_screen.dart';
import 'package:little_farmer/app/blog/ui/blog_list_screen.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';

class MainHomeScreen extends StatefulWidget {
  const MainHomeScreen({super.key});

  @override
  State<MainHomeScreen> createState() => _MainHomeScreenState();
}

class _MainHomeScreenState extends State<MainHomeScreen> with SingleTickerProviderStateMixin {
  late MainHomeProvider provider;

  @override
  void initState() {
    provider = Provider.of<MainHomeProvider>(context, listen: false);
    provider.currentPage = 0;
    provider.tabController = TabController(length: 5, vsync: this);
    provider.tabController.animation?.addListener(
      () {
        final value = provider.tabController.animation!.value.round();
        if (value != provider.currentPage && mounted) {
          provider.changePage(value);
        }
      },
    );
    super.initState();
  }

  @override
  void dispose() {
    provider.tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return PopScope(
      canPop: false,
      onPopInvokedWithResult: (didPop, result) async {
        if (didPop) return;
        Utils.showExitConfirmationDialog(context: context);
      },
      child: Scaffold(
        backgroundColor: CommonColor.bg_main,
        extendBodyBehindAppBar: false,
        body: SafeArea(
          child: Consumer(builder: (context, MainHomeProvider provider, _) {
            bool isTablet = Utils.isTablet(context: context);
            return BottomBar(
              hideOnScroll: false,
              clip: Clip.none,
              fit: StackFit.expand,
              borderRadius: BorderRadius.circular(50.h),
              curve: Curves.decelerate,
              showIcon: false,
              width: MediaQuery.of(context).size.width * 0.8,
              start: 2,
              end: 0,
              offset: 10.h,
              barAlignment: Alignment.bottomCenter,
              iconHeight: 30.h,
              iconWidth: 30.h,
              reverse: false,
              iconDecoration: BoxDecoration(color: CommonColor.white, borderRadius: BorderRadius.circular(150.h)),
              body: (context, controller) => TabBarView(
                controller: provider.tabController,
                dragStartBehavior: DragStartBehavior.down,
                physics: const NeverScrollableScrollPhysics(),
                children: const [
                  HomeScreen(),
                  AllCoursesScreen(),
                  AboutScreen(),
                  BlogListScreen(),
                  ProfileScreen(),
                ],
              ),
              child: Stack(
                alignment: Alignment.center,
                clipBehavior: Clip.none,
                children: [
                  TabBar(
                    controller: provider.tabController,
                    isScrollable: false,
                    dividerColor: CommonColor.transparent,
                    indicatorColor: CommonColor.transparent,
                    tabs: [
                      // Home Tab
                      Material(
                        color: Colors.transparent,
                        child: InkWell(
                          borderRadius: BorderRadius.circular(50.h),
                          onTap: () {
                            provider.tabController.animateTo(0);
                            provider.changePage(0);
                          },
                          child: Container(
                            height: 50.h,
                            width: isTablet ? 50.h : 50.w,
                            alignment: Alignment.center,
                            child: SvgPicture.asset(
                              CommonImage.ic_home,
                              height: 24.h,
                              width: 24.w,
                              fit: BoxFit.contain,
                              color: provider.currentPage == 0 ? CommonColor.white : CommonColor.grey,
                            ),
                          ),
                        ),
                      ),
                      // Courses Tab
                      Material(
                        color: Colors.transparent,
                        child: InkWell(
                          borderRadius: BorderRadius.circular(50.h),
                          onTap: () {
                            provider.tabController.animateTo(1);
                            provider.changePage(1);
                          },
                          child: Container(
                            height: 50.h,
                            width: isTablet ? 50.h : 50.w,
                            alignment: Alignment.center,
                            child: SvgPicture.asset(
                              CommonImage.ic_course,
                              height: 24.h,
                              width: 24.w,
                              fit: BoxFit.contain,
                              colorFilter: ColorFilter.mode(provider.currentPage == 1 ? CommonColor.white : CommonColor.grey, BlendMode.srcIn)
                            ),
                          ),
                        ),
                      ),
                      // About Us Tab
                      Material(
                        color: Colors.transparent,
                        child: InkWell(
                          borderRadius: BorderRadius.circular(50.h),
                          onTap: () {
                            provider.tabController.animateTo(2);
                            provider.changePage(2);
                          },
                          child: Container(
                            height: 50.h,
                            width: isTablet ? 50.h : 50.w,
                            alignment: Alignment.center,
                            child: Icon(
                              Icons.info_outline,
                              size: 24.h,
                              color: provider.currentPage == 2 ? CommonColor.white : CommonColor.grey,
                            ),
                          ),
                        ),
                      ),
                      // Blog Tab
                      Material(
                        color: Colors.transparent,
                        child: InkWell(
                          borderRadius: BorderRadius.circular(50.h),
                          onTap: () {
                            provider.tabController.animateTo(3);
                            provider.changePage(3);
                          },
                          child: Container(
                            height: 50.h,
                            width: isTablet ? 50.h : 50.w,
                            alignment: Alignment.center,
                            child: Icon(
                              Icons.article_outlined,
                              size: 24.h,
                              color: provider.currentPage == 3 ? CommonColor.white : CommonColor.grey,
                            ),
                          ),
                        ),
                      ),
                      // Profile Tab
                      Material(
                        color: Colors.transparent,
                        child: InkWell(
                          borderRadius: BorderRadius.circular(50.h),
                          onTap: () {
                            provider.tabController.animateTo(4);
                            provider.changePage(4);
                          },
                          child: Container(
                            height: 50.h,
                            width: isTablet ? 50.h : 50.w,
                            alignment: Alignment.center,
                            child: SvgPicture.asset(
                              CommonImage.ic_person,
                              height: 24.h,
                              width: 24.w,
                              fit: BoxFit.contain,
                              colorFilter: ColorFilter.mode(provider.currentPage == 4 ? CommonColor.white : CommonColor.grey, BlendMode.srcIn)
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            );
          }),
        ),
      ),
    );
  }
}
