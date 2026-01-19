import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/live_sessions/model/live_session.dart';
import 'package:little_farmer/app/live_sessions/provider/live_session_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:cached_network_image/cached_network_image.dart';

class LiveSessionDetailScreen extends StatefulWidget {
  final LiveSession session;

  const LiveSessionDetailScreen({super.key, required this.session});

  @override
  State<LiveSessionDetailScreen> createState() => _LiveSessionDetailScreenState();
}

class _LiveSessionDetailScreenState extends State<LiveSessionDetailScreen> {
  final _formKey = GlobalKey<FormState>();
  
  void _launchWhatsApp() async {
    const url = "https://wa.me/971543202013";
    if (await canLaunchUrl(Uri.parse(url))) {
      await launchUrl(Uri.parse(url), mode: LaunchMode.externalApplication);
    } else {
      if(mounted) Utils.showSnackbarMessage(message: "Could not launch WhatsApp");
    }
  }

  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _schoolController = TextEditingController();
  final TextEditingController _ageController = TextEditingController();
  final TextEditingController _dateController = TextEditingController();

  late LiveSessionProvider provider;
  bool isTablet = false;

  @override
  void initState() {
    super.initState();
    provider = Provider.of<LiveSessionProvider>(context, listen: false);
    
    // Auto-calculate next date
    DateTime nextDate = provider.getNextThirdSaturday();
    _dateController.text = "${nextDate.year}-${nextDate.month.toString().padLeft(2,'0')}-${nextDate.day.toString().padLeft(2,'0')}";
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _schoolController.dispose();
    _ageController.dispose();
    _dateController.dispose();
    super.dispose();
  }

