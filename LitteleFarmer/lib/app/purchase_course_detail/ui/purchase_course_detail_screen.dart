import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/purchase_course_detail/provider/purchase_course_detail_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/toolbar.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';
import 'package:video_player/video_player.dart';
import 'package:little_farmer/app/course_detail/ui/course_review_screen.dart';
import 'package:little_farmer/app/course_detail/ui/course_progress_dialog.dart';

// Custom Video Progress Indicator that doesn't pause on seek
class _CustomVideoProgressIndicator extends StatefulWidget {
  final VideoPlayerController controller;
  final VideoProgressColors colors;
  final Function(Duration) onSeek;

  const _CustomVideoProgressIndicator({
    required this.controller,
    required this.colors,
    required this.onSeek,
  });

  @override
  State<_CustomVideoProgressIndicator> createState() => _CustomVideoProgressIndicatorState();
}

class _CustomVideoProgressIndicatorState extends State<_CustomVideoProgressIndicator> {
  bool _isDragging = false;
  double _dragValue = 0.0;

  @override
  void initState() {
    super.initState();
    widget.controller.addListener(_onControllerUpdate);
  }

  @override
  void dispose() {
    widget.controller.removeListener(_onControllerUpdate);
    super.dispose();
  }

  void _onControllerUpdate() {
    if (!_isDragging && mounted) {
      setState(() {});
    }
  }

