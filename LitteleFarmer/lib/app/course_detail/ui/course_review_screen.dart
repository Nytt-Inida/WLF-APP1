import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:little_farmer/app/course_detail/model/review_question_model.dart';
import 'package:little_farmer/app/purchase_course_detail/provider/purchase_course_detail_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';

class CourseReviewScreen extends StatefulWidget {
  final int courseId;

  const CourseReviewScreen({Key? key, required this.courseId}) : super(key: key);

  @override
  State<CourseReviewScreen> createState() => _CourseReviewScreenState();
}

class _CourseReviewScreenState extends State<CourseReviewScreen> {
  Map<int, List<int>> selectedAnswers = {}; // Changed to support multiple selections per question

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _loadQuestions();
    });
  }

  void _loadQuestions() {
    Provider.of<PurchaseCourseDetailProvider>(context, listen: false).fetchReviewQuestions(courseId: widget.courseId).then((_) {
      if (mounted) {
        final provider = Provider.of<PurchaseCourseDetailProvider>(context, listen: false);
        if (provider.reviewQuestionModel != null) {
          // Pre-fill existing answers (support multiple selections)
          for (var q in provider.reviewQuestionModel!.questions) {
            if (q.savedOptions != null && q.savedOptions!.isNotEmpty) {
              selectedAnswers[q.id] = List<int>.from(q.savedOptions!);
            } else {
              selectedAnswers[q.id] = [];
            }
          }
          setState(() {});
        }
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Course Review", style: TextStyle(color: CommonColor.black, fontSize: 16.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope)),
        backgroundColor: CommonColor.white,
        iconTheme: IconThemeData(color: CommonColor.black),
        elevation: 0,
      ),
      body: Consumer<PurchaseCourseDetailProvider>(
        builder: (context, provider, child) {
          if (provider.isReviewFetchApiCalling) {
            return Center(child: CircularProgressIndicator(color: CommonColor.bg_button));
          }

          if (provider.reviewQuestionModel == null || provider.reviewQuestionModel!.questions.isEmpty) {
            return Center(child: Text("No review questions available.", style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontFamily: Constant.manrope)));
          }

          return Column(
            children: [
              Expanded(
                child: ListView.separated(
                  padding: EdgeInsets.all(16.w),
                  itemCount: provider.reviewQuestionModel!.questions.length,
                  separatorBuilder: (c, i) => SizedBox(height: 24.h),
                  itemBuilder: (context, index) {
                    final question = provider.reviewQuestionModel!.questions[index];
                    return _buildQuestionItem(question);
                  },
                ),
              ),
              _buildSubmitButton(provider),
            ],
          );
        },
      ),
    );
  }

  Widget _buildQuestionItem(ReviewQuestion question) {
    final selectedIndices = selectedAnswers[question.id] ?? [];
    
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          question.question,
          style: TextStyle(color: CommonColor.black, fontSize: 16.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
        ),
        SizedBox(height: 12.h),
        ...List.generate(question.options.length, (index) {
          final isSelected = selectedIndices.contains(index);
          return CheckboxListTile(
            title: Text(question.options[index], style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontFamily: Constant.manrope)),
            activeColor: CommonColor.bg_button,
            contentPadding: EdgeInsets.zero,
            dense: true,
            controlAffinity: ListTileControlAffinity.leading,
            value: isSelected,
            onChanged: (checked) {
              setState(() {
                final currentList = selectedAnswers[question.id] ?? [];
                if (checked == true) {
                  // Add to selection
                  if (!currentList.contains(index)) {
                    selectedAnswers[question.id] = [...currentList, index];
                  }
                } else {
                  // Remove from selection
                  selectedAnswers[question.id] = currentList.where((i) => i != index).toList();
                }
              });
            },
          );
        }),
      ],
    );
  }

  Widget _buildSubmitButton(PurchaseCourseDetailProvider provider) {
    return Container(
      padding: EdgeInsets.all(16.w),
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: Offset(0, -5),
          ),
        ],
      ),
      child: MaterialButton(
        onPressed: provider.isReviewSubmitApiCalling ? null : _submitReview,
        color: CommonColor.bg_button,
        disabledColor: CommonColor.bg_button.withOpacity(0.5),
        minWidth: double.infinity,
        height: 50.h,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8.r)),
        child: provider.isReviewSubmitApiCalling
            ? SizedBox(
                height: 20.h,
                width: 20.h,
                child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
              )
            : Text("Submit Review", style: TextStyle(color: CommonColor.white, fontSize: 16.sp, fontWeight: FontWeight.w600, fontFamily: Constant.manrope)),
      ),
    );
  }

  void _submitReview() {
    final provider = Provider.of<PurchaseCourseDetailProvider>(context, listen: false);
    
    // Check if all questions have at least one answer selected
    bool allAnswered = true;
    for (var q in provider.reviewQuestionModel!.questions) {
      final answers = selectedAnswers[q.id] ?? [];
      if (answers.isEmpty) {
        allAnswered = false;
        break;
      }
    }
    
    if (!allAnswered) {
      Utils.showSnackbarMessage(message: "Please select at least one option for each question before submitting.");
      return;
    }

    List<Map<String, dynamic>> answersPayload = [];
    selectedAnswers.forEach((qId, optionIndices) {
      if (optionIndices.isNotEmpty) {
        answersPayload.add({
          "question_id": qId,
          "selected_options": optionIndices // Send array of selected option indices
        });
      }
    });

    provider.submitReviewAnswers(courseId: widget.courseId, answers: answersPayload).then((success) {
      if (success) {
        Utils.showSnackbarMessage(message: "Review submitted successfully!");
        Navigator.pop(context);
      }
    });
  }
}