  void _showBookingSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return Container(
          height: MediaQuery.of(context).size.height * 0.85,
          decoration: BoxDecoration(
            color: CommonColor.white,
            borderRadius: BorderRadius.only(
              topLeft: Radius.circular(20.h),
              topRight: Radius.circular(20.h),
            ),
          ),
          padding: EdgeInsets.only(
            left: 20.w,
            right: 20.w,
            top: 20.h,
            bottom: MediaQuery.of(context).viewInsets.bottom + 20.h,
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    "Book Live Session",
                    style: TextStyle(
                      fontSize: 18.sp,
                      fontWeight: FontWeight.bold,
                      fontFamily: Constant.manrope,
                    ),
                  ),
                  IconButton(
                    icon: const Icon(Icons.close),
                    onPressed: () => Navigator.pop(context),
                  )
                ],
              ),
              Divider(color: CommonColor.grey_light),
              Expanded(
                child: SingleChildScrollView(
                  child: Form(
                    key: _formKey,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _buildLabel("Full Name *"),
                        _buildTextField(_nameController, "Enter your full name", TextInputType.name),
                        
                        _buildLabel("Email Address *"),
                        _buildTextField(_emailController, "Enter your email", TextInputType.emailAddress),
                        
                        _buildLabel("School/Organization *"),
                        _buildTextField(_schoolController, "Enter school name", TextInputType.text),
                        
                        _buildLabel("Age *"),
                        _buildTextField(_ageController, "Enter age", TextInputType.number),

                        _buildLabel("Next Live Session Date"),
                        TextFormField(
                            controller: _dateController,
                            readOnly: true,
                            style: TextStyle(fontSize: 14.sp, fontFamily: Constant.manrope, color: CommonColor.black),
                            decoration: InputDecoration(
                              filled: true,
                              fillColor: CommonColor.grey_light.withOpacity(0.3),
                              border: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(10.r),
                                borderSide: BorderSide.none,
                              ),
                              contentPadding: EdgeInsets.symmetric(horizontal: 15.w, vertical: 15.h),
                            ),
                          ),
                        SizedBox(height: 20.h),
                        
                        Consumer<LiveSessionProvider>(
                          builder: (context, provider, child) {
                            return SizedBox(
                              width: double.infinity,
                              height: 50.h,
                              child: ElevatedButton(
                                onPressed: provider.isBooking ? null : () {
                                  if (_formKey.currentState!.validate()) {
                                    provider.bookSession(
                                      name: _nameController.text,
                                      email: _emailController.text,
                                      school: _schoolController.text,
                                      age: _ageController.text,
                                      date: _dateController.text,
                                      courseName: widget.session.courseName,
                                      onSuccess: () {
                                        Navigator.pop(context);
                                      }
                                    );
                                  }
                                },
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: CommonColor.bg_button,
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(25.r),
                                  ),
                                ),
                                child: provider.isBooking 
                                  ? CircularProgressIndicator(color: CommonColor.white) 
                                  : Text(
                                      "Submit Booking",
                                      style: TextStyle(
                                        fontSize: 16.sp,
                                        fontWeight: FontWeight.bold,
                                        fontFamily: Constant.manrope,
                                        color: CommonColor.white
                                      ),
                                    ),
                              ),
                            );
                          },
                        ),
                      ],
                    ),
                  ),
                ),
              )
            ],
          ),
        );
      },
    );
  }

  Widget _buildLabel(String text) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: 8.h),
      child: Text(
        text,
        style: TextStyle(
          fontSize: 14.sp,
          fontWeight: FontWeight.w600,
          fontFamily: Constant.manrope,
          color: CommonColor.black,
        ),
      ),
    );
  }

  Widget _buildTextField(TextEditingController controller, String hint, TextInputType type) {
    return TextFormField(
      controller: controller,
      keyboardType: type,
      validator: (value) {
        if (value == null || value.isEmpty) {
          return "This field is required";
        }
        return null;
      },
      style: TextStyle(fontSize: 14.sp, fontFamily: Constant.manrope, color: CommonColor.black),
      decoration: InputDecoration(
        hintText: hint,
        hintStyle: TextStyle(color: CommonColor.grey, fontSize: 14.sp, fontFamily: Constant.manrope),
        filled: true,
        fillColor: CommonColor.white,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(10.r),
          borderSide: BorderSide(color: CommonColor.grey_light),
        ),
        contentPadding: EdgeInsets.symmetric(horizontal: 15.w, vertical: 15.h),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    isTablet = Utils.isTablet(context: context);
    
    return Scaffold(
        backgroundColor: CommonColor.bg_main,
        extendBodyBehindAppBar: false,
        floatingActionButton: FloatingActionButton(
          onPressed: _launchWhatsApp,
          backgroundColor: Colors.green,
          child: const FaIcon(FontAwesomeIcons.whatsapp, color: Colors.white),
        ),
        body: SafeArea(
          child: Column(
            children: [
            // Header
            Container(
              margin: EdgeInsets.all(10.h),
              child: Row(
                children: [
                  InkWell(
                    onTap: () => Navigator.pop(context),
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
                      "Course Details",
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
            
            // Main Content
            Expanded(
              child: SingleChildScrollView(
                padding: EdgeInsets.symmetric(horizontal: 16.w),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Hero Section - Image and Course Info
                    _buildHeroSection(),
                    SizedBox(height: 30.h),
                    
                    // Course Details Section
                    if (isTablet)
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // Left Column - Main Content
                          Expanded(
                            flex: 2,
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                _buildAboutCourseSection(),
                                SizedBox(height: 30.h),
                                if (widget.session.whatYouWillLearn != null) ...[
                                  _buildWhatYouWillLearnSection(),
                                  SizedBox(height: 30.h),
                                ],
                                if (widget.session.courseHighlights != null) ...[
                                  _buildCourseHighlightsSection(),
                                  SizedBox(height: 30.h),
                                ],
                              ],
                            ),
                          ),
                          SizedBox(width: 20.w),
                          // Right Column - Course Includes
                          Expanded(
                            flex: 1,
                            child: _buildCourseIncludesSection(),
                          ),
                        ],
                      )
                    else
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          _buildAboutCourseSection(),
                          SizedBox(height: 30.h),
                          if (widget.session.whatYouWillLearn != null) ...[
                            _buildWhatYouWillLearnSection(),
                            SizedBox(height: 30.h),
                          ],
                          if (widget.session.courseHighlights != null) ...[
                            _buildCourseHighlightsSection(),
                            SizedBox(height: 30.h),
                          ],
                          _buildCourseIncludesSection(),
                          SizedBox(height: 30.h),
                        ],
                      ),
                    
                    // Related Skills Section
                    if (widget.session.relatedSkills != null && widget.session.relatedSkills!.isNotEmpty) ...[
                      _buildRelatedSkillsSection(),
                      SizedBox(height: 30.h),
                    ],
                    
                    // Book Button
                    _buildBookButton(),
                    SizedBox(height: 100.h), // Space for floating button
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHeroSection() {
    return LayoutBuilder(
      builder: (context, constraints) {
        if (isTablet || constraints.maxWidth > 600) {
          // Tablet/Desktop: Side by side
          return Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Image on left
              Expanded(
                flex: 1,
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(15.r),
                  child: CachedNetworkImage(
                    imageUrl: widget.session.imageUrl,
                    width: double.infinity,
                    fit: BoxFit.cover,
                    placeholder: (context, url) => Container(
                      height: 300.h,
                      color: CommonColor.grey_light,
                      child: Center(child: CircularProgressIndicator(color: CommonColor.bg_button)),
                    ),
                    errorWidget: (context, url, error) => Container(
                      height: 300.h,
                      color: CommonColor.grey_light,
                      child: Center(child: Icon(Icons.error)),
                    ),
                  ),
                ),
              ),
              SizedBox(width: 20.w),
              // Content on right
              Expanded(
                flex: 1,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      widget.session.title,
                      style: TextStyle(
                        fontSize: 22.sp,
                        fontWeight: FontWeight.bold,
                        fontFamily: Constant.manrope,
                        color: CommonColor.black,
                        height: 1.3,
                      ),
                    ),
                    SizedBox(height: 15.h),
                    Text(
                      widget.session.description,
                      style: TextStyle(
                        fontSize: 15.sp,
                        fontFamily: Constant.manrope,
                        color: CommonColor.text_grey,
                        height: 1.6,
                      ),
                    ),
                    SizedBox(height: 20.h),
                    // Instructors
                    if (widget.session.instructors != null && widget.session.instructors!.isNotEmpty) ...[
                      ...widget.session.instructors!.map((instructor) => Padding(
                        padding: EdgeInsets.only(bottom: 10.h),
                        child: RichText(
                          text: TextSpan(
                            style: TextStyle(
                              fontSize: 14.sp,
                              fontFamily: Constant.manrope,
                              color: CommonColor.black,
                              height: 1.5,
                            ),
                            children: [
                              TextSpan(
                                text: "${instructor.name}, ${instructor.role}",
                                style: TextStyle(fontWeight: FontWeight.bold),
                              ),
                              TextSpan(text: " - ${instructor.description}"),
                            ],
                          ),
                        ),
                      )),
                      SizedBox(height: 20.h),
                    ],
                    // Book Button in hero
                    SizedBox(
                      width: double.infinity,
                      height: 50.h,
                      child: ElevatedButton(
                        onPressed: () => _showBookingSheet(context),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: CommonColor.bg_button,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(25.r),
                          ),
                        ),
                        child: Text(
                          "Book a Live Session",
                          style: TextStyle(
                            fontSize: 16.sp,
                            fontWeight: FontWeight.bold,
                            fontFamily: Constant.manrope,
                            color: CommonColor.white
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          );
        } else {
          // Mobile: Stacked
          return Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Image
              ClipRRect(
                borderRadius: BorderRadius.circular(15.r),
                child: CachedNetworkImage(
                  imageUrl: widget.session.imageUrl,
                  width: double.infinity,
                  height: 200.h,
                  fit: BoxFit.cover,
                  placeholder: (context, url) => Container(
                    height: 200.h,
                    color: CommonColor.grey_light,
                    child: Center(child: CircularProgressIndicator(color: CommonColor.bg_button)),
                  ),
                  errorWidget: (context, url, error) => Container(
                    height: 200.h,
                    color: CommonColor.grey_light,
                    child: Center(child: Icon(Icons.error)),
                  ),
                ),
              ),
              SizedBox(height: 20.h),
              // Title
              Text(
                widget.session.title,
                style: TextStyle(
                  fontSize: 20.sp,
                  fontWeight: FontWeight.bold,
                  fontFamily: Constant.manrope,
                  color: CommonColor.black,
                  height: 1.3,
                ),
              ),
              SizedBox(height: 15.h),
              // Age Group
              if (widget.session.ageGroup.isNotEmpty)
                Container(
                  padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 6.h),
                  decoration: BoxDecoration(
                    color: CommonColor.red.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(15.r),
                  ),
                  child: Text(
                    widget.session.ageGroup,
                    style: TextStyle(
                      color: CommonColor.red,
                      fontSize: 12.sp,
                      fontWeight: FontWeight.w600,
                      fontFamily: Constant.manrope,
                    ),
                  ),
                ),
              SizedBox(height: 15.h),
              // Description
              Text(
                widget.session.description,
                style: TextStyle(
                  fontSize: 14.sp,
                  fontFamily: Constant.manrope,
                  color: CommonColor.text_grey,
                  height: 1.6,
                ),
              ),
              SizedBox(height: 20.h),
              // Instructors
              if (widget.session.instructors != null && widget.session.instructors!.isNotEmpty) ...[
                ...widget.session.instructors!.map((instructor) => Padding(
                  padding: EdgeInsets.only(bottom: 10.h),
                  child: RichText(
                    text: TextSpan(
                      style: TextStyle(
                        fontSize: 14.sp,
                        fontFamily: Constant.manrope,
                        color: CommonColor.black,
                        height: 1.5,
                      ),
                      children: [
                        TextSpan(
                          text: "${instructor.name}, ${instructor.role}",
                          style: TextStyle(fontWeight: FontWeight.bold),
                        ),
                        TextSpan(text: " - ${instructor.description}"),
                      ],
                    ),
                  ),
                )),
                SizedBox(height: 20.h),
              ],
              // Book Button
              SizedBox(
                width: double.infinity,
                height: 50.h,
                child: ElevatedButton(
                  onPressed: () => _showBookingSheet(context),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: CommonColor.bg_button,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(25.r),
                    ),
                  ),
                  child: Text(
                    "Book a Live Session",
                    style: TextStyle(
                      fontSize: 16.sp,
                      fontWeight: FontWeight.bold,
                      fontFamily: Constant.manrope,
                      color: CommonColor.white
                    ),
                  ),
                ),
              ),
            ],
          );
        }
      },
    );
  }

  Widget _buildAboutCourseSection() {
    if (widget.session.aboutCourse == null || widget.session.aboutCourse!.isEmpty) {
      return SizedBox.shrink();
    }
    
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          "About the Course",
          style: TextStyle(
            fontSize: 20.sp,
            fontWeight: FontWeight.bold,
            fontFamily: Constant.manrope,
            color: CommonColor.black,
          ),
        ),
        SizedBox(height: 15.h),
        Text(
          widget.session.aboutCourse!,
          style: TextStyle(
            fontSize: 15.sp,
            fontFamily: Constant.manrope,
            color: CommonColor.text_grey,
            height: 1.7,
          ),
        ),
      ],
    );
  }

  Widget _buildWhatYouWillLearnSection() {
    if (widget.session.whatYouWillLearn == null || widget.session.whatYouWillLearn!.isEmpty) {
      return SizedBox.shrink();
    }
    
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          "What Your Child Will Learn",
          style: TextStyle(
            fontSize: 20.sp,
            fontWeight: FontWeight.bold,
            fontFamily: Constant.manrope,
            color: CommonColor.black,
          ),
        ),
        SizedBox(height: 15.h),
        ...widget.session.whatYouWillLearn!.asMap().entries.map((entry) {
          int index = entry.key;
          String item = entry.value;
          
          // Different icons for different items (matching website)
          // Detect course type by checking title
          bool isRobotics = widget.session.title.toLowerCase().contains('robotics');
          IconData icon;
          
          if (isRobotics) {
            // Robotics course icons
            if (index == 0) {
              icon = Icons.smart_toy_outlined; // Robot
            } else if (index == 1) {
              icon = Icons.settings_outlined; // Gear/Cogs
            } else if (index == 2) {
              icon = Icons.laptop_outlined; // Laptop/Code
            } else if (index == 3) {
              icon = Icons.agriculture_outlined; // Tractor
            } else {
              icon = Icons.build_outlined; // Building blocks/Prototype
            }
          } else {
            // AI Agriculture course icons
            if (index == 0) {
              icon = Icons.psychology_outlined; // Robot/AI
            } else if (index == 1) {
              icon = Icons.device_thermostat_outlined; // Thermometer
            } else if (index == 2) {
              icon = Icons.camera_alt_outlined; // Camera
            } else if (index == 3) {
              icon = Icons.psychology_outlined; // Brain
            } else {
              icon = Icons.bar_chart_outlined; // Bar chart
            }
          }
          
          return Padding(
            padding: EdgeInsets.only(bottom: 12.h),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Icon(icon, color: CommonColor.bg_button, size: 20.sp),
                SizedBox(width: 12.w),
                Expanded(
                  child: Text(
                    item,
                    style: TextStyle(
                      fontSize: 15.sp,
                      fontFamily: Constant.manrope,
                      color: CommonColor.black,
                      height: 1.6,
                    ),
                  ),
                ),
              ],
            ),
          );
        }),
      ],
    );
  }

  Widget _buildCourseHighlightsSection() {
    if (widget.session.courseHighlights == null || widget.session.courseHighlights!.isEmpty) {
      return SizedBox.shrink();
    }
    
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          "Course Highlights",
          style: TextStyle(
            fontSize: 20.sp,
            fontWeight: FontWeight.bold,
            fontFamily: Constant.manrope,
            color: CommonColor.black,
          ),
        ),
        SizedBox(height: 15.h),
        ...widget.session.courseHighlights!.map((item) => Padding(
          padding: EdgeInsets.only(bottom: 12.h),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Icon(Icons.check_circle, color: Colors.green, size: 20.sp),
              SizedBox(width: 12.w),
              Expanded(
                child: Text(
                  item,
                  style: TextStyle(
                    fontSize: 15.sp,
                    fontFamily: Constant.manrope,
                    color: CommonColor.black,
                    height: 1.6,
                  ),
                ),
              ),
            ],
          ),
        )),
      ],
    );
  }

  Widget _buildCourseIncludesSection() {
    if (widget.session.courseIncludes == null || widget.session.courseIncludes!.isEmpty) {
      return SizedBox.shrink();
    }
    
    return Container(
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
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            "Course Includes",
            style: TextStyle(
              fontSize: 20.sp,
              fontWeight: FontWeight.bold,
              fontFamily: Constant.manrope,
              color: CommonColor.black,
            ),
          ),
          SizedBox(height: 15.h),
          Text(
            widget.session.title.toLowerCase().contains('robotics')
                ? "Discover how robotics is changing modern agriculture. Kids explore how robots help farmers save time, reduce effort, and improve yields. The course combines mechanical systems, sensors, and coding to teach children the logic behind automated farming. Ideal for young tech enthusiasts and future engineers!"
                : "Explore how AI transforms farming using real-world sensor data and plant images. Kids learn by doing: cleaning data, labeling images, and testing simple models to understand how AI detects plant stress and supports healthy growth.",
            style: TextStyle(
              fontSize: 14.sp,
              fontFamily: Constant.manrope,
              color: CommonColor.text_grey,
              height: 1.6,
            ),
          ),
          SizedBox(height: 20.h),
          ...widget.session.courseIncludes!.map((item) => Padding(
            padding: EdgeInsets.only(bottom: 15.h),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                CachedNetworkImage(
                  imageUrl: item.iconUrl,
                  width: 24.w,
                  height: 24.w,
                  errorWidget: (context, url, error) => Icon(
                    Icons.circle_outlined,
                    size: 24.sp,
                    color: CommonColor.bg_button,
                  ),
                ),
                SizedBox(width: 12.w),
                Expanded(
                  child: Text(
                    item.text,
                    style: TextStyle(
                      fontSize: 15.sp,
                      fontFamily: Constant.manrope,
                      color: CommonColor.black,
                      height: 1.5,
                    ),
                  ),
                ),
              ],
            ),
          )),
        ],
      ),
    );
  }

  Widget _buildRelatedSkillsSection() {
    if (widget.session.relatedSkills == null || widget.session.relatedSkills!.isEmpty) {
      return SizedBox.shrink();
    }
    
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          "Related Skills",
          style: TextStyle(
            fontSize: 20.sp,
            fontWeight: FontWeight.bold,
            fontFamily: Constant.manrope,
            color: CommonColor.black,
          ),
        ),
        SizedBox(height: 15.h),
        Wrap(
          spacing: 10.w,
          runSpacing: 10.h,
          children: widget.session.relatedSkills!.map((skill) => Container(
            padding: EdgeInsets.symmetric(horizontal: 15.w, vertical: 8.h),
            decoration: BoxDecoration(
              color: CommonColor.bg_button.withOpacity(0.1),
              borderRadius: BorderRadius.circular(20.r),
              border: Border.all(color: CommonColor.bg_button.withOpacity(0.3)),
            ),
            child: Text(
              skill,
              style: TextStyle(
                fontSize: 13.sp,
                fontFamily: Constant.manrope,
                color: CommonColor.bg_button,
                fontWeight: FontWeight.w500,
              ),
            ),
          )).toList(),
        ),
      ],
    );
  }

  Widget _buildBookButton() {
    if (!isTablet) {
      // Already shown in hero section on mobile
      return SizedBox.shrink();
    }
    
    return SizedBox(
      width: double.infinity,
      height: 50.h,
      child: ElevatedButton(
        onPressed: () => _showBookingSheet(context),
        style: ElevatedButton.styleFrom(
          backgroundColor: CommonColor.bg_button,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(25.r),
          ),
        ),
        child: Text(
          "Book a Live Session",
          style: TextStyle(
            fontSize: 16.sp,
            fontWeight: FontWeight.bold,
            fontFamily: Constant.manrope,
            color: CommonColor.white
          ),
        ),
      ),
    );
  }
}