  @override
  Widget build(BuildContext context) {
    final duration = widget.controller.value.duration;
    final position = _isDragging 
        ? duration * _dragValue 
        : widget.controller.value.position;

    if (duration.inMilliseconds <= 0) {
      return SizedBox.shrink();
    }

    double value = position.inMilliseconds / duration.inMilliseconds;
    if (_isDragging) {
      value = _dragValue;
    }

    return GestureDetector(
      onHorizontalDragStart: (details) {
        if (!widget.controller.value.isInitialized) return;
        _isDragging = true;
        final RenderBox box = context.findRenderObject() as RenderBox;
        final x = details.localPosition.dx;
        final width = box.size.width;
        _dragValue = (x / width).clamp(0.0, 1.0);
        setState(() {});
      },
      onHorizontalDragUpdate: (details) {
        if (!widget.controller.value.isInitialized) return;
        final RenderBox box = context.findRenderObject() as RenderBox;
        final x = details.localPosition.dx;
        final width = box.size.width;
        _dragValue = (x / width).clamp(0.0, 1.0);
        setState(() {});
      },
      onHorizontalDragEnd: (details) {
        if (!widget.controller.value.isInitialized) return;
        _isDragging = false;
        final newPosition = duration * _dragValue;
        widget.onSeek(newPosition);
        setState(() {});
      },
      onTapDown: (details) {
        if (!widget.controller.value.isInitialized) return;
        final RenderBox box = context.findRenderObject() as RenderBox;
        final x = details.localPosition.dx;
        final width = box.size.width;
        final value = (x / width).clamp(0.0, 1.0);
        widget.onSeek(duration * value);
      },
      child: LayoutBuilder(
        builder: (context, constraints) {
          return Container(
            padding: EdgeInsets.symmetric(vertical: 8.h),
            child: SizedBox(
              height: 6.h,
              child: Stack(
              children: [
                // Background
                Container(
                  height: 6.h,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(2.r),
                    color: widget.colors.backgroundColor,
                  ),
                ),
                // Buffered indicator
                if (widget.controller.value.buffered.isNotEmpty)
                  ...widget.controller.value.buffered.map((range) {
                    final start = range.start.inMilliseconds / duration.inMilliseconds;
                    final end = range.end.inMilliseconds / duration.inMilliseconds;
                    return Positioned(
                      left: constraints.maxWidth * start,
                      top: 0,
                      bottom: 0,
                      child: Container(
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(2.r),
                          color: widget.colors.bufferedColor,
                        ),
                        width: constraints.maxWidth * (end - start),
                      ),
                    );
                  }),
                // Played indicator
                FractionallySizedBox(
                  widthFactor: value,
                  alignment: Alignment.centerLeft,
                  child: Container(
                    height: 6.h,
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(2.r),
                      color: widget.colors.playedColor,
                    ),
                  ),
                ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}

// ignore: must_be_immutable
class PurchaseCourseDetailScreen extends StatefulWidget {
  int courseId;
  String title;
  PurchaseCourseDetailScreen({super.key, required this.courseId, required this.title});

  @override
  State<PurchaseCourseDetailScreen> createState() => _PurchaseCourseDetailScreenState();
}

class _PurchaseCourseDetailScreenState extends State<PurchaseCourseDetailScreen> {
  late PurchaseCourseDetailProvider provider;
  
  // Helper function to format duration
  String _formatDuration(Duration duration) {
    String twoDigits(int n) => n.toString().padLeft(2, "0");
    String hours = twoDigits(duration.inHours);
    String minutes = twoDigits(duration.inMinutes.remainder(60));
    String seconds = twoDigits(duration.inSeconds.remainder(60));
    if (duration.inHours > 0) {
      return "$hours:$minutes:$seconds";
    } else {
      return "$minutes:$seconds";
    }
  }

  @override
  void initState() {
    super.initState();
    provider = Provider.of<PurchaseCourseDetailProvider>(context, listen: false);
    provider.courseId = widget.courseId;
    provider.fetchCourseDetail(context: context);
  }

  @override
  void dispose() {
    // Don't call resetProvider during dispose - it can cause "setState during dispose" errors
    // The provider will be cleaned up automatically when the widget is disposed
    // Only dispose the video controller if needed
    try {
      if (provider.isControllerInitialize) {
        provider.controller.pause();
        // Dispose asynchronously to avoid blocking dispose
        provider.controller.dispose().catchError((e) {
          debugPrint('Error disposing controller in screen dispose: $e');
        });
      }
    } catch (e) {
      debugPrint('Error in screen dispose: $e');
    }
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: CommonColor.bg_main,
      extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Consumer(
          builder: (context, PurchaseCourseDetailProvider provider, _) {
            return Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                if (!provider.isFullScreen) Container(margin: EdgeInsets.all(10.h), child: Toolbar(title: widget.title)),
                provider.isPurchaseCourseDetailFetchApiCalling
                    ? Expanded(
                        child: Container(
                          alignment: Alignment.center,
                          child: CircularProgressIndicator(color: CommonColor.bg_button),
                        ),
                      )
                    : provider.isFullScreen
                        ? Expanded(
                            child: SizedBox(
                              height: MediaQuery.of(context).size.height,
                              width: MediaQuery.of(context).size.width,
                              child: Stack(
                                children: [
                                  provider.isControllerInitialize && provider.controller.value.isInitialized
                                      ? Container(
                                          color: CommonColor.black,
                                          alignment: Alignment.center,
                                          width: double.infinity,
                                          height: double.infinity,
                                          child: RepaintBoundary(
                                            child: AspectRatio(
                                              aspectRatio: provider.controller.value.aspectRatio,
                                              child: VideoPlayer(
                                                provider.controller,
                                                key: ValueKey('video_${provider.controller.hashCode}'), // Prevent reuse of disposed controller
                                              ),
                                            ),
                                          ),
                                        )
                                      : CircularProgressIndicator(),
                                  // Auto-Advance Overlay in fullscreen
                                  if (provider.isAutoAdvancing)
                                    Positioned.fill(
                                      child: Container(
                                        color: Colors.black.withOpacity(0.8),
                                        child: Center(
                                          child: Column(
                                            mainAxisSize: MainAxisSize.min,
                                            children: [
                                              Text(
                                                "Next Lesson in",
                                                style: TextStyle(color: Colors.white, fontSize: 20.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                              ),
                                              SizedBox(height: 15.h),
                                              Text(
                                                "${provider.autoAdvanceSeconds}",
                                                style: TextStyle(color: Colors.white, fontSize: 70.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                              ),
                                              SizedBox(height: 25.h),
                                              Row(
                                                mainAxisSize: MainAxisSize.min,
                                                children: [
                                                  OutlinedButton(
                                                    onPressed: () => provider.cancelAutoAdvance(),
                                                    style: OutlinedButton.styleFrom(
                                                      foregroundColor: Colors.white,
                                                      side: BorderSide(color: Colors.white, width: 2),
                                                      padding: EdgeInsets.symmetric(horizontal: 24.w, vertical: 12.h),
                                                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(25.r)),
                                                    ),
                                                    child: Text("Cancel", style: TextStyle(fontSize: 16.sp, fontFamily: Constant.manrope)),
                                                  ),
                                                  SizedBox(width: 20.w),
                                                  ElevatedButton(
                                                    onPressed: () => provider.skipToNextItem(context),
                                                    style: ElevatedButton.styleFrom(
                                                      backgroundColor: Color(0xFFF39200),
                                                      foregroundColor: Colors.white,
                                                      padding: EdgeInsets.symmetric(horizontal: 24.w, vertical: 12.h),
                                                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(25.r)),
                                                    ),
                                                    child: Text("Next Lesson", style: TextStyle(fontSize: 16.sp, fontFamily: Constant.manrope)),
                                                  ),
                                                ],
                                              ),
                                            ],
                                          ),
                                        ),
                                      ),
                                    ),
                                  if (provider.isControllerVisible)
                                    Positioned(
                                      bottom: 0,
                                      left: 0,
                                      right: 0,
                                      child: buildVideoControls(context),
                                    ),
                                ],
                              ),
                            ),
                          )
                        : Expanded(
                            child: SingleChildScrollView(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  // Fixed video player at top
                                  Container(
                                    margin: EdgeInsets.symmetric(horizontal: 10.h),
                                    child: buildMediaContent(context),
                                  ),
                                  SizedBox(height: 10.h),
                                  Padding(
                                    padding: EdgeInsets.symmetric(horizontal: 10.h),
                                    child: Text(
                                      provider.courseDetailModel?.courseTitle ?? "",
                                      maxLines: 2,
                                      overflow: TextOverflow.ellipsis,
                                      style: TextStyle(color: CommonColor.black, fontWeight: FontWeight.bold, fontSize: 17.sp, fontFamily: Constant.manrope),
                                    ),
                                  ),
                                  SizedBox(height: 10.h),
                                  // Scrollable sections list
                                  if (provider.courseDetailModel != null)
                                    Padding(
                                      padding: EdgeInsets.symmetric(horizontal: 10.h),
                                      child: buildCourseSections(context),
                                    ),
                                ],
                              ),
                            ),
                          ),
              ],
            );
          },
        ),
      ),
    );
  }

