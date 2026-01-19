import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/all_courses/ui/all_courses_screen.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:little_farmer/utils/website_images.dart';
import 'package:url_launcher/url_launcher.dart';

class ContactScreen extends StatefulWidget {
  const ContactScreen({super.key});

  @override
  State<ContactScreen> createState() => _ContactScreenState();
}

class _ContactScreenState extends State<ContactScreen> {
  Future<void> _makePhoneCall(String phoneNumber) async {
    final Uri launchUri = Uri(
      scheme: 'tel',
      path: phoneNumber,
    );
    if (!await launchUrl(launchUri)) {
      throw Exception('Could not launch $launchUri');
    }
  }

  Future<void> _sendEmail(String email) async {
    final Uri launchUri = Uri(
      scheme: 'mailto',
      path: email,
    );
    if (!await launchUrl(launchUri)) {
      throw Exception('Could not launch $launchUri');
    }
  }

  Future<void> _openWhatsApp() async {
    final Uri url = Uri.parse(WebsiteImages.whatsappUrl);
    if (!await launchUrl(url, mode: LaunchMode.externalApplication)) {
      throw Exception('Could not launch WhatsApp');
    }
  }

  Future<void> _openMaps(String query) async {
    final Uri url = Uri.parse('https://www.google.com/maps/search/?api=1&query=${Uri.encodeComponent(query)}');
    if (!await launchUrl(url, mode: LaunchMode.externalApplication)) {
      throw Exception('Could not launch Maps');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        backgroundColor: CommonColor.bg_main,
        extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Column(
          children: [
            Container(
              margin: EdgeInsets.all(10.h),
              child: Row(
                children: [
                  InkWell(
                    onTap: () {
                      Navigator.pop(context);
                    },
                    child: Container(
                      height: 40.h,
                      width: 40.h,
                      padding: EdgeInsets.all(10.h),
                      decoration: BoxDecoration(
                          color: CommonColor.white,
                          borderRadius: BorderRadius.circular(50.h)),
                      child: SvgPicture.asset(CommonImage.ic_back,
                          fit: BoxFit.cover,
                          colorFilter: ColorFilter.mode(
                              CommonColor.black, BlendMode.srcIn)),
                    ),
                  ),
                  Expanded(
                    child: Text(
                      "Contact Us",
                      textAlign: TextAlign.center,
                      style: TextStyle(
                          color: CommonColor.black,
                          fontSize: 18.sp,
                          fontWeight: FontWeight.w900,
                          fontFamily: Constant.manrope),
                    ),
                  ),
                  SizedBox(width: 40.h),
                ],
              ),
            ),
            Expanded(
              child: SingleChildScrollView(
                padding: EdgeInsets.only(bottom: 100.h), // Add bottom padding for navbar
                child: Column(
                  children: [
                    // Top Promotional Banner
                    Container(
                      width: double.infinity,
                      padding: EdgeInsets.all(20.w),
                      margin: EdgeInsets.symmetric(horizontal: 15.w, vertical: 15.h),
                      decoration: BoxDecoration(
                        color: Color(0xFFF1F8E9),
                        borderRadius: BorderRadius.circular(15.r),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            "Looking for course information?",
                            style: TextStyle(
                              color: CommonColor.black,
                              fontSize: 20.sp,
                              fontWeight: FontWeight.w700,
                              fontFamily: Constant.manrope,
                            ),
                          ),
                          SizedBox(height: 8.h),
                          Text(
                            "Explore our farming programs designed for kids aged 5-14.",
                            style: TextStyle(
                              color: Colors.grey[700],
                              fontSize: 16.sp,
                              fontFamily: Constant.manrope,
                            ),
                          ),
                          SizedBox(height: 15.h),
                          SizedBox(
                            width: double.infinity,
                            height: 45.h,
                            child: ElevatedButton(
                              onPressed: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (context) => const AllCoursesScreen(),
                                  ),
                                );
                              },
                              style: ElevatedButton.styleFrom(
                                backgroundColor: CommonColor.bg_button,
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(10.r),
                                ),
                                elevation: 2,
                              ),
                              child: Text(
                                "Explore Our Programs",
                                style: TextStyle(
                                  color: CommonColor.white,
                                  fontSize: 15.sp,
                                  fontWeight: FontWeight.w600,
                                  fontFamily: Constant.manrope,
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                    
                    // Main Contact Section
                    Padding(
                      padding: EdgeInsets.symmetric(horizontal: 15.w),
                      child: LayoutBuilder(
                        builder: (context, constraints) {
                          // For tablets and larger screens, use side-by-side layout
                          // For mobile, stack vertically
                          final isTabletDevice = Utils.isTablet(context: context) || constraints.maxWidth > 600;
                          
                          if (isTabletDevice) {
                            // Tablet/Desktop: Side-by-side layout
                            return Row(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                // Left: Contact Image
                                Expanded(
                                  flex: 1,
                                  child: Container(
                                    margin: EdgeInsets.only(right: 15.w),
                                    child: ClipRRect(
                                      borderRadius: BorderRadius.circular(15.r),
                                      child: CachedNetworkImage(
                                        imageUrl: WebsiteImages.contactImage01,
                                        fit: BoxFit.cover,
                                        width: double.infinity,
                                        height: 400.h,
                                        placeholder: (context, url) => Container(
                                          height: 400.h,
                                          alignment: Alignment.center,
                                          child: CircularProgressIndicator(
                                            color: CommonColor.bg_button,
                                          ),
                                        ),
                                        errorWidget: (context, url, error) => Container(
                                          height: 400.h,
                                          color: Colors.grey[300],
                                          child: const Icon(Icons.image, color: Colors.grey),
                                        ),
                                      ),
                                    ),
                                  ),
                                ),
                                
                                // Right: Contact Information
                                Expanded(
                                  flex: 1,
                                  child: _buildContactInfoSection(),
                                ),
                              ],
                            );
                          } else {
                            // Mobile: Stacked layout
                            return Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                // Contact Image (full width)
                                ClipRRect(
                                  borderRadius: BorderRadius.circular(15.r),
                                  child: CachedNetworkImage(
                                    imageUrl: WebsiteImages.contactImage01,
                                    fit: BoxFit.cover,
                                    width: double.infinity,
                                    height: 250.h,
                                    placeholder: (context, url) => Container(
                                      height: 250.h,
                                      alignment: Alignment.center,
                                      child: CircularProgressIndicator(
                                        color: CommonColor.bg_button,
                                      ),
                                    ),
                                    errorWidget: (context, url, error) => Container(
                                      height: 250.h,
                                      color: Colors.grey[300],
                                      child: const Icon(Icons.image, color: Colors.grey),
                                    ),
                                  ),
                                ),
                                SizedBox(height: 20.h),
                                
                                // Contact Information
                                _buildContactInfoSection(),
                              ],
                            );
                          }
                        },
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildContactInfoSection() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
                                Text(
                                  "Get In Touch With Us",
                                  style: TextStyle(
                                    color: CommonColor.black,
                                    fontSize: 22.sp,
                                    fontWeight: FontWeight.bold,
                                    fontFamily: Constant.manrope,
                                  ),
                                ),
                                SizedBox(height: 10.h),
                                Text(
                                  "Have a question or need help? We'd love to hear from you. Reach out and our team will get back to you soon.",
                                  style: TextStyle(
                                    color: CommonColor.black,
                                    fontSize: 14.sp,
                                    height: 1.5,
                                    fontFamily: Constant.manrope,
                                  ),
                                ),
                                SizedBox(height: 25.h),
                                
                                // UAE Office Location
                                GestureDetector(
                                  onTap: () => _openMaps("Nytt - Insights Gulf LLC Block-B 1035, Youssef Zahra Bldg, Al Quoz 3, Dubai, UAE"),
                                  child: _buildContactCard(
                                    title: "UAE Office",
                                    content: "Nytt - Insights Gulf\nLLC Block-B 1035, Youssef Zahra Bldg,\nAl Quoz 3, Dubai, UAE",
                                    icon: Icons.location_on_outlined,
                                    isActionable: true,
                                  ),
                                ),

                                // Phone
                                GestureDetector(
                                  onTap: () => _makePhoneCall("+971543202013"),
                                  child: _buildContactCard(
                                    title: "Phone",
                                    content: "+971 54 320 2013",
                                    icon: Icons.phone_outlined,
                                    isActionable: true,
                                  ),
                                ),

                                // Email
                                GestureDetector(
                                  onTap: () => _sendEmail("register@welittlefarmers.com"),
                                  child: _buildContactCard(
                                    title: "Email",
                                    content: "register@welittlefarmers.com",
                                    icon: Icons.email_outlined,
                                    isActionable: true,
                                  ),
                                ),
                                
                                SizedBox(height: 25.h),
                                
                                // Need Instant Help Section
                                Container(
                                  width: double.infinity,
                                  padding: EdgeInsets.all(20.w),
                                  decoration: BoxDecoration(
                                    color: Colors.grey[50],
                                    borderRadius: BorderRadius.circular(15.r),
                                    border: Border.all(color: Colors.grey[200]!),
                                  ),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        "Need Instant Help?",
                                        style: TextStyle(
                                          color: CommonColor.black,
                                          fontSize: 18.sp,
                                          fontWeight: FontWeight.w600,
                                          fontFamily: Constant.manrope,
                                        ),
                                      ),
                                      SizedBox(height: 15.h),
                                      GestureDetector(
                                        onTap: () {
                                          // Navigate to FAQ if available, otherwise show message
                                          ScaffoldMessenger.of(context).showSnackBar(
                                            SnackBar(content: Text("FAQ page coming soon")),
                                          );
                                        },
                                        child: Row(
                                          children: [
                                            Icon(Icons.help_outline, color: CommonColor.bg_button, size: 20.sp),
                                            SizedBox(width: 10.w),
                                            Text(
                                              "Frequently Asked Questions",
                                              style: TextStyle(
                                                color: Colors.grey[700],
                                                fontSize: 15.sp,
                                                fontFamily: Constant.manrope,
                                              ),
                                            ),
                                          ],
                                        ),
                                      ),
                                      SizedBox(height: 12.h),
                                      GestureDetector(
                                        onTap: () {
                                          Navigator.push(
                                            context,
                                            MaterialPageRoute(
                                              builder: (context) => const AllCoursesScreen(),
                                            ),
                                          );
                                        },
                                        child: Row(
                                          children: [
                                            Icon(Icons.menu_book_outlined, color: CommonColor.bg_button, size: 20.sp),
                                            SizedBox(width: 10.w),
                                            Text(
                                              "Browse Our Courses",
                                              style: TextStyle(
                                                color: Colors.grey[700],
                                                fontSize: 15.sp,
                                                fontFamily: Constant.manrope,
                                              ),
                                            ),
                                          ],
                                        ),
                                      ),
                                      SizedBox(height: 20.h),
                                      SizedBox(
                                        width: double.infinity,
                                        height: 45.h,
                                        child: ElevatedButton.icon(
                                          onPressed: _openWhatsApp,
                                          icon: Icon(Icons.chat, color: CommonColor.white, size: 20.sp),
                                          label: Text(
                                            "Chat Now",
                                            style: TextStyle(
                                              color: CommonColor.white,
                                              fontSize: 15.sp,
                                              fontWeight: FontWeight.w600,
                                              fontFamily: Constant.manrope,
                                            ),
                                          ),
                                          style: ElevatedButton.styleFrom(
                                            backgroundColor: CommonColor.bg_button,
                                            shape: RoundedRectangleBorder(
                                              borderRadius: BorderRadius.circular(10.r),
                                            ),
                                            elevation: 2,
                                          ),
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ],
    );
  }

  Widget _buildContactCard(
      {required String title,
      required String content,
      required IconData icon,
      bool isActionable = false}) {
    return Container(
      width: double.infinity,
      margin: EdgeInsets.only(bottom: 15.h),
      padding: EdgeInsets.all(15.w),
      decoration: BoxDecoration(
        color: CommonColor.white,
        borderRadius: BorderRadius.circular(10.h),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: Offset(0, 5),
          ),
        ],
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: EdgeInsets.all(10.w),
            decoration: BoxDecoration(
              color: CommonColor.bg_button.withOpacity(0.1),
              borderRadius: BorderRadius.circular(50.h),
            ),
            child: Icon(icon, color: CommonColor.bg_button, size: 24.sp),
          ),
          SizedBox(width: 15.w),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                if (title.isNotEmpty)
                  Text(
                    title,
                    style: TextStyle(
                        color: CommonColor.bg_button,
                        fontSize: 14.sp,
                        fontWeight: FontWeight.bold,
                        fontFamily: Constant.manrope),
                  ),
                if (title.isNotEmpty) SizedBox(height: 5.h),
                Text(
                  content,
                  style: TextStyle(
                      color: CommonColor.black,
                      fontSize: 14.sp,
                      height: 1.5,
                      fontFamily: Constant.manrope),
                ),
                if (isActionable) ...[
                  SizedBox(height: 5.h),
                  Text(
                    title == "Email" 
                        ? "Tap to mail" 
                        : title == "Phone"
                            ? "Tap to call"
                            : "Tap to open in Maps",
                    style: TextStyle(
                        color: Colors.grey,
                        fontSize: 10.sp,
                        fontStyle: FontStyle.italic,
                        fontFamily: Constant.manrope),
                  ),
                ]
              ],
            ),
          ),
        ],
      ),
    );
  }
}
