import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:little_farmer/app/all_courses/ui/all_courses_screen.dart';
import 'package:little_farmer/app/profile/ui/contact_screen.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/website_images.dart';

class AboutScreen extends StatelessWidget {
  const AboutScreen({super.key});

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
                  Expanded(
                    child: Text(
                      "About Us",
                      textAlign: TextAlign.center,
                      style: TextStyle(
                          color: CommonColor.black,
                          fontSize: 18.sp,
                          fontWeight: FontWeight.w900,
                          fontFamily: Constant.manrope),
                    ),
                  ),
                ],
              ),
            ),
            Expanded(
              child: SingleChildScrollView(
                padding: EdgeInsets.fromLTRB(15.w, 0, 15.w, 100.h), // Add bottom padding for navbar
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Main About Section
                    Text(
                      "Top agriculture classes for children: modern farming & Robotics",
                      style: TextStyle(
                          color: CommonColor.black,
                          fontSize: 22.sp,
                          fontWeight: FontWeight.w900,
                          fontFamily: Constant.manrope),
                    ),
                    SizedBox(height: 15.h),
                    // About Images Row
                    Row(
                      children: [
                        Expanded(
                          child: ClipRRect(
                            borderRadius: BorderRadius.circular(50.r),
                            child: CachedNetworkImage(
                              imageUrl: WebsiteImages.aboutImage03,
                              height: 100.h,
                              width: 100.h,
                              fit: BoxFit.cover,
                              placeholder: (context, url) => Container(
                                height: 100.h,
                                alignment: Alignment.center,
                                child: CircularProgressIndicator(
                                  color: CommonColor.bg_button,
                                  strokeWidth: 2,
                                ),
                              ),
                              errorWidget: (context, url, error) => Container(
                                height: 100.h,
                                color: Colors.grey[300],
                                child: const Icon(Icons.person, color: Colors.grey),
                              ),
                            ),
                          ),
                        ),
                        SizedBox(width: 10.w),
                        Expanded(
                          child: ClipRRect(
                            borderRadius: BorderRadius.circular(50.r),
                            child: CachedNetworkImage(
                              imageUrl: WebsiteImages.aboutImage04,
                              height: 100.h,
                              width: 100.h,
                              fit: BoxFit.cover,
                              placeholder: (context, url) => Container(
                                height: 100.h,
                                alignment: Alignment.center,
                                child: CircularProgressIndicator(
                                  color: CommonColor.bg_button,
                                  strokeWidth: 2,
                                ),
                              ),
                              errorWidget: (context, url, error) => Container(
                                height: 100.h,
                                color: Colors.grey[300],
                                child: const Icon(Icons.person, color: Colors.grey),
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                    SizedBox(height: 20.h),
                    RichText(
                      text: TextSpan(
                        style: TextStyle(
                          color: CommonColor.black,
                          fontSize: 16.sp,
                          fontWeight: FontWeight.bold,
                          fontFamily: Constant.manrope,
                        ),
                        children: [
                          TextSpan(text: "Learn "),
                          TextSpan(
                            text: "Sustainable Farming",
                            style: TextStyle(color: CommonColor.bg_button),
                          ),
                          TextSpan(text: " with Expert Guidance"),
                        ],
                      ),
                    ),
                    SizedBox(height: 20.h),
                    Text(
                      "To inspire kids and adults through sustainable farming education, integrating robotics technology and innovative gardening practices.",
                      style: TextStyle(
                          color: CommonColor.black,
                          fontSize: 16.sp,
                          height: 1.6,
                          fontFamily: Constant.manrope),
                    ),
                    SizedBox(height: 15.h),
                    Text(
                      "Through online farming courses and modern agricultural technology, delivered by highly qualified educators, to improve kids' farming knowledge and skills.",
                      style: TextStyle(
                          color: CommonColor.black,
                          fontSize: 16.sp,
                          height: 1.6,
                          fontFamily: Constant.manrope),
                    ),
                    SizedBox(height: 25.h),
                    
                    // See Our Courses in Action
                    Text(
                      "See Our Courses in Action",
                      style: TextStyle(
                          color: CommonColor.black,
                          fontSize: 16.sp,
                          fontWeight: FontWeight.bold,
                          fontFamily: Constant.manrope),
                    ),
                    SizedBox(height: 12.h),
                    SizedBox(
                      width: double.infinity,
                      height: 45.h,
                      child: ElevatedButton(
                        onPressed: () {
                          // Navigate to All Courses screen
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
                          "Explore Courses",
                          style: TextStyle(
                            color: CommonColor.white,
                            fontSize: 15.sp,
                            fontWeight: FontWeight.w600,
                            fontFamily: Constant.manrope,
                          ),
                        ),
                      ),
                    ),
                    SizedBox(height: 40.h),
                    
                    // Instructors Section
                    Container(
                      padding: EdgeInsets.all(20.w),
                      decoration: BoxDecoration(
                        color: CommonColor.bg_button.withOpacity(0.05),
                        borderRadius: BorderRadius.circular(15.r),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            "Our Instructors",
                            style: TextStyle(
                                color: CommonColor.bg_button,
                                fontSize: 16.sp,
                                fontWeight: FontWeight.bold,
                                fontFamily: Constant.manrope),
                          ),
                          SizedBox(height: 10.h),
                          Text(
                            "Meet Our Team",
                            style: TextStyle(
                                color: CommonColor.black,
                                fontSize: 24.sp,
                                fontWeight: FontWeight.w900,
                                fontFamily: Constant.manrope),
                          ),
                          SizedBox(height: 15.h),
                          Text(
                            "Our instructors bring a wealth of experience and expertise in their respective fields, ensuring every course offers valuable insights and hands-on learning opportunities.",
                            style: TextStyle(
                                color: CommonColor.black,
                                fontSize: 15.sp,
                                height: 1.6,
                                fontFamily: Constant.manrope),
                          ),
                        ],
                      ),
                    ),
                    SizedBox(height: 25.h),
                    
                    // Instructor Cards
                    _buildInstructorCard(
                      name: "Praveen P",
                      role: "Data Scientist & Robotics Expert",
                      description:
                          "Specializes in AI-driven robotics solutions for agriculture, focusing on predictive analysis, automation, and real-time data insights to optimize farming practices.",
                    ),
                    SizedBox(height: 15.h),
                    _buildInstructorCard(
                      name: "Subarna V",
                      role: "Agriculturist",
                      description:
                          "Expert in integrating robotics and AI into traditional farming practices to boost productivity and sustainability. A passionate advocate for educating young minds about agriculture.",
                    ),
                    SizedBox(height: 15.h),
                    _buildInstructorCard(
                      name: "Mensilla M",
                      role: "Food Scientist",
                      description:
                          "Specializes in post-harvest processing and food technology. Focuses on utilizing technology to ensure quality and sustainability in food production.",
                    ),
                    SizedBox(height: 15.h),
                    _buildInstructorCard(
                      name: "Mohaimin Kader",
                      role: "Animation & Design Lead",
                      description:
                          "Leads the creative team in developing interactive and visually appealing educational content for our courses, ensuring an engaging learning experience for kids and adults alike.",
                    ),
                    SizedBox(height: 15.h),
                    _buildInstructorCard(
                      name: "Yash Chhalotre",
                      role: "Robotics Specialist",
                      description:
                          "Focuses on integrating automation and data analytics into modern farming education. Designs interactive learning tools and robotics modules that teach the fundamentals of precision agriculture and smart farming technology.",
                    ),
                    SizedBox(height: 15.h),
                    _buildInstructorCard(
                      name: "Mohsin Kader",
                      role: "Operations Strategist",
                      description:
                          "Oversees the planning and execution of educational farming projects and community initiatives. Ensures smooth coordination between teams, partners, and students to create impactful learning experiences that promote sustainable agriculture and innovation.",
                    ),
                    SizedBox(height: 15.h),
                    _buildInstructorCard(
                      name: "Aravind A S",
                      role: "Graphic Designer",
                      description:
                          "Creates engaging visual content for social media, websites, and course materials. Brings the world of agriculture and innovation to life through design, ensuring the Little Farmers brand inspires creativity and curiosity across all platforms.",
                    ),
                    SizedBox(height: 30.h),
                    
                    // Learn from Our Expert Team
                    Container(
                      width: double.infinity,
                      padding: EdgeInsets.all(20.w),
                      decoration: BoxDecoration(
                        color: CommonColor.white,
                        borderRadius: BorderRadius.circular(15.r),
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
                            "Learn from Our Expert Team",
                            style: TextStyle(
                                color: CommonColor.black,
                                fontSize: 20.sp,
                                fontWeight: FontWeight.bold,
                                fontFamily: Constant.manrope),
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
                                backgroundColor: Colors.transparent,
                                side: BorderSide(color: CommonColor.bg_button, width: 2),
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(10.r),
                                ),
                                elevation: 0,
                              ),
                              child: Text(
                                "View Our Courses",
                                style: TextStyle(
                                  color: CommonColor.bg_button,
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
                    SizedBox(height: 30.h),
                    
                    // Join Community Section
                    Container(
                      width: double.infinity,
                      padding: EdgeInsets.all(25.w),
                      decoration: BoxDecoration(
                        color: Color(0xFFF7FCF8),
                        borderRadius: BorderRadius.circular(15.r),
                      ),
                      child: Column(
                        children: [
                          Text(
                            "Join Our Growing Community of Little Farmers",
                            textAlign: TextAlign.center,
                            style: TextStyle(
                                color: CommonColor.black,
                                fontSize: 22.sp,
                                fontWeight: FontWeight.w900,
                                fontFamily: Constant.manrope),
                          ),
                          SizedBox(height: 25.h),
                          
                          // Statistics
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                            children: [
                              Expanded(
                                child: Column(
                                  children: [
                                    Icon(Icons.people_outline, color: CommonColor.bg_button, size: 30.h),
                                    SizedBox(height: 8.h),
                                    Text(
                                      "25,000+",
                                      style: TextStyle(
                                          color: CommonColor.black,
                                          fontSize: 16.sp,
                                          fontWeight: FontWeight.bold,
                                          fontFamily: Constant.manrope),
                                    ),
                                    Text(
                                      "Happy Learners",
                                      textAlign: TextAlign.center,
                                      style: TextStyle(
                                          color: CommonColor.black,
                                          fontSize: 12.sp,
                                          fontFamily: Constant.manrope),
                                    ),
                                  ],
                                ),
                              ),
                              Expanded(
                                child: Column(
                                  children: [
                                    Icon(Icons.menu_book_outlined, color: CommonColor.bg_button, size: 30.h),
                                    SizedBox(height: 8.h),
                                    Text(
                                      "20+",
                                      style: TextStyle(
                                          color: CommonColor.black,
                                          fontSize: 16.sp,
                                          fontWeight: FontWeight.bold,
                                          fontFamily: Constant.manrope),
                                    ),
                                    Text(
                                      "Farming Lessons",
                                      textAlign: TextAlign.center,
                                      style: TextStyle(
                                          color: CommonColor.black,
                                          fontSize: 12.sp,
                                          fontFamily: Constant.manrope),
                                    ),
                                  ],
                                ),
                              ),
                              Expanded(
                                child: Column(
                                  children: [
                                    Icon(Icons.all_inclusive, color: CommonColor.bg_button, size: 30.h),
                                    SizedBox(height: 8.h),
                                    Text(
                                      "Lifetime",
                                      style: TextStyle(
                                          color: CommonColor.black,
                                          fontSize: 16.sp,
                                          fontWeight: FontWeight.bold,
                                          fontFamily: Constant.manrope),
                                    ),
                                    Text(
                                      "Access",
                                      textAlign: TextAlign.center,
                                      style: TextStyle(
                                          color: CommonColor.black,
                                          fontSize: 12.sp,
                                          fontFamily: Constant.manrope),
                                    ),
                                  ],
                                ),
                              ),
                            ],
                          ),
                          SizedBox(height: 25.h),
                          
                          // Buttons
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
                                "Start Your Free Trial",
                                style: TextStyle(
                                  color: CommonColor.white,
                                  fontSize: 15.sp,
                                  fontWeight: FontWeight.w600,
                                  fontFamily: Constant.manrope,
                                ),
                              ),
                            ),
                          ),
                          SizedBox(height: 12.h),
                          SizedBox(
                            width: double.infinity,
                            height: 45.h,
                            child: ElevatedButton(
                              onPressed: () {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (context) => const ContactScreen(),
                                  ),
                                );
                              },
                              style: ElevatedButton.styleFrom(
                                backgroundColor: Colors.transparent,
                                side: BorderSide(color: CommonColor.bg_button, width: 2),
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(10.r),
                                ),
                                elevation: 0,
                              ),
                              child: Text(
                                "Contact Us for Guidance",
                                style: TextStyle(
                                  color: CommonColor.bg_button,
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
                    SizedBox(height: 30.h),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildInstructorCard({
    required String name,
    required String role,
    required String description,
  }) {
    final instructorImage = WebsiteImages.getInstructorImage(name);
    
    return Container(
      padding: EdgeInsets.all(15.w),
      decoration: BoxDecoration(
        color: CommonColor.white,
        borderRadius: BorderRadius.circular(12.r),
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
          // Instructor Image
          if (instructorImage != null)
            ClipRRect(
              borderRadius: BorderRadius.circular(50.r),
              child: CachedNetworkImage(
                imageUrl: instructorImage,
                height: 80.h,
                width: 80.h,
                fit: BoxFit.cover,
                placeholder: (context, url) => Container(
                  height: 80.h,
                  width: 80.h,
                  alignment: Alignment.center,
                  child: CircularProgressIndicator(
                    color: CommonColor.bg_button,
                    strokeWidth: 2,
                  ),
                ),
                errorWidget: (context, url, error) => Container(
                  height: 80.h,
                  width: 80.h,
                  color: Colors.grey[300],
                  child: const Icon(Icons.person, color: Colors.grey),
                ),
              ),
            ),
          if (instructorImage != null) SizedBox(width: 15.w),
          // Instructor Details
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  name,
                  style: TextStyle(
                      color: CommonColor.black,
                      fontSize: 18.sp,
                      fontWeight: FontWeight.bold,
                      fontFamily: Constant.manrope),
                ),
                SizedBox(height: 5.h),
                Text(
                  role,
                  style: TextStyle(
                      color: CommonColor.bg_button,
                      fontSize: 14.sp,
                      fontWeight: FontWeight.w600,
                      fontFamily: Constant.manrope),
                ),
                SizedBox(height: 10.h),
                Text(
                  description,
                  style: TextStyle(
                      color: CommonColor.black,
                      fontSize: 14.sp,
                      height: 1.5,
                      fontFamily: Constant.manrope),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