  Widget buildMediaContent(BuildContext context) {
    return Stack(
      children: [
        if (provider.isQuiz)
          SizedBox(
              height: 180.h,
              child: Stack(children: [
                ClipRRect(
                  borderRadius: BorderRadius.circular(20.h),
                  child: SvgPicture.asset(CommonImage.app_logo, height: 180.h, alignment: Alignment.center, width: MediaQuery.of(context).size.width, fit: BoxFit.fill),
                ),
                SizedBox(
                  height: 180.h,
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(20.h),
                    child: Container(
                      color: CommonColor.black.withAlpha(50),
                      alignment: Alignment.center,
                      child: GestureDetector(
                        onTap: () {
                          provider.gotoQuizScreen(context: context);
                        },
                        child: Container(
                          padding: EdgeInsets.symmetric(vertical: 10.h, horizontal: 15.h),
                          decoration: BoxDecoration(border: Border.all(width: 2.w, color: CommonColor.white), borderRadius: BorderRadius.circular(30.h)),
                          child:
                              Text(CommonString.open_quiz, style: TextStyle(color: CommonColor.white, fontSize: 14.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope)),
                        ),
                      ),
                    ),
                  ),
                )
              ])),
        // Removed article UI - articles are handled differently now
        if (provider.isVideo)
          provider.isControllerInitialize && provider.controller.value.isInitialized
              ? Container(
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(15.h),
                    boxShadow: [
                      BoxShadow(color: CommonColor.black.withAlpha(20), spreadRadius: 2, blurRadius: 5, offset: Offset(0, 3)),
                    ],
                  ),
                  child: Stack(
                    children: [
                      // CRITICAL FOR iOS: Video player must be directly accessible for touch events
                      // NO overlays blocking it - use Stack with absolute positioning for controls only
                      ClipRRect(
                        borderRadius: BorderRadius.circular(15.h),
                        child: RepaintBoundary(
                          child: AspectRatio(
                            aspectRatio: provider.controller.value.aspectRatio,
                            child: GestureDetector(
                              // CRITICAL FOR iOS: Use deferToChild to allow video player to receive touches
                              // This is essential - iOS VideoPlayer is a texture and needs this to work
                              behavior: HitTestBehavior.deferToChild,
                              onTap: () {
                                // Toggle controls visibility when tapping video
                                provider.controllerTimer();
                              },
                              child: VideoPlayer(
                                provider.controller,
                                key: ValueKey('video_${provider.controller.hashCode}'), // Prevent reuse of disposed controller
                              ),
                            ),
                          ),
                        ),
                      ),
                      // Center Big Play/Pause Button - Show when video is paused
                      // CRITICAL FOR iOS: Use IgnorePointer to allow touches to pass through to video player
                      // Only the button itself should capture touches
                      if (provider.controller.value.isInitialized && 
                          !provider.controller.value.isPlaying && 
                          !provider.isAutoAdvancing)
                        Positioned.fill(
                          child: IgnorePointer(
                            ignoring: true, // CRITICAL: Allow ALL touches to pass through to video player
                            child: Center(
                              child: IgnorePointer(
                                ignoring: false, // Re-enable pointer ONLY for the button itself
                                child: GestureDetector(
                                  behavior: HitTestBehavior.opaque, // Only detect touches on the button
                                  onTap: () async {
                                    await provider.playVideo();
                                    setState(() {});
                                  },
                                  child: Container(
                                    height: 50.h,
                                    width: 50.h,
                                    decoration: BoxDecoration(
                                      color: Color(0xFF00ADEE),
                                      shape: BoxShape.circle,
                                      boxShadow: [
                                        BoxShadow(
                                          color: Colors.black.withOpacity(0.3),
                                          blurRadius: 8,
                                          spreadRadius: 2,
                                        ),
                                      ],
                                    ),
                                    child: Icon(Icons.play_arrow, color: Colors.white, size: 28.sp),
                                  ),
                                ),
                              ),
                            ),
                          ),
                        ),

                    // Auto-Advance Overlay - Shows countdown after video finishes (matching website)
                    if (provider.isAutoAdvancing)
                      Positioned.fill(
                        child: Container(
                          decoration: BoxDecoration(
                            color: Colors.black.withOpacity(0.85),
                            borderRadius: BorderRadius.circular(15.h),
                          ),
                          child: SafeArea(
                            child: Center(
                              child: SingleChildScrollView(
                                child: Column(
                                  mainAxisSize: MainAxisSize.min,
                                  children: [
                                    Text(
                                      "Next Lesson in",
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontSize: 20.sp,
                                        fontWeight: FontWeight.bold,
                                        fontFamily: Constant.manrope,
                                      ),
                                    ),
                                    SizedBox(height: 15.h),
                                    Text(
                                      "${provider.autoAdvanceSeconds}",
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontSize: 80.sp,
                                        fontWeight: FontWeight.bold,
                                        fontFamily: Constant.manrope,
                                        height: 1.0,
                                      ),
                                    ),
                                    SizedBox(height: 25.h),
                                    // Buttons row
                                    Padding(
                                      padding: EdgeInsets.symmetric(horizontal: 10.w),
                                      child: Row(
                                        mainAxisSize: MainAxisSize.min,
                                        children: [
                                          OutlinedButton(
                                            onPressed: () => provider.cancelAutoAdvance(),
                                            style: OutlinedButton.styleFrom(
                                              foregroundColor: Colors.white,
                                              side: BorderSide(color: Colors.white, width: 2),
                                              padding: EdgeInsets.symmetric(horizontal: 20.w, vertical: 10.h),
                                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(25.r)),
                                            ),
                                            child: Text(
                                              "Cancel",
                                              style: TextStyle(
                                                fontSize: 14.sp,
                                                fontWeight: FontWeight.w600,
                                                fontFamily: Constant.manrope,
                                              ),
                                            ),
                                          ),
                                          SizedBox(width: 15.w),
                                          ElevatedButton(
                                            onPressed: () => provider.skipToNextItem(context),
                                            style: ElevatedButton.styleFrom(
                                              backgroundColor: Color(0xFFF39200),
                                              foregroundColor: Colors.white,
                                              padding: EdgeInsets.symmetric(horizontal: 20.w, vertical: 10.h),
                                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(25.r)),
                                              elevation: 4,
                                            ),
                                            child: Text(
                                              "Next Lesson",
                                              style: TextStyle(
                                                fontSize: 14.sp,
                                                fontWeight: FontWeight.w600,
                                                fontFamily: Constant.manrope,
                                              ),
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ),
                        ),
                      ),

                    // Video controls overlay - properly positioned with better visibility
                    // CRITICAL FOR iOS: Use IgnorePointer to allow touches to pass through to video player
                    // Only the control buttons themselves should capture touches
                    if (provider.isControllerVisible && !provider.isAutoAdvancing)
                      Positioned(
                        bottom: 0,
                        left: 0,
                        right: 0,
                        child: IgnorePointer(
                          ignoring: true, // Allow touches to pass through to video player
                          child: Container(
                            decoration: BoxDecoration(
                              gradient: LinearGradient(
                                begin: Alignment.bottomCenter,
                                end: Alignment.topCenter,
                                colors: [Colors.black.withOpacity(0.85), Colors.transparent],
                              ),
                            ),
                            padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 10.h),
                            child: SafeArea(
                              top: false,
                              child: IgnorePointer(
                                ignoring: false, // Re-enable pointer for controls
                                child: Column(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                // Custom Progress bar - More visible like website and doesn't pause on seek
                                _CustomVideoProgressIndicator(
                                  controller: provider.controller,
                                  colors: VideoProgressColors(
                                    playedColor: Color(0xFF00ADEE), 
                                    bufferedColor: Colors.grey.shade600, 
                                    backgroundColor: Colors.white.withOpacity(0.5),
                                  ),
                                  onSeek: (position) => provider.seekToPosition(position),
                                ),
                                SizedBox(height: 10.h),
                                // Controls row - Fixed overflow
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  crossAxisAlignment: CrossAxisAlignment.center,
                                  children: [
                                    // Rewind 10
                                    IconButton(
                                      icon: Icon(Icons.replay_10, color: Colors.white, size: 20.sp),
                                      padding: EdgeInsets.all(4.w),
                                      constraints: BoxConstraints(minWidth: 36.w, minHeight: 36.h),
                                      onPressed: () => provider.seekToPosition(provider.currentPosition - Duration(seconds: 10)),
                                    ),
                                    SizedBox(width: 4.w),
                                    // Play/Pause - Centered
                                    Container(
                                      decoration: BoxDecoration(
                                        color: Colors.white.withOpacity(0.2),
                                        shape: BoxShape.circle,
                                      ),
                                      child: IconButton(
                                        icon: Icon(
                                          provider.controller.value.isPlaying ? Icons.pause : Icons.play_arrow,
                                          color: Colors.white,
                                          size: 20.sp,
                                        ),
                                        padding: EdgeInsets.all(6.w),
                                        constraints: BoxConstraints(minWidth: 40.w, minHeight: 40.h),
                                        onPressed: () async {
                                          if (provider.controller.value.isPlaying) {
                                            provider.controller.pause();
                                          } else {
                                            await provider.playVideo();
                                          }
                                          setState(() {});
                                        },
                                      ),
                                    ),
                                    SizedBox(width: 4.w),
                                    // Forward 10
                                    IconButton(
                                      icon: Icon(Icons.forward_10, color: Colors.white, size: 20.sp),
                                      padding: EdgeInsets.all(4.w),
                                      constraints: BoxConstraints(minWidth: 36.w, minHeight: 36.h),
                                      onPressed: () => provider.seekToPosition(provider.currentPosition + Duration(seconds: 10)),
                                    ),
                                    SizedBox(width: 8.w),
                                    // Time display - More visible and larger
                                    Flexible(
                                      child: Container(
                                        padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 4.h),
                                        decoration: BoxDecoration(
                                          color: Colors.black.withOpacity(0.7),
                                          borderRadius: BorderRadius.circular(4.r),
                                        ),
                                        child: FittedBox(
                                          fit: BoxFit.scaleDown,
                                          child: Text(
                                            _formatDuration(provider.currentPosition) + ' / ' + (provider.duration.isNotEmpty ? provider.duration[0] : "--:--"),
                                            style: TextStyle(
                                              fontSize: 13.sp,
                                              color: Colors.white,
                                              fontFamily: Constant.manrope,
                                              fontWeight: FontWeight.w600,
                                              letterSpacing: 0.5,
                                            ),
                                            maxLines: 1,
                                          ),
                                        ),
                                      ),
                                    ),
                                    SizedBox(width: 4.w),
                                    // Volume
                                    IconButton(
                                      icon: Icon(provider.isMute ? Icons.volume_off : Icons.volume_up, color: Colors.white, size: 20.sp),
                                      padding: EdgeInsets.all(4.w),
                                      constraints: BoxConstraints(minWidth: 36.w, minHeight: 36.h),
                                      onPressed: () => provider.videoMute(),
                                    ),
                                    SizedBox(width: 2.w),
                                    // Settings
                                    IconButton(
                                      icon: Icon(Icons.settings, color: Colors.white, size: 20.sp),
                                      padding: EdgeInsets.all(4.w),
                                      constraints: BoxConstraints(minWidth: 36.w, minHeight: 36.h),
                                      onPressed: () {}, // Speed/Quality settings
                                    ),
                                    SizedBox(width: 2.w),
                                    // Fullscreen
                                    IconButton(
                                      icon: Icon(Icons.fullscreen, color: Colors.white, size: 20.sp),
                                      padding: EdgeInsets.all(4.w),
                                      constraints: BoxConstraints(minWidth: 36.w, minHeight: 36.h),
                                      onPressed: () {
                                        setState(() {
                                          provider.isFullScreen = true;
                                          SystemChrome.setEnabledSystemUIMode(SystemUiMode.manual, overlays: []);
                                          SystemChrome.setPreferredOrientations([DeviceOrientation.landscapeLeft, DeviceOrientation.landscapeRight]);
                                        });
                                      },
                                    ),
                                  ],
                                ),
                              ],
                            ),
                          ),
                        ),
                      ),
                    ),
                  )
                  ]),
                )
              : GestureDetector(
                  onTap: () {
                    provider.controllerTimer();
                  },
                  child: Container(
                    height: 180.h,
                    width: MediaQuery.of(context).size.width,
                    alignment: Alignment.center,
                    child: CircularProgressIndicator(valueColor: AlwaysStoppedAnimation<Color>(CommonColor.bg_button)),
                  ),
                )
      ],
    );
  }

  Widget buildVideoControls(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.bottomCenter,
          end: Alignment.topCenter,
          colors: [Colors.black.withOpacity(0.8), Colors.transparent],
        ),
      ),
      padding: EdgeInsets.symmetric(horizontal: 8.w, vertical: 8.h),
      child: SafeArea(
        top: false,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            VideoProgressIndicator(
              provider.controller,
              allowScrubbing: true,
              colors: VideoProgressColors(
                playedColor: Color(0xFF00ADEE), 
                bufferedColor: Colors.grey.shade600, 
                backgroundColor: Colors.white.withOpacity(0.3)
              ),
            ),
            SizedBox(height: 8.h),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                IconButton(
                  icon: Icon(Icons.replay_10, color: Colors.white, size: 22.sp),
                  padding: EdgeInsets.all(4.w),
                  constraints: BoxConstraints(minWidth: 36.w, minHeight: 36.h),
                  onPressed: () => provider.seekToPosition(provider.currentPosition - Duration(seconds: 10)),
                ),
                SizedBox(width: 4.w),
                Container(
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.2),
                    shape: BoxShape.circle,
                  ),
                  child: IconButton(
                    icon: Icon(provider.controller.value.isPlaying ? Icons.pause : Icons.play_arrow, color: Colors.white, size: 22.sp),
                    padding: EdgeInsets.all(6.w),
                    constraints: BoxConstraints(minWidth: 38.w, minHeight: 38.h),
                    onPressed: () async {
                      if (provider.controller.value.isPlaying) {
                        provider.controller.pause();
                      } else {
                        await provider.playVideo();
                      }
                      setState(() {});
                    },
                  ),
                ),
                SizedBox(width: 4.w),
                IconButton(
                  icon: Icon(Icons.forward_10, color: Colors.white, size: 22.sp),
                  padding: EdgeInsets.all(4.w),
                  constraints: BoxConstraints(minWidth: 36.w, minHeight: 36.h),
                  onPressed: () => provider.controller.seekTo(provider.currentPosition + Duration(seconds: 10)),
                ),
                SizedBox(width: 8.w),
                Flexible(
                  child: Container(
                    padding: EdgeInsets.symmetric(horizontal: 6.w, vertical: 3.h),
                    decoration: BoxDecoration(
                      color: Colors.black.withOpacity(0.4),
                      borderRadius: BorderRadius.circular(4.r),
                    ),
                    child: Text(
                      '${provider.currentPosition.toString().split(".")[0]} / ${provider.duration.isNotEmpty ? provider.duration[0] : "--:--"}',
                      style: TextStyle(fontSize: 11.sp, color: Colors.white, fontFamily: Constant.manrope, fontWeight: FontWeight.w500),
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                ),
                SizedBox(width: 4.w),
                IconButton(
                  icon: Icon(provider.isMute ? Icons.volume_off : Icons.volume_up, color: Colors.white, size: 20.sp),
                  padding: EdgeInsets.all(4.w),
                  constraints: BoxConstraints(minWidth: 36.w, minHeight: 36.h),
                  onPressed: () => provider.videoMute(),
                ),
                SizedBox(width: 2.w),
                IconButton(
                  icon: Icon(Icons.settings, color: Colors.white, size: 20.sp),
                  padding: EdgeInsets.all(4.w),
                  constraints: BoxConstraints(minWidth: 36.w, minHeight: 36.h),
                  onPressed: () {}, // Speed/Quality settings
                ),
                SizedBox(width: 2.w),
                IconButton(
                  icon: Icon(Icons.fullscreen_exit, color: Colors.white, size: 20.sp),
                  padding: EdgeInsets.all(4.w),
                  constraints: BoxConstraints(minWidth: 36.w, minHeight: 36.h),
                  onPressed: () {
                    setState(() {
                      provider.isFullScreen = false;
                      provider.streamController.close();
                      SystemChrome.setEnabledSystemUIMode(SystemUiMode.manual, overlays: SystemUiOverlay.values);
                      SystemChrome.setPreferredOrientations([DeviceOrientation.portraitUp]);
                    });
                  },
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget buildCourseSections(BuildContext context) {
    if (provider.courseDetailModel == null) return SizedBox();
    
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        Padding(
          padding: EdgeInsets.all(15.h),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                padding: EdgeInsets.symmetric(horizontal: 10.w, vertical: 5.h),
                decoration: BoxDecoration(
                  color: Color(0xFFE8F5E9),
                  borderRadius: BorderRadius.circular(5.r),
                  border: Border.all(color: Color(0xFF4CAF50)),
                ),
                child: Text("Course Unlocked", style: TextStyle(color: Color(0xFF2E7D32), fontSize: 12.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope)),
              ),
              Column(
                children: [
                  Stack(
                    alignment: Alignment.center,
                    children: [
                      SizedBox(
                        height: 36.h,
                        width: 36.h,
                        child: CircularProgressIndicator(
                          value: provider.totalLesson > 0 ? provider.totalCompletedLesson / provider.totalLesson : 0,
                          backgroundColor: Colors.grey.withOpacity(0.2),
                          valueColor: AlwaysStoppedAnimation<Color>(Color(0xFFF39200)),
                          strokeWidth: 4,
                        ),
                      ),
                      Icon(Icons.emoji_events_outlined, color: Color(0xFFF39200), size: 18.sp),
                    ],
                  ),
                  SizedBox(height: 4.h),
                  Text("${provider.totalCompletedLesson}/${provider.totalLesson}", style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12.sp, fontFamily: Constant.manrope)),
                ],
              ),
            ],
          ),
        ),
        ListView.builder(
          physics: const NeverScrollableScrollPhysics(),
          shrinkWrap: true,
          itemCount: provider.courseDetailModel!.sections.length,
          itemBuilder: (context, index) {
            return Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Section Title (matches Image 1)
                Container(
                  width: double.infinity,
                  padding: EdgeInsets.symmetric(horizontal: 15.w, vertical: 10.h),
                  color: Colors.white,
                  child: Row(
                    children: [
                      Icon(Icons.layers, color: Color(0xFFF39200), size: 18.sp),
                      SizedBox(width: 8.w),
                      Expanded(
                        child: Text(
                          provider.courseDetailModel!.sections[index].sectionTitle,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: TextStyle(color: CommonColor.black, fontWeight: FontWeight.bold, fontSize: 13.sp, fontFamily: Constant.manrope),
                        ),
                      ),
                    ],
                  ),
                ),
                buildSectionItems(context, index),
              ],
            );
          },
        ),
      ],
    );
  }

  Widget buildSectionItems(BuildContext context, int index) {
    bool isLastSection = index == (provider.courseDetailModel!.sections.length - 1);
    
    // Filter out sample tests from items
    List items = provider.courseDetailModel!.sections[index].items
        .where((item) => !item.itemName.toLowerCase().contains('sample'))
        .toList();
    
    int itemsLength = items.length;
    // For last section, add 2 extra items (Review, Certificate)
    // For others, just items
    int totalCount = isLastSection ? itemsLength + 2 : itemsLength;

    return ListView.builder(
      physics: const NeverScrollableScrollPhysics(),
      shrinkWrap: true,
      itemCount: totalCount,
      itemBuilder: (context, position) {
        
        // CASE 1: Normal Lesson Item
        if (position < itemsLength) {
          // Get filtered items (excluding sample tests)
          List filteredItems = provider.courseDetailModel!.sections[index].items
              .where((item) => !item.itemName.toLowerCase().contains('sample'))
              .toList();
          final item = filteredItems[position];
          
          // Find original index in unfiltered list for current item tracking
          int originalIndex = provider.courseDetailModel!.sections[index].items.indexOf(item);
          bool isCurrent = (index == provider.currentSection && originalIndex == provider.currentVideoInSection);
          
          return Column(
            children: [
               InkWell(
                    onTap: () {
                      // Use original index, not filtered position
                      provider.courseClicked(context: context, section: index, sectionItem: originalIndex);
                    },
                    child: Container(
                      padding: EdgeInsets.symmetric(vertical: 12.h, horizontal: 15.h),
                      decoration: BoxDecoration(
                        color: isCurrent ? Color(0xFFFFF7F0) : CommonColor.bg_main,
                        border: isCurrent ? Border(left: BorderSide(color: Color(0xFFF39200), width: 4.w)) : null,
                      ),
                      child: Row(
                        children: [
                          Container(
                            margin: EdgeInsets.only(right: 10.w),
                            width: 20.sp,
                            child: !item.isAccessible
                              ? Icon(Icons.lock, color: Colors.grey, size: 20.sp)
                              : item.isComplete && !isCurrent
                                ? Icon(Icons.check_circle, color: Color(0xFF43A047), size: 20.sp) // Green checkmark for completed
                              : isCurrent 
                                ? (item.isVideo 
                                    ? Icon(Icons.equalizer, color: Color(0xFFF39200), size: 20.sp) // Orange equalizer for playing video
                                    : item.isQuiz
                                      ? Icon(Icons.quiz, color: Color(0xFF2196F3), size: 20.sp) // Blue quiz icon for quiz
                                      : Icon(Icons.article, color: Color(0xFF9C27B0), size: 20.sp)) // Purple article icon for article
                                : Icon(Icons.play_circle_outline, color: Colors.grey, size: 20.sp),
                          ),
                          
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  "${(position + 1).toString().padLeft(2, '0')}. ${item.itemName}",
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                  style: TextStyle(
                                    color: isCurrent ? Color(0xFFF39200) : CommonColor.black, 
                                    fontWeight: isCurrent ? FontWeight.w900 : FontWeight.w500, 
                                    fontSize: 14.sp, 
                                    fontFamily: Constant.manrope
                                  ),
                                ),
                                // Show "PLAYING" tag only for videos, not quizzes/articles
                                if (isCurrent && item.isVideo)
                                  Container(
                                    margin: EdgeInsets.only(top: 4.h),
                                    padding: EdgeInsets.symmetric(horizontal: 6.w, vertical: 2.h),
                                    decoration: BoxDecoration(
                                      color: Color(0xFFF39200),
                                      borderRadius: BorderRadius.circular(4.r),
                                    ),
                                    child: Text(
                                      "PLAYING",
                                      style: TextStyle(color: Colors.white, fontSize: 10.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                    ),
                                  )
                                // Show "QUIZ" tag for quizzes
                                else if (isCurrent && item.isQuiz)
                                  Container(
                                    margin: EdgeInsets.only(top: 4.h),
                                    padding: EdgeInsets.symmetric(horizontal: 6.w, vertical: 2.h),
                                    decoration: BoxDecoration(
                                      color: Color(0xFF2196F3),
                                      borderRadius: BorderRadius.circular(4.r),
                                    ),
                                    child: Text(
                                      "QUIZ",
                                      style: TextStyle(color: Colors.white, fontSize: 10.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                    ),
                                  )
                                // Show "ARTICLE" tag for articles
                                else if (isCurrent && item.isArticle)
                                  Container(
                                    margin: EdgeInsets.only(top: 4.h),
                                    padding: EdgeInsets.symmetric(horizontal: 6.w, vertical: 2.h),
                                    decoration: BoxDecoration(
                                      color: Color(0xFF9C27B0),
                                      borderRadius: BorderRadius.circular(4.r),
                                    ),
                                    child: Text(
                                      "ARTICLE",
                                      style: TextStyle(color: Colors.white, fontSize: 10.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                    ),
                                  ),
                              ],
                            ),
                          ),
                          SizedBox(width: 10.w),
                          Text(
                            item.lessonDuration,
                            style: TextStyle(color: Colors.grey, fontSize: 12.sp, fontFamily: Constant.manrope),
                          ),
                        ],
                      ),
                    ),
                  ),
              Container(height: 1.0, margin: EdgeInsets.symmetric(horizontal: 10.h), color: Colors.grey.withOpacity(0.2))
            ],
          );
        }

        // CASE 2: Course Review Item (Position == itemsLength)
        if (isLastSection && position == itemsLength) {
          bool allItemsComplete = provider.totalLesson == provider.totalCompletedLesson;
          
          return Column(
            children: [
              InkWell(
                onTap: () {
                    if (!allItemsComplete) {
                      Utils.showSnackbarMessage(message: "Please complete all lessons and tests to unlock the review.");
                      return;
                    }
                    if (provider.isControllerInitialize) {
                      provider.controller.pause();
                    }
                    Navigator.push(context, MaterialPageRoute(builder: (context) => CourseReviewScreen(courseId: widget.courseId)));
                },
                child: Container(
                  padding: EdgeInsets.symmetric(vertical: 15.h, horizontal: 15.h),
                  color: allItemsComplete ? CommonColor.bg_main : Colors.grey.withOpacity(0.1),
                  child: Row(
                    children: [
                      Icon(Icons.rate_review_outlined, size: 20.sp, color: allItemsComplete ? CommonColor.black : Colors.grey),
                      SizedBox(width: 15.w),
                      Expanded(
                        child: Text(
                          "Course Review",
                          style: TextStyle(
                            color: allItemsComplete ? CommonColor.black : Colors.grey, 
                            fontWeight: FontWeight.w500, 
                            fontSize: 14.sp, 
                            fontFamily: Constant.manrope
                          ),
                        ),
                      ),
                      if (!allItemsComplete)
                        Icon(Icons.lock, size: 14.sp, color: Colors.grey)
                      else
                        Icon(Icons.arrow_forward_ios, size: 14.sp, color: CommonColor.grey)
                    ],
                  ),
                ),
              ),
              Container(height: 1.0, margin: EdgeInsets.symmetric(horizontal: 10.h), color: Colors.grey.withOpacity(0.2))
            ],
          );
        }

        // CASE 3: Download Certificate Item (Position == itemsLength + 1)
        if (isLastSection && position == itemsLength + 1) {
          return Column(
            children: [
              InkWell(
                onTap: () async {
                    if (provider.isControllerInitialize) {
                      provider.controller.pause();
                    }
                    // Refresh completion status before showing dialog
                    await Provider.of<PurchaseCourseDetailProvider>(context, listen: false).checkCourseCompletion(courseId: widget.courseId);
                    if (mounted) {
                      showDialog(context: context, builder: (_) => CourseProgressDialog(courseId: widget.courseId, courseName: widget.title));
                    }
                },
                child: Container(
                  padding: EdgeInsets.symmetric(vertical: 15.h, horizontal: 15.h),
                  color: CommonColor.bg_main,
                  child: Row(
                    children: [
                      Icon(Icons.workspace_premium, size: 20.sp, color: CommonColor.black),
                      SizedBox(width: 15.w),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              CommonString.download_my_certificate,
                              style: TextStyle(color: CommonColor.black, fontWeight: FontWeight.w500, fontSize: 14.sp, fontFamily: Constant.manrope),
                            ),
                            Text(
                              CommonString.certificate,
                              style: TextStyle(color: Colors.grey.shade700, fontWeight: FontWeight.w500, fontSize: 12.sp, fontFamily: Constant.manrope),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              Container(height: 15.h) // Bottom padding
            ],
          );
        }

        return SizedBox();
      },
    );
  }
}
