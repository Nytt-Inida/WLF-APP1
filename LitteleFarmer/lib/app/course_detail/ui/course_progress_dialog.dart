import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:little_farmer/app/purchase_course_detail/provider/purchase_course_detail_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:provider/provider.dart';

class CourseProgressDialog extends StatefulWidget {
  final int courseId;
  final String courseName;

  const CourseProgressDialog({Key? key, required this.courseId, required this.courseName}) : super(key: key);

  @override
  State<CourseProgressDialog> createState() => _CourseProgressDialogState();
}

class _CourseProgressDialogState extends State<CourseProgressDialog> {

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<PurchaseCourseDetailProvider>(context, listen: false).checkCourseCompletion(courseId: widget.courseId);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16.r)),
      child: Consumer<PurchaseCourseDetailProvider>(
        builder: (context, provider, child) {
          if (provider.isCompletionCheckApiCalling || provider.courseCompletionModel == null) {
            return Container(
              padding: EdgeInsets.all(24.w),
              height: 200.h,
              child: Center(child: CircularProgressIndicator(color: CommonColor.bg_button)),
            );
          }

          final stats = provider.courseCompletionModel!;
          // final lessonProgress = stats.lessonStats.totalCount > 0 ? (stats.lessonStats.completedCount / stats.lessonStats.totalCount) : 0.0;
          // final quizProgress = stats.quizStats.totalCount > 0 ? (stats.quizStats.completedCount / stats.quizStats.totalCount) : 0.0;
          
          double reviewProgress = 0.0;
          if (stats.reviewStats.totalCount > 0) {
             reviewProgress = stats.reviewStats.completedCount >= stats.reviewStats.totalCount ? 1.0 : 0.0;
          } else if (!stats.reviewRequired) {
             reviewProgress = 1.0; // Considered complete if not required
          }

          // Simple percentage calc
          int totalItems = stats.lessonStats.totalCount + stats.quizStats.totalCount + (stats.reviewStats.totalCount > 0 ? 1 : 0);
          int completedItems = stats.lessonStats.completedCount + stats.quizStats.completedCount + (reviewProgress == 1.0 ? 1 : 0);
          
          final totalProgressPercent = totalItems > 0 ? (completedItems / totalItems * 100).toInt() : 0;

          return Padding(
            padding: EdgeInsets.all(20.w),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text("Course Progress", style: TextStyle(color: CommonColor.black, fontSize: 18.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope)),
                    IconButton(
                      icon: Icon(Icons.close, size: 20.sp),
                      onPressed: () => Navigator.pop(context),
                      padding: EdgeInsets.zero,
                      constraints: BoxConstraints(),
                    )
                  ],
                ),
                SizedBox(height: 8.h),
                Text("You're $totalProgressPercent% complete!", style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontFamily: Constant.manrope)),
                SizedBox(height: 16.h),
                
                // Warning Box
                if (!stats.completed)
                  Container(
                    width: double.infinity,
                    padding: EdgeInsets.all(12.w),
                    decoration: BoxDecoration(
                      color: Color(0xFFFFF8E1),
                      border: Border.all(color: Color(0xFFFFC107)),
                      borderRadius: BorderRadius.circular(8.r),
                    ),
                    child: Text(
                      "Complete all lessons, tests and review to unlock your certificate",
                      style: TextStyle(color: Color(0xFFFF9800), fontSize: 12.sp, fontWeight: FontWeight.w500, fontFamily: Constant.manrope),
                      textAlign: TextAlign.center,
                    ),
                  ),
                
                SizedBox(height: 20.h),
                
                // Progress Bars
                _buildProgressBar("Lessons", stats.lessonStats.completedCount, stats.lessonStats.totalCount),
                SizedBox(height: 12.h),
                _buildProgressBar("Tests", stats.quizStats.completedCount, stats.quizStats.totalCount),
                SizedBox(height: 12.h),
                _buildProgressBar("Review", stats.reviewStats.completedCount >= stats.reviewStats.totalCount ? 1 : 0, 1), 

                SizedBox(height: 24.h),
                
                stats.completed 
                ? MaterialButton(
                    onPressed: () {
                       Navigator.pop(context); // Close dialog first
                       Provider.of<PurchaseCourseDetailProvider>(context, listen: false).gotoCertificateScreen(context: context, title: widget.courseName);
                    },
                    color: Color(0xFF43A047), // Green
                    minWidth: double.infinity,
                    height: 45.h,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8.r)),
                    child: Text("Download Certificate", style: TextStyle(color: CommonColor.white, fontSize: 16.sp, fontWeight: FontWeight.w600, fontFamily: Constant.manrope)),
                  )
                : MaterialButton(
                    onPressed: () => Navigator.pop(context),
                    color: Color(0xFFFF9800), // Orange
                    minWidth: double.infinity,
                    height: 45.h,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8.r)),
                    child: Text("Continue Learning", style: TextStyle(color: CommonColor.white, fontSize: 16.sp, fontWeight: FontWeight.w600, fontFamily: Constant.manrope)),
                  ),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildProgressBar(String label, int current, int total) {
    double progress = total > 0 ? current / total : 0.0;
    if (progress > 1.0) progress = 1.0;

    return Column(
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(label, style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontWeight: FontWeight.w500, fontFamily: Constant.manrope)),
            Text("$current/$total", style: TextStyle(color: CommonColor.black, fontSize: 12.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope)),
          ],
        ),
        SizedBox(height: 6.h),
        ClipRRect(
          borderRadius: BorderRadius.circular(4.r),
          child: LinearProgressIndicator(
            value: progress,
            backgroundColor: Color(0xFFEEEEEE),
            valueColor: AlwaysStoppedAnimation<Color>(CommonColor.bg_button),
            minHeight: 8.h,
          ),
        ),
      ],
    );
  }
}
