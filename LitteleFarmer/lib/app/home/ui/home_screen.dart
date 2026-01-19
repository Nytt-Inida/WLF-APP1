import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/blog/provider/blog_provider.dart';
import 'package:little_farmer/app/blog/ui/blog_detail_screen.dart';
import 'package:little_farmer/app/blog/ui/blog_list_screen.dart';
import 'package:little_farmer/app/home/model/course_by_age_model.dart';
import 'package:little_farmer/app/all_courses/ui/all_courses_screen.dart';
import 'package:little_farmer/app/home/provider/home_provider.dart';
import 'package:little_farmer/app/live_sessions/ui/live_session_list_screen.dart';
import 'package:little_farmer/utils/SharedPreferencesUtil.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:little_farmer/utils/website_images.dart';
import 'package:provider/provider.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  late HomeProvider provider;
  late bool isTablet;
  
  @override
  void initState() {
    super.initState();
    provider = Provider.of<HomeProvider>(context, listen: false);
    isTablet = Utils.isTablet(context: context);
    // Fetch data in parallel for better performance
    Future.wait([
      provider.fetchPopularCourse(),
      provider.fetchCourseByAge(age: "Any age"),
      context.read<BlogProvider>().fetchRecentBlogs(limit: 3),
    ]);
  }

  @override
  void dispose() {
    // Don't call resetProvider during dispose - it can cause "setState during dispose" errors
    // The provider will be cleaned up automatically
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: CommonColor.bg_main,
      extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Consumer<HomeProvider>(
        builder: (context, provider, _) {
          return SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Hi User and Search
                Container(
                  margin: EdgeInsets.symmetric(horizontal: 10.w, vertical: 10.h),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            "Welcome back,",
                            style: Theme.of(context).textTheme.bodyMedium,
                          ),
                          Text(
                            "Hi ${SharedPreferencesUtil.getString(SharedPreferencesKey.name)}",
                            style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                                  color: Theme.of(context).colorScheme.primary,
                                  fontWeight: FontWeight.bold,
                                ),
                          ),
                        ],
                      ),
                      InkWell(
                          onTap: () {
                            provider.gotoSearchScreen(context: context);
                          },
                          borderRadius: BorderRadius.circular(50),
                          child: Container(
                              padding: EdgeInsets.all(8.w),
                              decoration: BoxDecoration(
                                color: Theme.of(context).colorScheme.surface,
                                shape: BoxShape.circle,
                                boxShadow: [
                                  BoxShadow(
                                    color: Colors.black.withOpacity(0.05),
                                    blurRadius: 10,
                                    offset: Offset(0, 5),
                                  ),
                                ],
                              ),
                              height: 40.h,
                              width: 40.h,
                              child: SvgPicture.asset(CommonImage.ic_search, fit: BoxFit.cover, colorFilter: ColorFilter.mode(Theme.of(context).colorScheme.onSurface, BlendMode.srcIn)))),
                    ],
                  ),
                ),
                
                // Hero Section - Discover the joy of farming
                _buildHeroSection(context),
                SizedBox(height: 30.h),
                
                // Beyond Certificate Section
                _buildBeyondCertificateSection(),
                SizedBox(height: 30.h),
                
                // Courses Section
                _buildCoursesSection(),
                SizedBox(height: 30.h),
                
                // Live Sessions Section
                _buildLiveSessionsSection(),
                SizedBox(height: 30.h),
                
                // Why Teach Farming Skills Section
                _buildWhyTeachFarmingSection(),
                SizedBox(height: 30.h),
                
                // Recent Blogs Section
                _buildRecentBlogsSection(),
                SizedBox(height: 100.h) // Extra padding to prevent navbar overlap
              ],
            ),
          );
        },
      ),
    ));
  }

  Widget _buildHeroSection(BuildContext context) {
    return Container(
      margin: EdgeInsets.symmetric(horizontal: 16.w),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            "Discover the joy of",
            style: Theme.of(context).textTheme.displaySmall?.copyWith(
                  fontSize: isTablet ? 36.sp : 32.sp,
                  height: 1.2,
                ),
          ),
          RichText(
            text: TextSpan(
              style: Theme.of(context).textTheme.displaySmall?.copyWith(
                    fontSize: isTablet ? 36.sp : 32.sp,
                    height: 1.2,
                  ),
              children: [
                TextSpan(
                  text: "farming",
                  style: TextStyle(color: Theme.of(context).colorScheme.primary),
                ),
                TextSpan(
                  text: ".",
                  style: TextStyle(
                    color: Theme.of(context).colorScheme.secondary,
                  ),
                ),
              ],
            ),
          ),
          SizedBox(height: 25.h),
          Text(
            "Equip your child with essential life skills like growing their own food for tomorrow's world.",
            style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                  height: 1.6,
                  color: Theme.of(context).colorScheme.onSurface.withOpacity(0.8),
                ),
          ),
          SizedBox(height: 20.h),
          Container(
            padding: EdgeInsets.all(20.w),
            decoration: BoxDecoration(
              color: Theme.of(context).colorScheme.surface,
              borderRadius: BorderRadius.circular(16.r),
              boxShadow: [
                BoxShadow(
                  color: Theme.of(context).colorScheme.shadow.withOpacity(0.05),
                  blurRadius: 20,
                  offset: const Offset(0, 10),
                ),
              ],
              border: Border.all(color: Theme.of(context).colorScheme.outline.withOpacity(0.1)),
            ),
            child: Row(
              children: [
                Container(
                  padding: EdgeInsets.all(10.w),
                  decoration: BoxDecoration(
                    color: Theme.of(context).colorScheme.primary.withOpacity(0.1),
                    shape: BoxShape.circle,
                  ),
                  child: Icon(Icons.verified_user_outlined, color: Theme.of(context).colorScheme.primary),
                ),
                SizedBox(width: 15.w),
                Expanded(
                  child: RichText(
                    text: TextSpan(
                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(height: 1.5),
                      children: [
                        TextSpan(
                          text: "#1 ",
                          style: TextStyle(fontWeight: FontWeight.bold, color: Theme.of(context).colorScheme.primary),
                        ),
                        TextSpan(
                          text: "specialized farming & food science course for kids.",
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildBeyondCertificateSection() {
    return Container(
      margin: EdgeInsets.symmetric(horizontal: 16.w),
      child: LayoutBuilder(
        builder: (context, constraints) {
          bool isTabletLayout = isTablet || constraints.maxWidth > 600;
          
          return Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              if (isTabletLayout)
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(
                      flex: 1,
                      child: _buildBeyondCertificateText(context),
                    ),
                    SizedBox(width: 20.w),
                    Expanded(
                      flex: 2,
                      child: _buildFeatureCards(),
                    ),
                  ],
                )
              else
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildBeyondCertificateText(context),
                    SizedBox(height: 20.h),
                    _buildFeatureCards(),
                  ],
                ),
            ],
          );
        },
      ),
    );
  }

  Widget _buildBeyondCertificateText(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          "Beyond Certificate",
          style: Theme.of(context).textTheme.headlineSmall,
        ),
        SizedBox(height: 15.h),
        Text(
          "Upon completion, your child can download a certificate. Beyond that, this course builds essential survival skills: learning to grow their own food, as vital as learning to swim.",
          style: Theme.of(context).textTheme.bodyMedium?.copyWith(height: 1.6),
        ),
      ],
    );
  }

  Widget _buildFeatureCards() {
    return Container(
      height: 200.h,
      child: ListView(
        scrollDirection: Axis.horizontal,
        children: [
          _buildFeatureCard(
            iconUrl: WebsiteImages.iconNotepad,
            title: "Healthy Food Awareness",
            description: "A must-have skill to lead a healthy family and society in the future.",
            color: Colors.green,
          ),
          SizedBox(width: 15.w),
          _buildFeatureCard(
            iconUrl: WebsiteImages.iconPuzzle,
            title: "Encourages Green Minds",
            description: "Explains more happiness with more green around us.",
            color: Theme.of(context).colorScheme.primary,
          ),
          SizedBox(width: 15.w),
          _buildFeatureCard(
            iconUrl: WebsiteImages.iconManager,
            title: "Sustainable Skills",
            description: "Builds sustainable skills and long-term hobbies.",
            color: Colors.orange,
          ),
        ],
      ),
    );
  }

  Widget _buildFeatureCard({
    required String iconUrl,
    required String title,
    required String description,
    required Color color,
  }) {
    // Use appropriate icon based on title
    IconData iconData;
    if (title.contains("Healthy Food") || title.contains("Notepad")) {
      iconData = Icons.restaurant_menu;
    } else if (title.contains("Green") || title.contains("Happy") || title.contains("Puzzle")) {
      iconData = Icons.eco;
    } else if (title.contains("Sustainable") || title.contains("Manager")) {
      iconData = Icons.psychology;
    } else {
      iconData = Icons.check_circle;
    }

    return Card(
      elevation: 0,
      color: Theme.of(context).colorScheme.surface,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16.r),
        side: BorderSide(color: Theme.of(context).colorScheme.outline.withOpacity(0.1)),
      ),
      child: Container(
        width: 250.w,
        padding: EdgeInsets.all(20.w),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              width: 50.w,
              height: 50.w,
              decoration: BoxDecoration(
                color: color.withOpacity(0.1),
                borderRadius: BorderRadius.circular(12.r),
              ),
              child: Center(
                child: Icon(
                  iconData,
                  color: color,
                  size: 24.sp,
                ),
              ),
            ),
            SizedBox(height: 15.h),
            Text(
              title,
              style: Theme.of(context).textTheme.titleLarge?.copyWith(
                fontSize: 16.sp,
              ),
            ),
            SizedBox(height: 8.h),
            Expanded(
              child: Text(
                description,
                style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                  height: 1.4,
                  fontSize: 13.sp,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildCoursesSection() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Container(
          padding: EdgeInsets.symmetric(horizontal: 16.w),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                "Courses",
                style: Theme.of(context).textTheme.headlineSmall,
              ),
              InkWell(
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => const AllCoursesScreen()),
                  ).then((_) {
                    provider.fetchCourseByAge(age: "Any age");
                  });
                },
                child: Text(
                  "See All",
                  style: Theme.of(context).textTheme.labelLarge?.copyWith(
                    color: Theme.of(context).colorScheme.primary,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
        ),
        SizedBox(height: 10.h),
        provider.isCourseFetchByAgeApiCalling
            ? Container(alignment: Alignment.center, height: 150.h, child: CircularProgressIndicator(color: Theme.of(context).colorScheme.primary))
            : provider.coursesByAgeList.isEmpty
                ? Container(
                    alignment: Alignment.center,
                    height: 150.h,
                    child: Text(CommonString.no_course_found,
                        style: Theme.of(context).textTheme.bodyLarge),
                  )
                : ListView.builder(
                    padding: EdgeInsets.symmetric(horizontal: 16.w),
                    itemCount: provider.coursesByAgeList.length,
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    cacheExtent: 500,
                    itemBuilder: (context, index) {
                      return RepaintBoundary(
                        child: _CourseByAgeItem(
                          course: provider.coursesByAgeList[index],
                          index: index,
                          provider: provider,
                          isTablet: isTablet,
                        ),
                      );
                    },
                  ),
      ],
    );
  }

  Widget _buildLiveSessionsSection() {
    return Container(
      padding: EdgeInsets.symmetric(horizontal: 16.w),
      child: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                "Live Sessions",
                style: Theme.of(context).textTheme.headlineSmall,
              ),
              InkWell(
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => const LiveSessionListScreen(),
                    ),
                  );
                },
                child: Text(
                  "See All",
                  style: Theme.of(context).textTheme.labelLarge?.copyWith(
                    color: Theme.of(context).colorScheme.primary,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
          SizedBox(height: 10.h),
          GestureDetector(
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => const LiveSessionListScreen(),
                ),
              );
            },
            child: Card(
              elevation: 4,
              shadowColor: Colors.black26,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16.r)),
              clipBehavior: Clip.antiAlias,
              child: Container(
                height: 150.h,
                width: double.infinity,
                child: Stack(
                  children: [
                    CachedNetworkImage(
                      imageUrl: WebsiteImages.agricultureAiBanner,
                      width: double.infinity,
                      height: 150.h,
                      fit: BoxFit.cover,
                      placeholder: (context, url) => Container(
                        height: 150.h,
                        alignment: Alignment.center,
                        child: CircularProgressIndicator(
                          color: Theme.of(context).colorScheme.primary,
                        ),
                      ),
                      errorWidget: (context, url, error) => Container(
                        height: 150.h,
                        color: Colors.grey[300],
                        child: const Icon(Icons.image, color: Colors.grey),
                      ),
                    ),
                    Container(
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          begin: Alignment.bottomCenter,
                          end: Alignment.topCenter,
                          colors: [
                            Colors.black.withOpacity(0.8),
                            Colors.transparent
                          ],
                        ),
                      ),
                      padding: EdgeInsets.all(15.w),
                      alignment: Alignment.bottomLeft,
                      child: Text(
                        "Join Live Agricultural Classes\nFor Kids",
                        style: Theme.of(context).textTheme.titleMedium?.copyWith(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildWhyTeachFarmingSection() {
    return Container(
      margin: EdgeInsets.symmetric(horizontal: 16.w),
      child: Card(
        color: Theme.of(context).colorScheme.surface, // Or a soft variant if available
        surfaceTintColor: Theme.of(context).colorScheme.primary, // Adds a slight tint
        elevation: 2,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20.r)),
        child: Padding(
          padding: EdgeInsets.all(20.w),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 6.h),
                decoration: BoxDecoration(
                  color: Theme.of(context).colorScheme.primary,
                  borderRadius: BorderRadius.circular(8.r),
                ),
                child: Text(
                  "Explore Little Farmers Academy",
                  style: Theme.of(context).textTheme.labelMedium?.copyWith(
                    color: Theme.of(context).colorScheme.onPrimary,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              SizedBox(height: 15.h),
              Text(
                "Why Teach Farming Skills to Kids?",
                style: Theme.of(context).textTheme.headlineSmall,
              ),
              SizedBox(height: 15.h),
              Text(
                "Farming connects children with nature and teaches life skills beyond classrooms. At Little Farmers Academy, kids (ages 5–15) learn responsibility, sustainability, and how food grows through fun online farming courses.",
                style: Theme.of(context).textTheme.bodyMedium?.copyWith(height: 1.6),
              ),
              SizedBox(height: 20.h),
              Text(
                "Future-Ready Careers to Explore:",
                style: Theme.of(context).textTheme.titleLarge,
              ),
              SizedBox(height: 15.h),
              _buildCareerList(),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildCareerList() {
    final careers = [
      "Food Scientist: Learns how to develop healthy foods and improve food safety and nutrition.",
      "Agricultural Engineer: Designs smart farming tools and machines using technology.",
      "Agricultural Scientist: Studies plants and soil to improve crop yield and food quality.",
      "Organic Farmer: Practices eco-friendly and chemical-free farming methods.",
      "Agri Entrepreneur: Builds sustainable businesses in farming, honey, or plant-based products.",
      "Farm Manager: Oversees farm operations and resource management efficiently.",
      "Environmental Consultant: Promotes responsible resource use and sustainable solutions.",
      "Farm-to-Table Chef: Creates healthy dishes using fresh, locally grown ingredients.",
    ];

    return Column(
      children: careers.map((career) {
        return Padding(
          padding: EdgeInsets.only(bottom: 12.h),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Icon(Icons.check_circle_outline, color: Theme.of(context).colorScheme.primary, size: 20.sp),
              SizedBox(width: 10.w),
              Expanded(
                child: Text(
                  career,
                  style: Theme.of(context).textTheme.bodyMedium?.copyWith(height: 1.4),
                ),
              ),
            ],
          ),
        );
      }).toList(),
    );
  }

  Widget _buildRecentBlogsSection() {
    return Container(
      margin: EdgeInsets.symmetric(horizontal: 16.w),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                "Recent Blogs",
                style: Theme.of(context).textTheme.headlineSmall,
              ),
              InkWell(
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => const BlogListScreen(),
                    ),
                  );
                },
                child: Text(
                  "See All",
                  style: Theme.of(context).textTheme.labelLarge?.copyWith(
                    color: Theme.of(context).colorScheme.primary,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
          SizedBox(height: 15.h),
          Selector<BlogProvider, List<dynamic>>(
            selector: (context, provider) => provider.recentBlogs,
            shouldRebuild: (prev, next) => prev.length != next.length,
            builder: (context, blogs, child) {
              final blogProvider = context.read<BlogProvider>();
              if (blogProvider.isRecentBlogsLoading) {
                return Container(
                  height: 180.h,
                  alignment: Alignment.center,
                  child: CircularProgressIndicator(
                    color: CommonColor.bg_button,
                  ),
                );
              }

              if (blogs.isEmpty) {
                return const SizedBox.shrink();
              }

              return Container(
                height: 220.h,
                child: ListView.builder(
                  shrinkWrap: true,
                  scrollDirection: Axis.horizontal,
                  physics: const AlwaysScrollableScrollPhysics(),
                  itemCount: blogs.length,
                  itemBuilder: (context, index) {
                    final blog = blogs[index];
                    return RepaintBoundary(
                      child: _BlogItem(blog: blog, formatDate: _formatDate),
                    );
                  },
                ),
              );
            },
          ),
        ],
      ),
    );
  }

  String _formatDate(String? dateStr) {
    if (dateStr == null) return "";
    try {
      DateTime date = DateTime.parse(dateStr);
      return "${date.day}/${date.month}/${date.year}";
    } catch (e) {
      return dateStr;
    }
  }
}

// Extracted course item widget for better performance
class _CourseByAgeItem extends StatelessWidget {
  final Course course;
  final int index;
  final HomeProvider provider;
  final bool isTablet;

  const _CourseByAgeItem({
    required this.course,
    required this.index,
    required this.provider,
    required this.isTablet,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 2,
      margin: EdgeInsets.only(bottom: 12.h),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16.r)),
      color: Theme.of(context).colorScheme.surface,
      surfaceTintColor: Colors.white,
      child: InkWell(
        borderRadius: BorderRadius.circular(16.r),
        onTap: () {
          provider.gotoCourseDetailScreen(
            context: context,
            courseId: course.id,
            title: course.title,
            price: course.price,
            isPurchased: course.isPurchase,
          );
        },
        child: Padding(
          padding: EdgeInsets.all(12.w),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              SizedBox(
                height: 80.h,
                width: 80.h,
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(12.r),
                  child: CachedNetworkImage(
                    imageUrl: course.image,
                    fit: BoxFit.cover,
                    placeholder: (context, url) => Container(
                      height: 80.h,
                      width: 80.h,
                      alignment: Alignment.center,
                      child: CircularProgressIndicator(
                        color: Theme.of(context).colorScheme.primary,
                        strokeWidth: 2,
                      ),
                    ),
                    errorWidget: (context, url, error) => Container(
                      color: Theme.of(context).colorScheme.surfaceContainerHighest,
                      child: Icon(Icons.image_not_supported, color: Theme.of(context).colorScheme.onSurfaceVariant),
                    ),
                  ),
                ),
              ),
              SizedBox(width: 15.w),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text(
                      course.title,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: Theme.of(context).textTheme.titleMedium?.copyWith(
                        fontWeight: FontWeight.bold,
                        height: 1.2,
                      ),
                    ),
                    SizedBox(height: 6.h),
                    Container(
                      padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 4.h),
                      decoration: BoxDecoration(
                        color: Theme.of(context).colorScheme.errorContainer,
                        borderRadius: BorderRadius.circular(8.r),
                      ),
                      child: Text(
                        course.ageGroup,
                        style: Theme.of(context).textTheme.labelSmall?.copyWith(
                          color: Theme.of(context).colorScheme.onErrorContainer,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                    SizedBox(height: 6.h),
                    Row(
                      children: [
                        Icon(Icons.class_outlined, size: 14.sp, color: Theme.of(context).colorScheme.secondary),
                        SizedBox(width: 4.w),
                        Text(
                          "${course.numberOfClasses} Classes",
                          style: Theme.of(context).textTheme.bodySmall?.copyWith(
                             color: Theme.of(context).colorScheme.secondary,
                             fontWeight: FontWeight.w600,
                          ),
                        ),
                      ],
                    ),
                    SizedBox(height: 6.h),
                    Text(
                      course.displayPrice,
                      style: Theme.of(context).textTheme.titleMedium?.copyWith(
                        color: Theme.of(context).colorScheme.primary,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// Extracted blog item widget for better performance
class _BlogItem extends StatelessWidget {
  final dynamic blog; // Blog model
  final String Function(String?) formatDate;

  const _BlogItem({
    required this.blog,
    required this.formatDate,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 2,
      margin: EdgeInsets.only(right: 12.w, bottom: 4.h, top: 4.h),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16.r)),
      color: Theme.of(context).colorScheme.surface,
      surfaceTintColor: Colors.white,
      child: InkWell(
        borderRadius: BorderRadius.circular(16.r),
        onTap: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => BlogDetailScreen(blogId: blog.id),
            ),
          );
        },
        child: SizedBox(
          width: 250.w, // Slightly reduced width to better fit mobile viewports
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min, // Allow column to shrink wrap
            children: [
              ClipRRect(
                borderRadius: BorderRadius.vertical(
                  top: Radius.circular(16.r),
                ),
                child: CachedNetworkImage(
                  imageUrl: blog.featuredImage ?? "",
                  height: 120.h,
                  width: double.infinity,
                  fit: BoxFit.cover,
                  errorWidget: (context, url, error) => Container(
                    height: 120.h,
                    color: Theme.of(context).colorScheme.surfaceContainerHighest,
                    child: Icon(Icons.image_not_supported, color: Theme.of(context).colorScheme.onSurfaceVariant),
                  ),
                ),
              ),
              Padding(
                padding: EdgeInsets.all(12.w),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      blog.title,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: Theme.of(context).textTheme.titleMedium?.copyWith(
                        fontWeight: FontWeight.bold,
                        height: 1.2,
                      ),
                    ),
                    SizedBox(height: 8.h),
                    if (blog.publishedAt != null)
                      Row(
                        children: [
                          Icon(Icons.calendar_today_outlined, size: 14.sp, color: Theme.of(context).colorScheme.secondary),
                          SizedBox(width: 6.w),
                          Expanded(
                            child: Text(
                              formatDate(blog.publishedAt),
                              style: Theme.of(context).textTheme.bodySmall?.copyWith(
                                color: Theme.of(context).colorScheme.secondary,
                              ),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
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
    );
  }
}
