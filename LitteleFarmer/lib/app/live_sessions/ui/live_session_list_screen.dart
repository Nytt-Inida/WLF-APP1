import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/live_sessions/provider/live_session_provider.dart';
import 'package:little_farmer/app/live_sessions/ui/live_session_detail_screen.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/toolbar.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';

class LiveSessionListScreen extends StatefulWidget {
  const LiveSessionListScreen({super.key});

  @override
  State<LiveSessionListScreen> createState() => _LiveSessionListScreenState();
}

class _LiveSessionListScreenState extends State<LiveSessionListScreen> {
  late LiveSessionProvider provider;
  bool isTablet = false;

  @override
  void initState() {
    super.initState();
    // No fetch needed as data is hardcoded in provider
    provider = Provider.of<LiveSessionProvider>(context, listen: false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        backgroundColor: CommonColor.bg_main,
        extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Consumer<LiveSessionProvider>(
          builder: (context, provider, _) { 
            isTablet = Utils.isTablet(context: context);
            return Container(
              margin: EdgeInsets.all(10.h),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Toolbar(title: "Live Sessions"), // Add to CommonString if needed
                  SizedBox(height: 10.h),
                  Expanded(
                    child: ListView.builder(
                      shrinkWrap: true,
                      itemCount: provider.sessions.length,
                      physics: const AlwaysScrollableScrollPhysics(),
                      itemBuilder: (context, index) {
                        final session = provider.sessions[index];
                        return Container(
                          margin: EdgeInsets.only(bottom: 10.h),
                          child: Container(
                            alignment: Alignment.center,
                            decoration: BoxDecoration(
                                color: CommonColor.white,
                                boxShadow: [
                                  BoxShadow(color: CommonColor.grey.withAlpha(50), spreadRadius: 5, blurRadius: 7, offset: const Offset(0, 3)),
                                ],
                                borderRadius: BorderRadius.circular(20.h)),
                            child: Material(
                              color: CommonColor.transparent,
                              child: InkWell(
                                borderRadius: BorderRadius.circular(20.h),
                                onTap: () {
                                  Navigator.push(
                                    context, 
                                    MaterialPageRoute(builder: (context) => LiveSessionDetailScreen(session: session))
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
                                        child: ClipRRect(
                                          borderRadius: BorderRadius.only(topLeft: Radius.circular(12.h), topRight: Radius.circular(12.h)),
                                          child: Container(
                                            color: CommonColor.bg_main,
                                            alignment: Alignment.center,
                                            child: Image.network(
                                              session.imageUrl,
                                              fit: BoxFit.cover,
                                              width: double.infinity,
                                              errorBuilder: (context, error, stackTrace) {
                                                return SvgPicture.asset(CommonImage.app_logo,
                                                    height: isTablet ? 200.h : 120.h, width: MediaQuery.of(context).size.width, fit: BoxFit.cover);
                                              },
                                            ),
                                          ),
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
                                                    session.ageGroup,
                                                    style: TextStyle(color: CommonColor.red, fontSize: 12.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                                                  ),
                                                ),
                                                Text(
                                                  session.price,
                                                  style: TextStyle(fontSize: 14.sp, fontWeight: FontWeight.w500, color: CommonColor.bg_button, fontFamily: Constant.manrope),
                                                ),
                                              ],
                                            ),
                                            SizedBox(height: 5.h),
                                            Text(
                                              session.title,
                                              maxLines: 2,
                                              overflow: TextOverflow.ellipsis,
                                              style: TextStyle(color: CommonColor.black, fontSize: 15.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                            ),
                                            SizedBox(height: 5.h),
                                            Row(
                                              children: [
                                                SvgPicture.asset(CommonImage.ic_timer,
                                                    height: 16.h, width: 16.h, fit: BoxFit.cover, colorFilter: ColorFilter.mode(CommonColor.black, BlendMode.srcIn)),
                                                SizedBox(width: 7.w),
                                                Text(
                                                  session.classesCount,
                                                  style: TextStyle(fontSize: 12.sp, fontWeight: FontWeight.normal, color: CommonColor.black, fontFamily: Constant.manrope),
                                                ),
                                              ],
                                            ),
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
                  )
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}
