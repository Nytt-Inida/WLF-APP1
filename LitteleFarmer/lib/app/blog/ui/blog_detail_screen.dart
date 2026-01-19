import 'package:cached_network_image/cached_network_image.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';
import 'package:intl/intl.dart';
import 'package:little_farmer/app/all_courses/ui/all_courses_screen.dart';
import 'package:little_farmer/app/blog/model/blog_model.dart';
import 'package:little_farmer/app/blog/provider/blog_provider.dart';
import 'package:little_farmer/app/course_detail/ui/course_detail_screen.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';

class BlogDetailScreen extends StatefulWidget {
  final int blogId;
  const BlogDetailScreen({super.key, required this.blogId});

  @override
  State<BlogDetailScreen> createState() => _BlogDetailScreenState();
}

class _BlogDetailScreenState extends State<BlogDetailScreen> {
  BlogModel? blog;
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _fetchDetail();
    });
  }

  Future<void> _fetchDetail() async {
    final provider = context.read<BlogProvider>();
    final result = await provider.fetchBlogDetail(widget.blogId);
    if (mounted) {
      setState(() {
        blog = result;
        isLoading = false;
      });
    }
  }

  String _formatDate(String? dateStr) {
    if (dateStr == null) return "";
    try {
      DateTime date = DateTime.parse(dateStr);
      return DateFormat('MMMM d, yyyy').format(date);
    } catch (e) {
      return dateStr;
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
            // Header
            Container(
              margin: EdgeInsets.all(10.h),
              child: Row(
                children: [
                  Material(
                    color: Colors.transparent,
                    child: InkWell(
                      borderRadius: BorderRadius.circular(50.h),
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
                  ),
                  Expanded(
                    child: Text(
                      "Blog",
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
              child: isLoading
                  ? Center(child: CircularProgressIndicator(color: CommonColor.bg_button))
                  : blog == null
                      ? Center(
                          child: Text(
                            "Failed to load blog.",
                            style: TextStyle(fontFamily: Constant.manrope),
                          ),
                        )
                      : Consumer<BlogProvider>(
                          builder: (context, provider, _) {
                            return SingleChildScrollView(
                              padding: EdgeInsets.fromLTRB(15.w, 0, 15.w, 100.h),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  // Title (first, before image)
                                  Text(
                                    blog!.title,
                                    style: TextStyle(
                                      color: CommonColor.black,
                                      fontSize: 24.sp,
                                      fontWeight: FontWeight.w900,
                                      fontFamily: Constant.manrope,
                                    ),
                                  ),
                                  SizedBox(height: 20.h),
                                  
                                  // Featured Image
                                  if (blog!.featuredImage != null)
                                    ClipRRect(
                                      borderRadius: BorderRadius.circular(15.r),
                                      child: CachedNetworkImage(
                                        imageUrl: blog!.featuredImage ?? "",
                                        width: double.infinity,
                                        height: 250.h,
                                        fit: BoxFit.cover,
                                        placeholder: (context, url) => Container(
                                          height: 250.h,
                                          color: Colors.grey[200],
                                          child: Center(
                                            child: CircularProgressIndicator(
                                              color: CommonColor.bg_button,
                                            ),
                                          ),
                                        ),
                                        errorWidget: (context, url, error) =>
                                            Container(
                                          height: 250.h,
                                          color: Colors.grey[300],
                                          child: Icon(Icons.broken_image, size: 50.h),
                                        ),
                                      ),
                                    ),
                                  
                                  SizedBox(height: 20.h),
                                  
                                  // Meta Information (Date and Views)
                                  Row(
                                    children: [
                                      Icon(Icons.calendar_today_outlined,
                                          size: 16.sp, color: Colors.grey),
                                      SizedBox(width: 5.w),
                                      Text(
                                        _formatDate(blog!.publishedAt),
                                        style: TextStyle(
                                          color: Colors.grey,
                                          fontSize: 14.sp,
                                          fontFamily: Constant.manrope,
                                        ),
                                      ),
                                      SizedBox(width: 20.w),
                                      Icon(Icons.remove_red_eye_outlined,
                                          size: 16.sp, color: Colors.grey),
                                      SizedBox(width: 5.w),
                                      Text(
                                        "${blog!.views ?? 0} views",
                                        style: TextStyle(
                                          color: Colors.grey,
                                          fontSize: 14.sp,
                                          fontFamily: Constant.manrope,
                                        ),
                                      ),
                                    ],
                                  ),
                                  
                                  SizedBox(height: 20.h),
                                  
                                  // Excerpt
                                  if (blog!.excerpt != null && blog!.excerpt!.isNotEmpty)
                                    Container(
                                      padding: EdgeInsets.all(15.w),
                                      decoration: BoxDecoration(
                                        color: CommonColor.bg_button.withOpacity(0.1),
                                        borderRadius: BorderRadius.circular(10.r),
                                      ),
                                      child: HtmlWidget(
                                        blog!.excerpt!,
                                        textStyle: TextStyle(
                                          fontFamily: Constant.manrope,
                                          fontSize: 17.sp,
                                          height: 1.6,
                                          color: CommonColor.black,
                                          fontStyle: FontStyle.italic,
                                        ),
                                      ),
                                    ),
                                  
                                  if (blog!.excerpt != null && blog!.excerpt!.isNotEmpty)
                                    SizedBox(height: 25.h),
                                  
                                  // Blog Content
                                  HtmlWidget(
                                    blog!.content ?? "",
                                    textStyle: TextStyle(
                                      fontFamily: Constant.manrope,
                                      fontSize: 16.sp,
                                      height: 1.6,
                                      color: CommonColor.black,
                                    ),
                                  ),
                                  
                                  SizedBox(height: 25.h),
                                  
                                  // In-Article CTA (after content)
                                  _buildInArticleCTA(),
                                  
                                  SizedBox(height: 30.h),
                                  
                                  // Final Call to Action
                                  _buildFinalCTA(),
                                  
                                  SizedBox(height: 30.h),
                                  
                                  // Sidebar Content (Mobile: Below main content)
                                  _buildSidebarContent(provider),
                                  
                                  SizedBox(height: 30.h),
                                ],
                              ),
                            );
                          },
                        ),
            ),
          ],
        ),
      ),
    );
  }

  // In-article CTA (injected after 2nd paragraph)
  Widget _buildInArticleCTA() {
    return Container(
      padding: EdgeInsets.all(15.w),
      decoration: BoxDecoration(
        color: Color(0xFFF0F7FF),
        border: Border(left: BorderSide(color: CommonColor.bg_button, width: 5.w)),
        borderRadius: BorderRadius.circular(10.r),
      ),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  "Want your child to learn these skills hands-on?",
                  style: TextStyle(
                    color: Color(0xFF0056B3),
                    fontSize: 15.sp,
                    fontWeight: FontWeight.bold,
                    fontFamily: Constant.manrope,
                  ),
                ),
                SizedBox(height: 5.h),
                Text(
                  "Explore our interactive farming courses designed for kids!",
                  style: TextStyle(
                    color: CommonColor.black,
                    fontSize: 13.sp,
                    fontFamily: Constant.manrope,
                  ),
                ),
              ],
            ),
          ),
          SizedBox(width: 10.w),
          ElevatedButton(
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
                borderRadius: BorderRadius.circular(20.r),
              ),
              padding: EdgeInsets.symmetric(horizontal: 15.w, vertical: 8.h),
            ),
            child: Text(
              "Explore Courses",
              style: TextStyle(
                color: CommonColor.white,
                fontSize: 12.sp,
                fontWeight: FontWeight.w600,
                fontFamily: Constant.manrope,
              ),
            ),
          ),
        ],
      ),
    );
  }

  // Final Call to Action
  Widget _buildFinalCTA() {
    return Container(
      padding: EdgeInsets.all(25.w),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [
            Color(0xFFE3F2FD),
            Colors.white,
          ],
        ),
        borderRadius: BorderRadius.circular(15.r),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.1),
            blurRadius: 15,
            offset: Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        children: [
          Text(
            "Ready to Turn Learning into Action?",
            textAlign: TextAlign.center,
            style: TextStyle(
              color: CommonColor.black,
              fontSize: 20.sp,
              fontWeight: FontWeight.bold,
              fontFamily: Constant.manrope,
            ),
          ),
          SizedBox(height: 10.h),
          Text(
            "Join 25,000+ kids learning farming, sustainability & food science.",
            textAlign: TextAlign.center,
            style: TextStyle(
              color: Color(0xFF555555),
              fontSize: 16.sp,
              fontFamily: Constant.manrope,
            ),
          ),
          SizedBox(height: 20.h),
          SizedBox(
            width: double.infinity,
            height: 50.h,
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
                  borderRadius: BorderRadius.circular(25.r),
                ),
                elevation: 3,
              ),
              child: Text(
                "Explore Our Courses",
                style: TextStyle(
                  color: CommonColor.white,
                  fontSize: 16.sp,
                  fontWeight: FontWeight.bold,
                  fontFamily: Constant.manrope,
                ),
              ),
            ),
          ),
          SizedBox(height: 10.h),
          Text(
            "First lesson free!",
            style: TextStyle(
              color: Colors.grey,
              fontSize: 13.sp,
              fontFamily: Constant.manrope,
            ),
          ),
        ],
      ),
    );
  }

  // Sidebar Content
  Widget _buildSidebarContent(BlogProvider provider) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // New to Little Farmers? Card
        Container(
          width: double.infinity,
          padding: EdgeInsets.all(15.w),
          decoration: BoxDecoration(
            color: CommonColor.white,
            border: Border.all(color: Colors.grey.shade200),
            borderRadius: BorderRadius.circular(10.r),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.05),
                blurRadius: 20,
                offset: Offset(0, 5),
              ),
            ],
          ),
          child: Column(
            children: [
              Text(
                "New to Little Farmers?",
                textAlign: TextAlign.center,
                style: TextStyle(
                  color: CommonColor.black,
                  fontSize: 18.sp,
                  fontWeight: FontWeight.bold,
                  fontFamily: Constant.manrope,
                ),
              ),
              SizedBox(height: 10.h),
              ClipRRect(
                borderRadius: BorderRadius.circular(10.r),
                child: CachedNetworkImage(
                  imageUrl: "https://welittlefarmers.com/assets/img/blog/recent-1.jpg",
                  width: double.infinity,
                  height: 180.h,
                  fit: BoxFit.cover,
                  errorWidget: (context, url, error) => Container(
                    height: 180.h,
                    color: Colors.grey[200],
                    child: Icon(Icons.image, size: 50.h),
                  ),
                ),
              ),
              SizedBox(height: 12.h),
              Text(
                "Start with our best-selling \"The Little Farmer Online Course\"!",
                textAlign: TextAlign.center,
                style: TextStyle(
                  color: Colors.grey.shade700,
                  fontSize: 14.sp,
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
                        builder: (context) => CourseDetailScreen(
                          courseId: 1,
                          title: "The Little Farmer Online Course",
                          price: "₹2,500",
                        ),
                      ),
                    );
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.green,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(25.r),
                    ),
                  ),
                  child: Text(
                    "Enroll Now",
                    style: TextStyle(
                      color: CommonColor.white,
                      fontSize: 15.sp,
                      fontWeight: FontWeight.bold,
                      fontFamily: Constant.manrope,
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
        
        SizedBox(height: 25.h),
        
        // Recent Posts
        Text(
          "Recent Posts",
          style: TextStyle(
            color: CommonColor.black,
            fontSize: 20.sp,
            fontWeight: FontWeight.bold,
            fontFamily: Constant.manrope,
          ),
        ),
        SizedBox(height: 15.h),
        
        // Show related blogs from API or fetch recent
        if (provider.relatedBlogs.isNotEmpty)
          ...provider.relatedBlogs.take(3).map((recentBlog) {
            return Container(
              margin: EdgeInsets.only(bottom: 15.h),
              child: Material(
                color: Colors.transparent,
                child: InkWell(
                  borderRadius: BorderRadius.circular(10.r),
                  onTap: () {
                    Navigator.pushReplacement(
                      context,
                      MaterialPageRoute(
                        builder: (context) => BlogDetailScreen(blogId: recentBlog.id),
                      ),
                    );
                  },
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      if (recentBlog.featuredImage != null)
                        ClipRRect(
                          borderRadius: BorderRadius.circular(10.r),
                          child: CachedNetworkImage(
                            imageUrl: recentBlog.featuredImage ?? "",
                            width: double.infinity,
                            height: 150.h,
                            fit: BoxFit.cover,
                            errorWidget: (context, url, error) => Container(
                              height: 150.h,
                              color: Colors.grey[200],
                            ),
                          ),
                        ),
                      SizedBox(height: 8.h),
                      Text(
                        _formatDate(recentBlog.publishedAt),
                        style: TextStyle(
                          color: Colors.grey,
                          fontSize: 12.sp,
                          fontFamily: Constant.manrope,
                        ),
                      ),
                      SizedBox(height: 5.h),
                      Text(
                        recentBlog.title,
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: TextStyle(
                          color: CommonColor.black,
                          fontSize: 16.sp,
                          fontWeight: FontWeight.bold,
                          fontFamily: Constant.manrope,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            );
          }).toList(),
        
        SizedBox(height: 25.h),
        
        // Social Media
        Text(
          "Follow Us on Social Media",
          style: TextStyle(
            color: CommonColor.black,
            fontSize: 20.sp,
            fontWeight: FontWeight.bold,
            fontFamily: Constant.manrope,
          ),
        ),
        SizedBox(height: 15.h),
        Row(
          children: [
            Material(
              color: Colors.transparent,
              child: InkWell(
                borderRadius: BorderRadius.circular(50.h),
                onTap: () async {
                  final url = Uri.parse(
                      "https://www.linkedin.com/in/we-little-farmer-4bbb18380");
                  if (await canLaunchUrl(url)) {
                    await launchUrl(url, mode: LaunchMode.externalApplication);
                  }
                },
                child: Container(
                  width: 50.h,
                  height: 50.h,
                  decoration: BoxDecoration(
                    color: CommonColor.bg_button,
                    shape: BoxShape.circle,
                  ),
                  alignment: Alignment.center,
                  child: FaIcon(FontAwesomeIcons.linkedin,
                      color: CommonColor.white, size: 24.h),
                ),
              ),
            ),
            SizedBox(width: 15.w),
            Material(
              color: Colors.transparent,
              child: InkWell(
                borderRadius: BorderRadius.circular(50.h),
                onTap: () async {
                  final url = Uri.parse(
                      "https://www.instagram.com/welittlefarmer/?igsh=ODB0eHE5eXBsajF3#");
                  if (await canLaunchUrl(url)) {
                    await launchUrl(url, mode: LaunchMode.externalApplication);
                  }
                },
                child: Container(
                  width: 50.h,
                  height: 50.h,
                  decoration: BoxDecoration(
                    color: CommonColor.bg_button,
                    shape: BoxShape.circle,
                  ),
                  alignment: Alignment.center,
                  child: FaIcon(FontAwesomeIcons.instagram,
                      color: CommonColor.white, size: 24.h),
                ),
              ),
            ),
          ],
        ),
      ],
    );
  }
}
