import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:little_farmer/app/course_detail/provider/course_detail_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/payment_config.dart';
import 'package:little_farmer/utils/SharedPreferencesUtil.dart';

import 'package:little_farmer/utils/utils.dart';
import 'package:little_farmer/app/payment/ui/manual_payment_screen.dart';
import 'package:little_farmer/app/payment/ui/paypal_payment_screen.dart';
import 'package:provider/provider.dart';
import 'package:video_player/video_player.dart';

// ignore: must_be_immutable
class CourseDetailScreen extends StatefulWidget {
  int courseId;
  String title;
  String price;
  CourseDetailScreen({super.key, required this.courseId, required this.title, required this.price});

  @override
  State<CourseDetailScreen> createState() => _CourseDetailScreenState();
}

class _CourseDetailScreenState extends State<CourseDetailScreen> {
  late CourseDetailProvider provider;

  @override
  void initState() {
    super.initState();
    provider = Provider.of<CourseDetailProvider>(context, listen: false);
    provider.fetchCourseDetail(context: context, courseId: widget.courseId);
  }

  @override
  void dispose() {
    // Don't call resetProvider during dispose - it can cause "setState during dispose" errors
    // Only clean up the controller directly if needed
    try {
      if (provider.isControllerInitialize && provider.controller != null) {
        provider.controller?.pause();
        provider.controller?.dispose().catchError((e) {
          debugPrint('Error disposing controller in course detail screen: $e');
        });
      }
    } catch (e) {
      debugPrint('Error in course detail screen dispose: $e');
    }
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Consumer<CourseDetailProvider>(
          builder: (context, provider, _) {
            return Stack(
              children: [
                Container(
                  margin: provider.isFullScreen ? const EdgeInsets.all(0.0) : EdgeInsets.symmetric(horizontal: 0.h), // Full width for header
                  child: Column(
                    children: [
                      if (!provider.isFullScreen)
                        Container(
                          padding: EdgeInsets.symmetric(horizontal: 16.w, vertical: 12.h),
                          color: Theme.of(context).cardColor, // White header
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              GestureDetector(
                                onTap: () {
                                  Navigator.of(context).pop();
                                },
                                child: Container(
                                  padding: EdgeInsets.all(8.w),
                                  decoration: BoxDecoration(
                                    color: Theme.of(context).colorScheme.surface,
                                    shape: BoxShape.circle,
                                    border: Border.all(color: Theme.of(context).colorScheme.outline.withOpacity(0.2)),
                                  ),
                                  child: Icon(Icons.arrow_back, size: 20.h, color: Theme.of(context).colorScheme.onSurface),
                                ),
                              ),
                              Expanded(
                                child: Text(
                                  widget.title,
                                  textAlign: TextAlign.center,
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                  style: Theme.of(context).textTheme.titleLarge?.copyWith(
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                              ),
                              SizedBox(width: 42.w), // Placeholder
                            ],
                          ),
                        ),
                      provider.isCourseFetchApiCalling
                          ? Container(
                              alignment: Alignment.center,
                              height: MediaQuery.of(context).size.height - 100.h,
                              child: CircularProgressIndicator(color: Theme.of(context).colorScheme.primary))
                          : provider.isFullScreen
                              ? Expanded(
                                  child: Container(
                                    color: Colors.black,
                                    width: double.infinity,
                                    child: Stack(
                                      fit: StackFit.expand,
                                      children: [
                                        provider.isControllerInitialize && provider.controller != null && provider.controller!.value.isInitialized
                                            ? Center(
                                                child: AspectRatio(
                                                  aspectRatio: provider.controller!.value.aspectRatio,
                                                  child: VideoPlayer(
                                                    provider.controller!,
                                                    key: ValueKey('video_${provider.controller!.hashCode}'), // Prevent reuse of disposed controller
                                                  ),
                                                ),
                                              )
                                            : Center(child: CircularProgressIndicator(color: Theme.of(context).colorScheme.primary)),
                                        if (provider.isControllerVisible && provider.controller != null)
                                          SafeArea(child: _buildFullScreenControls(context, provider)),
                                      ],
                                    ),
                                  ),
                                )
                              : Expanded(
                                  child: SingleChildScrollView(
                                    child: Column(
                                      children: [
                                        buildVideoPlayerSection(provider),
                                        Column(
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            if (provider.courseDetail != null) buildCourseHeaderSection(provider),
                                            buildLessonsList(provider),
                                            if (provider.courseDetail != null) buildCourseDetailSection(provider),
                                            buildCourseIncludesSection(provider),
                                            if (provider.courseDetailModel?.ratings != null) buildRatingsSection(provider),
                                            if (provider.courseDetailModel?.instructors != null && provider.courseDetailModel!.instructors.isNotEmpty)
                                              buildInstructorSection(provider),
                                            if (provider.courseDetailModel?.tags != null && provider.courseDetailModel!.tags.isNotEmpty)
                                              buildTagsSection(provider),
                                            if (provider.courseDetailModel?.relatedCourses != null && provider.courseDetailModel!.relatedCourses.isNotEmpty)
                                              buildRelatedCoursesSection(provider),
                                            SizedBox(height: 80.h),
                                          ],
                                        ),
                                      ],
                                    ),
                                  ),
                                ),
                    ],
                  ),
                ),
                if (!provider.isFullScreen) Positioned(bottom: 0, left: 0, right: 0, child: buildBottomButtonSection(context, provider)),
              ],
            );
          },
        ),
      ),
    );
  }

  Widget _buildFullScreenControls(BuildContext context, CourseDetailProvider provider) {
    return ClipRRect(
      borderRadius: BorderRadius.circular(0),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.end,
        children: [
          Container(
            width: double.infinity,
            color: Colors.black54,
            padding: EdgeInsets.symmetric(horizontal: 20.w, vertical: 20.h),
            child: Row(
              children: [
                GestureDetector(
                  onTap: () {
                    if (provider.controller!.value.isPlaying) {
                      provider.controller!.pause();
                    } else {
                      provider.controller!.play();
                    }
                    setState(() {});
                  },
                  child: Icon(
                    provider.controller!.value.isPlaying ? Icons.pause_circle_filled : Icons.play_circle_fill,
                    size: 30.h,
                    color: Theme.of(context).colorScheme.primary,
                  ),
                ),
                SizedBox(width: 10.w),
                Expanded(
                  child: VideoProgressIndicator(
                    provider.controller!,
                    allowScrubbing: true,
                    colors: VideoProgressColors(
                        playedColor: Theme.of(context).colorScheme.primary,
                        bufferedColor: Colors.white54,
                        backgroundColor: Colors.white24),
                  ),
                ),
                SizedBox(width: 10.w),
                Text(
                  provider.duration.isNotEmpty
                      ? '${provider.currentPosition.toString().split(".")[0]} / ${provider.duration[0]}'
                      : '--:-- / --:--',
                  style: Theme.of(context).textTheme.labelSmall?.copyWith(color: Colors.white),
                ),
                SizedBox(width: 10.w),
                GestureDetector(
                  onTap: () {
                    provider.videoMute();
                  },
                  child: Icon(
                    provider.isMute ? Icons.volume_off : Icons.volume_up,
                    size: 24.h,
                    color: Colors.white,
                  ),
                ),
                SizedBox(width: 10.w),
                GestureDetector(
                  onTap: () {
                    setState(() {
                      provider.isFullScreen = false;
                      provider.streamController.close();
                      SystemChrome.setEnabledSystemUIMode(SystemUiMode.manual, overlays: SystemUiOverlay.values);
                      SystemChrome.setPreferredOrientations([DeviceOrientation.portraitUp]);
                    });
                  },
                  child: Icon(Icons.fullscreen_exit, size: 24.h, color: Colors.white),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget buildVideoPlayerSection(CourseDetailProvider provider) {
    return Container(
      width: double.infinity,
      height: 220.h, // Standard video height
      color: Colors.black,
      child: provider.isControllerInitialize && provider.controller != null
          ? Stack(
              alignment: Alignment.center,
              children: [
                AspectRatio(
                  aspectRatio: provider.controller!.value.aspectRatio,
                  child: VideoPlayer(
                    provider.controller!,
                    key: ValueKey('video_${provider.controller!.hashCode}'), // Prevent reuse of disposed controller
                  ),
                ),
                if (provider.isControllerVisible)
                  Container(
                    alignment: Alignment.bottomCenter,
                    child: Container(
                      width: double.infinity,
                      color: Colors.black45,
                      padding: EdgeInsets.symmetric(horizontal: 10.w, vertical: 8.h),
                      child: Row(
                        children: [
                          GestureDetector(
                            onTap: () async {
                              if (provider.controller != null) {
                                if (provider.controller!.value.isPlaying) {
                                  provider.controller!.pause();
                                } else {
                                  // Activate audio session before playing (iOS requirement)
                                  await provider.activateAudioSession();
                                  await provider.controller!.setVolume(1.0);
                                  await provider.controller!.play();
                                }
                                setState(() {});
                              }
                            },
                            child: Icon(
                              provider.controller != null && provider.controller!.value.isPlaying
                                  ? Icons.pause_circle_filled
                                  : Icons.play_circle_fill,
                              color: Theme.of(context).colorScheme.primary,
                              size: 28.h,
                            ),
                          ),
                          SizedBox(width: 10.w),
                          Expanded(
                            child: provider.controller != null
                                ? VideoProgressIndicator(
                                    provider.controller!,
                                    allowScrubbing: true,
                                    colors: VideoProgressColors(
                                        playedColor: Theme.of(context).colorScheme.primary,
                                        bufferedColor: Colors.white54,
                                        backgroundColor: Colors.white24),
                                  )
                                : SizedBox(),
                          ),
                          SizedBox(width: 10.w),
                          Text(
                            provider.duration.isNotEmpty
                                ? '${provider.currentPosition.toString().split(".")[0]} / ${provider.duration[0]}'
                                : '--:-- / --:--',
                            style: Theme.of(context).textTheme.labelSmall?.copyWith(color: Colors.white),
                          ),
                          SizedBox(width: 8.w),
                          GestureDetector(
                            onTap: () => provider.videoMute(),
                            child: Icon(
                              provider.isMute ? Icons.volume_off : Icons.volume_up,
                              color: Colors.white,
                              size: 20.h,
                            ),
                          ),
                          SizedBox(width: 8.w),
                          GestureDetector(
                            onTap: () {
                              provider.isFullScreen = true;
                              SystemChrome.setEnabledSystemUIMode(SystemUiMode.manual, overlays: []);
                              SystemChrome.setPreferredOrientations(
                                  [DeviceOrientation.landscapeLeft, DeviceOrientation.landscapeRight]);
                            },
                            child: Icon(Icons.fullscreen, color: Colors.white, size: 24.h),
                          ),
                        ],
                      ),
                    ),
                  )
              ],
            )
          : Stack(
              children: [
                Positioned.fill(
                  child: CachedNetworkImage(
                    imageUrl: provider.courseDetailModel?.thumbnail ?? "",
                    fit: BoxFit.cover,
                    errorWidget: (context, url, error) => Container(color: Colors.grey[900]),
                  ),
                ),
                Center(
                  child: GestureDetector(
                    onTap: () {
                      provider.controllerTimer();
                    },
                    child: Container(
                      padding: EdgeInsets.all(12.w),
                      decoration: BoxDecoration(color: Colors.black54, shape: BoxShape.circle),
                      child: provider.isCourseFetchApiCalling
                          ? CircularProgressIndicator(color: Theme.of(context).colorScheme.primary)
                          : Icon(Icons.play_arrow, color: Colors.white, size: 40.h),
                    ),
                  ),
                ),
              ],
            ),
    );
  }

  Widget buildCourseHeaderSection(CourseDetailProvider provider) {
    if (provider.courseDetail == null) return SizedBox();
    return Container(
      padding: EdgeInsets.fromLTRB(16.w, 16.h, 16.w, 8.h),
      color: Theme.of(context).cardColor,
      width: double.infinity,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            provider.courseDetail!.title,
            style: Theme.of(context).textTheme.headlineSmall?.copyWith(
              fontWeight: FontWeight.bold,
              height: 1.3,
            ),
          ),
          SizedBox(height: 12.h),
          Row(
            children: [
              if (provider.courseDetailModel?.ageGroup != null && provider.courseDetailModel!.ageGroup.isNotEmpty)
                Container(
                  padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 6.h),
                  decoration: BoxDecoration(
                    color: Theme.of(context).colorScheme.primaryContainer,
                    borderRadius: BorderRadius.circular(20.r),
                    border: Border.all(color: Theme.of(context).colorScheme.primary.withOpacity(0.2)),
                  ),
                  child: Text(
                    "Age: ${provider.courseDetailModel!.ageGroup}",
                    style: Theme.of(context).textTheme.labelMedium?.copyWith(
                      color: Theme.of(context).colorScheme.onPrimaryContainer,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
            ],
          ),
        ],
      ),
    );
  }

  Widget buildCourseDetailSection(CourseDetailProvider provider) {
    if (provider.courseDetail == null) return SizedBox();
    return Container(
      margin: EdgeInsets.only(top: 8.h),
      padding: EdgeInsets.all(16.w),
      color: Theme.of(context).cardColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // About the Course Section
          Text(
            "About the Course",
            style: Theme.of(context).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
          ),
          SizedBox(height: 12.h),
          Text(
            provider.courseDetail!.description.isNotEmpty
                ? provider.courseDetail!.description
                : "The Little Farmers Full Course is an interactive online farming and gardening course for children...",
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(height: 1.6),
          ),
          SizedBox(height: 24.h),
          
          // What Your Child Will Learn Section
          Text(
            "What Your Child Will Learn",
            style: Theme.of(context).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
          ),
          SizedBox(height: 16.h),
          _buildLearningPoint("Grow plants from seed and care for them the right way."),
          SizedBox(height: 12.h),
          _buildLearningPoint("Understand soil, sunlight, water, and seasons for healthy growth."),
          SizedBox(height: 12.h),
          _buildLearningPoint("Start a mini home garden with vegetables, herbs, and edible plants."),
          SizedBox(height: 12.h),
          _buildLearningPoint("Discover why farming is essential for people and the planet."),
          SizedBox(height: 12.h),
          _buildLearningPoint("Identify healthy food and practice everyday sustainability."),
          SizedBox(height: 24.h),
          
          // Course Highlights Section
          Text(
            "Course Highlights",
            style: Theme.of(context).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
          ),
          SizedBox(height: 16.h),
          _buildHighlightItem(Icons.play_circle_outline, "19 interactive video lessons your child can follow easily."),
          SizedBox(height: 12.h),
          _buildHighlightItem(Icons.description_outlined, "Activity sheets & quizzes that make learning playful."),
          SizedBox(height: 12.h),
          _buildHighlightItem(Icons.eco_outlined, "Grow-at-home projects to apply skills in real life."),
          SizedBox(height: 12.h),
          _buildHighlightItem(Icons.emoji_events_outlined, "Certificate of completion — \"I Am a Farmer\"."),
          SizedBox(height: 12.h),
          _buildHighlightItem(Icons.all_inclusive, "Lifetime access on mobile, tablet, or desktop."),
        ],
      ),
    );
  }

  Widget _buildLearningPoint(String text) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(
          Icons.check_circle,
          color: Theme.of(context).colorScheme.primary, // Orange ticks
          size: 20.sp,
        ),
        SizedBox(width: 12.w),
        Expanded(
          child: Text(
            text,
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(height: 1.4),
          ),
        ),
      ],
    );
  }

  Widget _buildHighlightItem(IconData icon, String text) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Container(
          padding: EdgeInsets.all(8.w),
          decoration: BoxDecoration(
            color: Theme.of(context).colorScheme.primaryContainer,
            borderRadius: BorderRadius.circular(8.r),
          ),
          child: Icon(
            icon,
            color: Theme.of(context).colorScheme.primary,
            size: 20.sp,
          ),
        ),
        SizedBox(width: 12.w),
        Expanded(
          child: Text(
            text,
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(height: 1.4),
          ),
        ),
      ],
    );
  }

  Widget buildCourseIncludesSection(CourseDetailProvider provider) {
    return Container(
      margin: EdgeInsets.only(top: 8.h),
      padding: EdgeInsets.all(16.w),
      decoration: BoxDecoration(
        color: Theme.of(context).cardColor,
        // Using existing shadow logic or theme shadow if available
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            "Course Includes",
            style: Theme.of(context).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
          ),
          SizedBox(height: 12.h),
          Text(
            "Explore the joy of farming with our kid-friendly online course...",
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(height: 1.6),
          ),
          SizedBox(height: 20.h),
          _buildIncludeItem(Icons.video_library_outlined, "4 hours of on-demand video lessons on farming basics"),
          SizedBox(height: 12.h),
          _buildIncludeItem(Icons.download_outlined, "Downloadable activity sheets and fun gardening tips"),
          SizedBox(height: 12.h),
          _buildIncludeItem(Icons.all_inclusive, "Full lifetime access to course materials"),
          SizedBox(height: 12.h),
          _buildIncludeItem(Icons.devices, "Access on mobile, tablet, or desktop devices"),
          SizedBox(height: 12.h),
          _buildIncludeItem(Icons.picture_as_pdf_outlined, "Certificate available as a downloadable PDF"),
        ],
      ),
    );
  }

  Widget _buildIncludeItem(IconData icon, String text) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(
          icon,
          color: Theme.of(context).colorScheme.primary,
          size: 20.sp,
        ),
        SizedBox(width: 12.w),
        Expanded(
          child: Text(
            text,
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(height: 1.4),
          ),
        ),
      ],
    );
  }

  Widget buildRatingsSection(CourseDetailProvider provider) {
    final ratings = provider.courseDetailModel?.ratings;
    if (ratings == null || ratings.total == 0) return SizedBox();

    return Container(
      margin: EdgeInsets.only(top: 16.h, bottom: 8.h),
      padding: EdgeInsets.all(16.w),
      decoration: BoxDecoration(
        color: Theme.of(context).cardColor,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            "Course Ratings & Reviews",
            style: Theme.of(context).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
          ),
          SizedBox(height: 16.h),
          Row(
            children: [
              // Average Rating
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      Text(
                        ratings.average.toStringAsFixed(1),
                        style: Theme.of(context).textTheme.displaySmall?.copyWith(fontWeight: FontWeight.bold),
                      ),
                      SizedBox(width: 8.w),
                      Icon(Icons.star, color: Colors.amber, size: 28.sp),
                    ],
                  ),
                  SizedBox(height: 4.h),
                  Text(
                    "${ratings.total} ${ratings.total == 1 ? 'Review' : 'Reviews'}",
                    style: Theme.of(context).textTheme.bodyMedium?.copyWith(color: Theme.of(context).disabledColor),
                  ),
                ],
              ),
              SizedBox(width: 24.w),
              // Rating Distribution
              Expanded(
                child: Column(
                  children: List.generate(5, (index) {
                    final starCount = 5 - index;
                    final count = ratings.distribution[starCount] ?? 0;
                    final percentage = ratings.total > 0 ? (count / ratings.total) : 0.0;

                    return Padding(
                      padding: EdgeInsets.only(bottom: 6.h),
                      child: Row(
                        children: [
                          Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Text(
                                "$starCount",
                                style: Theme.of(context).textTheme.bodySmall?.copyWith(fontWeight: FontWeight.bold),
                              ),
                              SizedBox(width: 4.w),
                              Icon(Icons.star, color: Colors.amber, size: 12.sp),
                            ],
                          ),
                          SizedBox(width: 8.w),
                          Expanded(
                            child: Container(
                              height: 6.h,
                              decoration: BoxDecoration(
                                color: Theme.of(context).colorScheme.surfaceContainerHighest,
                                borderRadius: BorderRadius.circular(4.r),
                              ),
                              child: FractionallySizedBox(
                                alignment: Alignment.centerLeft,
                                widthFactor: percentage,
                                child: Container(
                                  decoration: BoxDecoration(
                                    color: Colors.amber,
                                    borderRadius: BorderRadius.circular(4.r),
                                  ),
                                ),
                              ),
                            ),
                          ),
                          SizedBox(width: 8.w),
                          Text(
                            "$count",
                            style: Theme.of(context).textTheme.bodySmall?.copyWith(color: Theme.of(context).disabledColor),
                          ),
                        ],
                      ),
                    );
                  }),
                ),
              ),
            ],
          ),
          // Recent Reviews
          if (provider.courseDetailModel!.reviews.isNotEmpty) ...[
            SizedBox(height: 24.h),
            Divider(color: Theme.of(context).dividerColor),
            SizedBox(height: 16.h),
            Text(
              "Recent Reviews",
              style: Theme.of(context).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 16.h),
            ...provider.courseDetailModel!.reviews.take(3).map((review) {
              return Container(
                margin: EdgeInsets.only(bottom: 12.h),
                padding: EdgeInsets.all(12.w),
                decoration: BoxDecoration(
                  color: Theme.of(context).colorScheme.surfaceContainerHighest.withOpacity(0.3),
                  borderRadius: BorderRadius.circular(12.r),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Expanded(
                          child: Text(
                            review.userName,
                            style: Theme.of(context).textTheme.titleSmall?.copyWith(fontWeight: FontWeight.bold),
                          ),
                        ),
                        Row(
                          children: List.generate(5, (index) {
                            return Icon(
                              index < review.rating ? Icons.star : Icons.star_border,
                              color: Colors.amber,
                              size: 14.sp,
                            );
                          }),
                        ),
                      ],
                    ),
                    if (review.message.isNotEmpty) ...[
                      SizedBox(height: 8.h),
                      Text(
                        review.message,
                        style: Theme.of(context).textTheme.bodyMedium,
                      ),
                    ],
                  ],
                ),
              );
            }).toList(),
          ],
        ],
      ),
    );
  }

  Widget buildTagsSection(CourseDetailProvider provider) {
    final tags = provider.courseDetailModel?.tags ?? [];
    if (tags.isEmpty) return SizedBox();

    return Container(
      margin: EdgeInsets.only(top: 8.h, bottom: 8.h),
      padding: EdgeInsets.all(16.w),
      decoration: BoxDecoration(
        color: Theme.of(context).cardColor,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            "Course Tags",
            style: Theme.of(context).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
          ),
          SizedBox(height: 12.h),
          Wrap(
            spacing: 8.w,
            runSpacing: 8.h,
            children: tags.map((tag) {
              return Container(
                padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 6.h),
                decoration: BoxDecoration(
                  color: Theme.of(context).colorScheme.primary.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(20.r),
                  border: Border.all(color: Theme.of(context).colorScheme.primary.withOpacity(0.3), width: 1),
                ),
                child: Text(
                  tag.name,
                  style: Theme.of(context).textTheme.labelMedium?.copyWith(
                    color: Theme.of(context).colorScheme.primary,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              );
            }).toList(),
          ),
        ],
      ),
    );
  }

  Widget buildInstructorSection(CourseDetailProvider provider) {
    final instructors = provider.courseDetailModel?.instructors ?? [];
    if (instructors.isEmpty) return SizedBox();

    return Container(
      margin: EdgeInsets.only(top: 8.h, bottom: 8.h),
      padding: EdgeInsets.all(16.w),
      decoration: BoxDecoration(
        color: Theme.of(context).cardColor,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            "About the Instructors",
            style: Theme.of(context).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
          ),
          SizedBox(height: 16.h),
          ...instructors.map((instructor) {
            return Container(
              margin: EdgeInsets.only(bottom: 12.h),
              padding: EdgeInsets.all(12.w),
              decoration: BoxDecoration(
                color: Theme.of(context).colorScheme.surfaceContainerHighest.withOpacity(0.3),
                borderRadius: BorderRadius.circular(12.r),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        width: 48.h,
                        height: 48.h,
                        decoration: BoxDecoration(
                          color: Theme.of(context).colorScheme.primaryContainer,
                          shape: BoxShape.circle,
                        ),
                        child: Icon(
                          Icons.person,
                          color: Theme.of(context).colorScheme.primary,
                          size: 24.h,
                        ),
                      ),
                      SizedBox(width: 12.w),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              instructor.name,
                              style: Theme.of(context).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
                            ),
                            if (instructor.role.isNotEmpty)
                              Text(
                                instructor.role,
                                style: Theme.of(context).textTheme.bodySmall?.copyWith(
                                  color: Theme.of(context).colorScheme.primary,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  if (instructor.description.isNotEmpty) ...[
                    SizedBox(height: 12.h),
                    Text(
                      instructor.description,
                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(height: 1.5),
                    ),
                  ],
                ],
              ),
            );
          }).toList(),
        ],
      ),
    );
  }

  Widget buildRelatedCoursesSection(CourseDetailProvider provider) {
    final relatedCourses = provider.courseDetailModel?.relatedCourses ?? [];
    if (relatedCourses.isEmpty) return SizedBox();

    return Container(
      margin: EdgeInsets.only(top: 8.h, bottom: 24.h),
      padding: EdgeInsets.all(16.w),
      decoration: BoxDecoration(
        color: Theme.of(context).cardColor,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            "Related Courses",
            style: Theme.of(context).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
          ),
          SizedBox(height: 16.h),
          ...relatedCourses.map((course) {
            return Card(
              elevation: 0,
              color: Theme.of(context).colorScheme.surface,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12.r),
                side: BorderSide(color: Theme.of(context).dividerColor),
              ),
              margin: EdgeInsets.only(bottom: 12.h),
              child: InkWell(
                borderRadius: BorderRadius.circular(12.r),
                onTap: () {
                  Navigator.pushReplacement(
                    context,
                    MaterialPageRoute(
                      builder: (context) => CourseDetailScreen(
                        courseId: course.id,
                        title: course.title,
                        price: course.price,
                      ),
                    ),
                  );
                },
                child: Padding(
                  padding: EdgeInsets.all(12.w),
                  child: Row(
                    children: [
                      ClipRRect(
                        borderRadius: BorderRadius.circular(8.r),
                        child: course.thumbnail.isNotEmpty
                            ? CachedNetworkImage(
                                imageUrl: course.thumbnail,
                                width: 80.w,
                                height: 60.h,
                                fit: BoxFit.cover,
                                placeholder: (context, url) => Container(
                                  width: 80.w,
                                  height: 60.h,
                                  color: Theme.of(context).colorScheme.surfaceContainerHighest,
                                  child: Center(
                                    child: CircularProgressIndicator(
                                      strokeWidth: 2,
                                      color: Theme.of(context).colorScheme.primary,
                                    ),
                                  ),
                                ),
                                errorWidget: (context, url, error) => Container(
                                  width: 80.w,
                                  height: 60.h,
                                  color: Theme.of(context).colorScheme.surfaceContainerHighest,
                                  child: Icon(Icons.image_not_supported, size: 24.h, color: Theme.of(context).colorScheme.onSurfaceVariant),
                                ),
                              )
                            : Container(
                                width: 80.w,
                                height: 60.h,
                                color: Theme.of(context).colorScheme.surfaceContainerHighest,
                                child: Icon(Icons.image, size: 24.h, color: Theme.of(context).colorScheme.onSurfaceVariant),
                              ),
                      ),
                      SizedBox(width: 12.w),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              course.title,
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                              style: Theme.of(context).textTheme.titleSmall?.copyWith(fontWeight: FontWeight.bold),
                            ),
                            SizedBox(height: 4.h),
                            Row(
                              children: [
                                Text(
                                  "₹${course.price}",
                                  style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                                    color: Theme.of(context).colorScheme.primary,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                if (course.ageGroup.isNotEmpty) ...[
                                  SizedBox(width: 8.w),
                                  Container(
                                    padding: EdgeInsets.symmetric(horizontal: 6.w, vertical: 2.h),
                                    decoration: BoxDecoration(
                                      color: Theme.of(context).colorScheme.primaryContainer,
                                      borderRadius: BorderRadius.circular(4.r),
                                    ),
                                    child: Text(
                                      course.ageGroup,
                                      style: Theme.of(context).textTheme.labelSmall?.copyWith(
                                        color: Theme.of(context).colorScheme.onPrimaryContainer,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                  ),
                                ],
                              ],
                            ),
                          ],
                        ),
                      ),
                      Icon(
                        Icons.arrow_forward_ios,
                        size: 16.h,
                        color: Theme.of(context).colorScheme.onSurfaceVariant,
                      ),
                    ],
                  ),
                ),
              ),
            );
          }).toList(),
        ],
      ),
    );
  }

  Widget buildLessonsList(CourseDetailProvider provider) {
    // Use sections/lessons if available (from purchase course detail model)
    if (provider.purchaseCourseDetailModel != null) {
      return Container(
        margin: EdgeInsets.only(top: 8.h),
        padding: EdgeInsets.all(16.w),
        color: Theme.of(context).cardColor,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(Icons.play_circle_outline, color: Theme.of(context).colorScheme.primary, size: 24.sp),
                SizedBox(width: 8.w),
                Text(
                  CommonString.lessons,
                  style: Theme.of(context).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
                ),
              ],
            ),
            SizedBox(height: 16.h),
            ListView.builder(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: provider.purchaseCourseDetailModel!.sections.length,
              itemBuilder: (context, sectionIndex) {
                final section = provider.purchaseCourseDetailModel!.sections[sectionIndex];
                return Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    if (sectionIndex > 0) SizedBox(height: 20.h),
                    Container(
                      padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 8.h),
                      decoration: BoxDecoration(
                        color: Theme.of(context).colorScheme.surfaceContainerHighest.withOpacity(0.5),
                        borderRadius: BorderRadius.circular(8.r),
                      ),
                      child: Text(
                        section.sectionTitle,
                        style: Theme.of(context).textTheme.titleMedium?.copyWith(
                              color: Theme.of(context).colorScheme.onSurfaceVariant,
                              fontWeight: FontWeight.bold,
                            ),
                      ),
                    ),
                    SizedBox(height: 12.h),
                    ...section.items.asMap().entries.map((entry) {
                      final index = entry.key;
                      final item = entry.value;
                      final isLocked = !item.isAccessible;

                      return Card(
                        elevation: 0,
                        color: isLocked ? Theme.of(context).colorScheme.surfaceDim : Theme.of(context).colorScheme.surface,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12.r),
                          side: BorderSide(color: Theme.of(context).dividerColor),
                        ),
                        margin: EdgeInsets.only(bottom: 10.h),
                        child: InkWell(
                          borderRadius: BorderRadius.circular(12.r),
                          onTap: () {
                            if (isLocked) {
                              Utils.showSnackbarMessage(message: "This lesson requires purchase. Please buy the course to access it.");
                            } else if (item.isVideo) {
                              // Play the accessible lesson
                              provider.currentSection = sectionIndex;
                              provider.currentVideoInSection = index;
                              provider.initializeController(
                                context: context,
                                filename: item.lessonVideoUrl,
                                lastPosition: Duration.zero,
                                lessonId: item.lessonId,
                              );
                            }
                          },
                          child: Padding(
                            padding: EdgeInsets.all(12.w),
                            child: Row(
                              children: [
                                Container(
                                  padding: EdgeInsets.all(8.w),
                                  decoration: BoxDecoration(
                                    color: isLocked ? Colors.grey[300] : Theme.of(context).colorScheme.primaryContainer,
                                    shape: BoxShape.circle,
                                  ),
                                  child: Icon(
                                    isLocked
                                        ? Icons.lock_outline
                                        : item.isVideo
                                            ? Icons.play_arrow
                                            : item.isArticle
                                                ? Icons.article_outlined
                                                : Icons.quiz_outlined,
                                    color: isLocked ? Colors.grey[600] : Theme.of(context).colorScheme.primary,
                                    size: 20.sp,
                                  ),
                                ),
                                SizedBox(width: 12.w),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        item.itemName,
                                        maxLines: 1,
                                        overflow: TextOverflow.ellipsis,
                                        style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                                              fontWeight: FontWeight.bold,
                                              color: isLocked ? Theme.of(context).disabledColor : Theme.of(context).textTheme.bodyLarge?.color,
                                            ),
                                      ),
                                      if (item.lessonDuration.isNotEmpty)
                                        Text(
                                          item.lessonDuration,
                                          style: Theme.of(context).textTheme.bodySmall?.copyWith(color: Theme.of(context).textTheme.bodySmall?.color?.withOpacity(0.7)),
                                        ),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),
                      );
                    }).toList(),
                  ],
                );
              },
            ),
          ],
        ),
      );
    }

    // Fallback to old course lessons list
    return Container(
      margin: EdgeInsets.only(top: 8.h),
      padding: EdgeInsets.all(16.w),
      color: Theme.of(context).cardColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(Icons.play_circle_outline, color: Theme.of(context).colorScheme.primary, size: 24.sp),
              SizedBox(width: 8.w),
              Text(
                CommonString.lessons,
                style: Theme.of(context).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
              ),
            ],
          ),
          SizedBox(height: 16.h),
          ListView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: provider.courseDetail?.courseLessons.length ?? 0,
            itemBuilder: (context, index) {
              return Card(
                elevation: 0,
                color: Theme.of(context).colorScheme.surface,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12.r),
                  side: BorderSide(color: Theme.of(context).dividerColor),
                ),
                margin: EdgeInsets.only(bottom: 10.h),
                child: InkWell(
                  borderRadius: BorderRadius.circular(12.r),
                  onTap: () {
                    if (index != 0 && (provider.courseDetailModel?.isPurchase == false)) {
                      Utils.showSnackbarMessage(message: CommonString.message_purchase_course);
                    } else {
                      Utils.showSnackbarMessage(message: CommonString.message_go_to_purchase_screen);
                    }
                  },
                  child: Padding(
                    padding: EdgeInsets.all(12.w),
                    child: Row(
                      children: [
                        Container(
                          width: 36.w,
                          height: 36.w,
                          alignment: Alignment.center,
                          decoration: BoxDecoration(
                            color: Theme.of(context).colorScheme.primaryContainer,
                            shape: BoxShape.circle,
                          ),
                          child: Text(
                            "${index + 1}",
                            style: Theme.of(context).textTheme.titleMedium?.copyWith(
                                  color: Theme.of(context).colorScheme.primary,
                                  fontWeight: FontWeight.bold,
                                ),
                          ),
                        ),
                        SizedBox(width: 12.w),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                provider.courseDetail!.courseLessons[index].title,
                                maxLines: 1,
                                overflow: TextOverflow.ellipsis,
                                style: Theme.of(context).textTheme.bodyLarge?.copyWith(fontWeight: FontWeight.bold),
                              ),
                              Text(
                                "${provider.courseDetail!.courseLessons[index].duration} min",
                                style: Theme.of(context).textTheme.bodySmall,
                              ),
                            ],
                          ),
                        ),
                        Icon(Icons.lock_outline, color: Colors.grey[400], size: 20.sp),
                      ],
                    ),
                  ),
                ),
              );
            },
          ),
        ],
      ),
    );
  }

  Widget buildBottomButtonSection(BuildContext context, CourseDetailProvider provider) {
    bool isPending = (provider.courseDetailModel?.paymentStatus == 1 && provider.courseDetailModel?.pendingCourseId == widget.courseId);

    // Get user's country for hybrid payment routing
    String userCountry = provider.detectedCountryCode ?? PaymentConfig.detectedCountryCode ?? SharedPreferencesUtil.getString(SharedPreferencesKey.country);
    bool isIndianUser = PaymentConfig.isIndianUser(userCountry);

    // Get the correct price based on user's country
    String displayPrice = isIndianUser
        ? "${PaymentConfig.symbolInr}${provider.courseDetailModel?.price ?? widget.price}"
        : "${PaymentConfig.symbolUsd}${provider.courseDetailModel?.priceUsd ?? '29'}";

    return Container(
      padding: EdgeInsets.all(16.w),
      decoration: BoxDecoration(
        color: Theme.of(context).colorScheme.surface,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: Offset(0, -5),
          ),
        ],
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min, // Important for bottom sheet feel
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (!provider.isCourseFetchApiCalling && (provider.courseDetailModel?.isPurchase ?? false))
            Padding(
              padding: EdgeInsets.only(bottom: 8.h),
              child: Text(
                CommonString.message_already_purchase,
                style: Theme.of(context).textTheme.bodySmall?.copyWith(color: Colors.green),
              ),
            ),

          if (isPending)
            Container(
              margin: EdgeInsets.only(bottom: 12.h),
              padding: EdgeInsets.all(12.w),
              decoration: BoxDecoration(
                color: const Color(0xFFFFF8E1),
                borderRadius: BorderRadius.circular(10.r),
                border: Border.all(color: const Color(0xFFFFE082)),
              ),
              child: Row(
                children: [
                  Icon(Icons.hourglass_empty, color: Color(0xFFF57F17), size: 20.sp),
                  SizedBox(width: 12.w),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text("Verification Pending", style: Theme.of(context).textTheme.bodyMedium?.copyWith(fontWeight: FontWeight.bold, color: Color(0xFFF57F17))),
                        Text("Access will be granted within 24 hours.", style: Theme.of(context).textTheme.bodySmall?.copyWith(color: Color(0xFFF9A825))),
                      ],
                    ),
                  )
                ],
              ),
            ),

          if (!isPending)
            ElevatedButton(
              onPressed: () {
                if (provider.courseDetailModel == null) return;
                if (provider.courseDetailModel!.isPurchase) {
                  provider.resetProvider();
                  provider.gotoPurchaseCourseDetailScreen(context: context);
                } else {
                  if (!provider.isCourseVerifyApiCalling) {
                    if (isIndianUser) {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => ManualPaymentScreen(
                            courseId: provider.courseDetailModel!.courseId,
                            title: provider.courseDetailModel!.title,
                            price: provider.courseDetailModel!.price,
                          ),
                        ),
                      ).then((refresh) {
                        if (refresh == true) {
                          provider.fetchCourseDetail(context: context, courseId: widget.courseId);
                        }
                      });
                    } else {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => PayPalPaymentScreen(
                            courseId: provider.courseDetailModel!.courseId,
                            title: provider.courseDetailModel!.title,
                            price: provider.courseDetailModel!.priceUsd,
                          ),
                        ),
                      ).then((refresh) {
                        if (refresh == true) {
                          provider.fetchCourseDetail(context: context, courseId: widget.courseId);
                        }
                      });
                    }
                  }
                }
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: Theme.of(context).colorScheme.primary, // Orange
                foregroundColor: Theme.of(context).colorScheme.onPrimary, // White text
                padding: EdgeInsets.symmetric(vertical: 14.h),
                minimumSize: Size(double.infinity, 50.h),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12.r)),
                elevation: 0,
              ),
              child: provider.isCourseFetchApiCalling
                  ? SizedBox(height: 20.h, width: 20.h, child: CircularProgressIndicator(color: CommonColor.white, strokeWidth: 2))
                  : Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text(
                          (provider.courseDetailModel?.isPurchase ?? false)
                              ? CommonString.go_to_course
                              : "${CommonString.buy_course} $displayPrice",
                          style: Theme.of(context).textTheme.titleMedium?.copyWith(
                                color: Theme.of(context).colorScheme.onPrimary,
                                fontWeight: FontWeight.bold,
                              ),
                        ),
                        if (provider.isCourseVerifyApiCalling) ...[
                          SizedBox(width: 10.w),
                          SizedBox(height: 16.h, width: 16.h, child: CircularProgressIndicator(color: CommonColor.white, strokeWidth: 2)),
                        ]
                      ],
                    ),
            ),
        ],
        ),
      );
  }
}
