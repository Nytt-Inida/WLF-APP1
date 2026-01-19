import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/quiz/model/answer_list.dart';
import 'package:little_farmer/app/quiz/provider/quiz_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:provider/provider.dart';

// ignore: must_be_immutable
class QuizScreen extends StatefulWidget {
  String title;
  int courseId;
  bool isCompleted;
  QuizScreen({super.key, required this.title, required this.courseId, required this.isCompleted});

  @override
  State<QuizScreen> createState() => _QuizScreenState();
}

class _QuizScreenState extends State<QuizScreen> {
  late QuizProvider provider;

  @override
  void initState() {
    super.initState();
    provider = Provider.of<QuizProvider>(context, listen: false);
    provider.isQuizAlreadyCompleted = widget.isCompleted;
    if (widget.isCompleted) {
      provider.fetchQuizResult(quizTitle: widget.title, courseId: widget.courseId);
    } else {
      provider.fetchQuiz(title: widget.title, courseId: widget.courseId);
    }
  }

  @override
  void dispose() {
    provider.resetProvider();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: CommonColor.bg_main,
      extendBodyBehindAppBar: false,
      bottomNavigationBar: Consumer(
        builder: (context, QuizProvider provider, _) {
          return provider.isQuizAlreadyCompleted
              ? Container(
                  margin: EdgeInsets.all(10.h),
                  child: Row(
                    children: [
                      Expanded(
                          child: Container(
                        height: 45.h,
                        width: MediaQuery.of(context).size.width,
                        margin: EdgeInsets.only(bottom: 10.h),
                        alignment: Alignment.center,
                        decoration: BoxDecoration(color: CommonColor.bg_button, borderRadius: BorderRadius.circular(10.h)),
                        child: Material(
                          color: CommonColor.transparent,
                          child: InkWell(
                            borderRadius: BorderRadius.circular(10.h),
                            splashColor: CommonColor.rippleColor,
                            onTap: () {
                              setState(() {
                                provider.resetProvider();
                                provider.fetchQuiz(title: widget.title, courseId: widget.courseId);
                              });
                            },
                            child: Container(
                              height: double.infinity,
                              width: MediaQuery.of(context).size.width,
                              alignment: Alignment.center,
                              child: Text(
                                CommonString.retry,
                                style: TextStyle(color: CommonColor.white, fontSize: 16.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                              ),
                            ),
                          ),
                        ),
                      )),
                      SizedBox(width: 10.w),
                      Expanded(
                          child: Container(
                        height: 45.h,
                        width: MediaQuery.of(context).size.width,
                        margin: EdgeInsets.only(bottom: 10.h),
                        alignment: Alignment.center,
                        decoration: BoxDecoration(color: CommonColor.bg_button, borderRadius: BorderRadius.circular(10.h)),
                        child: Material(
                          color: CommonColor.transparent,
                          child: InkWell(
                            borderRadius: BorderRadius.circular(10.h),
                            splashColor: CommonColor.rippleColor,
                            onTap: () {
                              provider.resetProvider();
                              Navigator.of(context).pop(true);
                            },
                            child: Container(
                              height: double.infinity,
                              width: MediaQuery.of(context).size.width,
                              alignment: Alignment.center,
                              child: Text(
                                CommonString.next_lecture,
                                style: TextStyle(color: CommonColor.white, fontSize: 16.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                              ),
                            ),
                          ),
                        ),
                      ))
                    ],
                  ),
                )
              : provider.isViewResult
                  ? Container(
                      margin: EdgeInsets.all(10.h),
                      child: Row(
                        children: [
                          Expanded(
                              child: Container(
                            height: 45.h,
                            width: MediaQuery.of(context).size.width,
                            margin: EdgeInsets.only(bottom: 10.h),
                            alignment: Alignment.center,
                            decoration: BoxDecoration(color: CommonColor.bg_button, borderRadius: BorderRadius.circular(10.h)),
                            child: Material(
                              color: CommonColor.transparent,
                              child: InkWell(
                                borderRadius: BorderRadius.circular(10.h),
                                splashColor: CommonColor.rippleColor,
                                onTap: () {
                                  setState(() {
                                    provider.resetProvider();
                                  });
                                },
                                child: Container(
                                  height: double.infinity,
                                  width: MediaQuery.of(context).size.width,
                                  alignment: Alignment.center,
                                  child: Text(
                                    provider.isQuizComplete ? CommonString.retry : CommonString.next,
                                    style: TextStyle(color: CommonColor.white, fontSize: 16.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                  ),
                                ),
                              ),
                            ),
                          )),
                          SizedBox(width: 10.w),
                          Expanded(
                              child: Container(
                            height: 45.h,
                            width: MediaQuery.of(context).size.width,
                            margin: EdgeInsets.only(bottom: 10.h),
                            alignment: Alignment.center,
                            decoration: BoxDecoration(color: CommonColor.bg_button, borderRadius: BorderRadius.circular(10.h)),
                            child: Material(
                              color: CommonColor.transparent,
                              child: InkWell(
                                borderRadius: BorderRadius.circular(10.h),
                                splashColor: CommonColor.rippleColor,
                                onTap: () {
                                  provider.resetProvider();
                                  Navigator.of(context).pop(true);
                                },
                                child: Container(
                                  height: double.infinity,
                                  width: MediaQuery.of(context).size.width,
                                  alignment: Alignment.center,
                                  child: Text(
                                    CommonString.next_lecture,
                                    style: TextStyle(color: CommonColor.white, fontSize: 16.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                  ),
                                ),
                              ),
                            ),
                          ))
                        ],
                      ),
                    )
                  : Container(
                      margin: EdgeInsets.all(10.h),
                      child: Container(
                        height: 45.h,
                        width: MediaQuery.of(context).size.width,
                        margin: EdgeInsets.only(bottom: 10.h),
                        alignment: Alignment.center,
                        decoration: BoxDecoration(color: CommonColor.bg_button, borderRadius: BorderRadius.circular(10.h)),
                        child: Material(
                          color: CommonColor.transparent,
                          child: InkWell(
                            borderRadius: BorderRadius.circular(10.h),
                            splashColor: CommonColor.rippleColor,
                            onTap: () {
                              setState(() {
                                if (provider.questionList.isEmpty) {
                                  Navigator.of(context).pop(false);
                                } else {
                                  if (!provider.isFetchQuizApiCalling) {
                                    if (provider.isAnswerConfirmed) {
                                      // Move to next question or finish
                                      if (provider.currentQuestion == provider.questionList.length) {
                                        provider.separateOutAnswer();
                                        provider.submitQuiz();
                                      } else {
                                        double sliderValues = (100.0 / provider.questionList.length.toDouble()) * (provider.currentQuestion.toDouble());
                                        provider.sliderValue = sliderValues;
                                        provider.currentQuestion++;
                                        provider.resetQuestion();
                                      }
                                    } else {
                                      // Confirm answer
                                      if (provider.isAnswerSelected) {
                                        provider.confirmAnswer();
                                      } else {
                                        // Skip if nothing selected?
                                        provider.answerList.add(AnswerList(
                                            question: provider.questionList[provider.currentQuestion - 1].question,
                                            selectedAnswer: 0,
                                            questionId: provider.questionList[provider.currentQuestion - 1].id,
                                            answerType: CommonString.skipped));
                                        
                                        if (provider.currentQuestion == provider.questionList.length) {
                                            provider.separateOutAnswer();
                                            provider.submitQuiz();
                                        } else {
                                            double sliderValues = (100.0 / provider.questionList.length.toDouble()) * (provider.currentQuestion.toDouble());
                                            provider.sliderValue = sliderValues;
                                            provider.currentQuestion++;
                                            provider.resetQuestion();
                                        }
                                      }
                                    }
                                  }
                                }
                              });
                            },
                            child: Container(
                              height: double.infinity,
                              width: MediaQuery.of(context).size.width,
                              alignment: Alignment.center,
                              child: Row(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  Text(
                                    provider.questionList.isEmpty
                                        ? CommonString.back_to_course
                                        : provider.isQuizComplete
                                            ? CommonString.view_result
                                            : CommonString.next,
                                    style: TextStyle(color: CommonColor.white, fontSize: 18.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                  ),
                                  provider.isFetchQuizApiCalling
                                      ? Container(
                                          width: 20.h,
                                          height: 20.h,
                                          margin: EdgeInsets.only(left: 10.w),
                                          child: CircularProgressIndicator(strokeWidth: 2.0, valueColor: AlwaysStoppedAnimation<Color>(CommonColor.white)),
                                        )
                                      : SizedBox(width: 20.h, height: 20.h),
                                ],
                              ),
                            ),
                          ),
                        ),
                      ),
                    );
                  },
      ),
      body: SafeArea(
        child: Consumer(
        builder: (context, QuizProvider provider, _) {
          return Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                margin: EdgeInsets.all(10.h),
                child: Text(
                  widget.title,
                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18.sp, color: CommonColor.black, fontFamily: Constant.manrope),
                ),
              ),
              provider.isFetchQuizApiCalling
                  ? Container(alignment: Alignment.center, height: MediaQuery.of(context).size.height - 180.h, child: CircularProgressIndicator(color: CommonColor.bg_button))
                  : provider.isQuizAlreadyCompleted
                      ? Expanded(
                          child: SingleChildScrollView(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Container(
                                  color: CommonColor.grey,
                                  width: MediaQuery.of(context).size.width,
                                  padding: EdgeInsets.all(15.h),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        "${provider.correctAnswer} of ${provider.questionResultList.length} ${CommonString.correct_answer}",
                                        style: TextStyle(color: CommonColor.white, fontSize: 18.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                      ),
                                      Text(
                                        CommonString.try_again_move_other_lecture,
                                        style: TextStyle(color: CommonColor.white, fontSize: 14.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                                      )
                                    ],
                                  ),
                                ),
                                Container(
                                  margin: EdgeInsets.all(10.h),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Row(
                                        children: [
                                          Container(
                                            padding: EdgeInsets.all(10.h),
                                            decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0)),
                                            child: Column(
                                              crossAxisAlignment: CrossAxisAlignment.start,
                                              children: [
                                                SvgPicture.asset(CommonImage.ic_checkbox_checked, height: 20.h, width: 20.h, fit: BoxFit.cover),
                                                SizedBox(height: 15.h),
                                                Text(
                                                  "${provider.correctAnswer}",
                                                  style: TextStyle(color: CommonColor.black, fontSize: 18.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                                ),
                                                Text(
                                                  CommonString.correct,
                                                  style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                                                )
                                              ],
                                            ),
                                          ),
                                          SizedBox(width: 10.w),
                                          Expanded(
                                              child: Container(
                                            padding: EdgeInsets.all(10.h),
                                            decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0)),
                                            child: Column(
                                              crossAxisAlignment: CrossAxisAlignment.start,
                                              children: [
                                                SvgPicture.asset(CommonImage.ic_wrong, height: 20.h, width: 20.h, fit: BoxFit.cover),
                                                SizedBox(height: 15.h),
                                                Text(
                                                  "${provider.inCorrectAnswer}",
                                                  style: TextStyle(color: CommonColor.black, fontSize: 18.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                                ),
                                                Text(
                                                  CommonString.incorrect,
                                                  style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                                                )
                                              ],
                                            ),
                                          )),
                                          SizedBox(width: 10.w),
                                          Expanded(
                                              child: Container(
                                            padding: EdgeInsets.all(10.h),
                                            decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0)),
                                            child: Column(
                                              crossAxisAlignment: CrossAxisAlignment.start,
                                              children: [
                                                Container(
                                                  height: 20.h,
                                                  width: 20.h,
                                                  padding: EdgeInsets.all(2.h),
                                                  decoration: BoxDecoration(color: CommonColor.bg_button, borderRadius: BorderRadius.circular(15.h)),
                                                  child: SvgPicture.asset(CommonImage.ic_skip, fit: BoxFit.cover),
                                                ),
                                                SizedBox(height: 15.h),
                                                Text(
                                                  "${provider.skippedAnswer}",
                                                  style: TextStyle(color: CommonColor.black, fontSize: 18.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                                ),
                                                Text(
                                                  CommonString.skipped,
                                                  style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                                                )
                                              ],
                                            ),
                                          ))
                                        ],
                                      ),
                                      SizedBox(height: 15.h),
                                      ListView.builder(
                                        shrinkWrap: true,
                                        physics: const NeverScrollableScrollPhysics(),
                                        itemCount: provider.questionResultList.length,
                                        itemBuilder: (context, index) {
                                          return Container(
                                            margin: EdgeInsets.only(bottom: 15.h),
                                            child: Row(
                                              children: [
                                                if (provider.questionResultList[index].answerType == CommonString.correct)
                                                  SvgPicture.asset(
                                                    CommonImage.ic_checkbox_checked,
                                                    height: 20.h,
                                                    width: 20.h,
                                                    fit: BoxFit.cover,
                                                  ),
                                                if (provider.questionResultList[index].answerType == CommonString.incorrect)
                                                  SvgPicture.asset(
                                                    CommonImage.ic_wrong,
                                                    height: 20.h,
                                                    width: 20.h,
                                                    fit: BoxFit.cover,
                                                  ),
                                                if (provider.questionResultList[index].answerType == CommonString.skipped)
                                                  Container(
                                                    height: 20.h,
                                                    width: 20.h,
                                                    padding: EdgeInsets.all(2.h),
                                                    decoration: BoxDecoration(color: CommonColor.bg_button, borderRadius: BorderRadius.circular(15.h)),
                                                    child: SvgPicture.asset(CommonImage.ic_skip, fit: BoxFit.cover),
                                                  ),
                                                SizedBox(width: 10.w),
                                                Expanded(
                                                  child: Text(
                                                    provider.questionResultList[index].question,
                                                    style: TextStyle(color: CommonColor.black, fontSize: 16.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                                                  ),
                                                )
                                              ],
                                            ),
                                          );
                                        },
                                      )
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          ),
                        )
                      : provider.isViewResult
                          ? Expanded(
                              child: SingleChildScrollView(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Container(
                                      color: CommonColor.grey,
                                      width: MediaQuery.of(context).size.width,
                                      padding: EdgeInsets.all(15.h),
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            "${provider.correctAnswer} of ${provider.questionList.length} ${CommonString.correct_answer}",
                                            style: TextStyle(color: CommonColor.white, fontSize: 18.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                          ),
                                          Text(
                                            CommonString.try_again_move_other_lecture,
                                            style: TextStyle(color: CommonColor.white, fontSize: 14.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                                          )
                                        ],
                                      ),
                                    ),
                                    Container(
                                      margin: EdgeInsets.all(10.h),
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Row(
                                            children: [
                                              Container(
                                                padding: EdgeInsets.all(10.h),
                                                decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0)),
                                                child: Column(
                                                  crossAxisAlignment: CrossAxisAlignment.start,
                                                  children: [
                                                    SvgPicture.asset(CommonImage.ic_checkbox_checked, height: 20.h, width: 20.h, fit: BoxFit.cover),
                                                    SizedBox(height: 15.h),
                                                    Text(
                                                      "${provider.correctAnswer}",
                                                      style: TextStyle(color: CommonColor.black, fontSize: 18.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                                    ),
                                                    Text(
                                                      CommonString.correct,
                                                      style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                                                    )
                                                  ],
                                                ),
                                              ),
                                              SizedBox(width: 10.w),
                                              Expanded(
                                                  child: Container(
                                                padding: EdgeInsets.all(10.h),
                                                decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0)),
                                                child: Column(
                                                  crossAxisAlignment: CrossAxisAlignment.start,
                                                  children: [
                                                    SvgPicture.asset(CommonImage.ic_wrong, height: 20.h, width: 20.h, fit: BoxFit.cover),
                                                    SizedBox(height: 15.h),
                                                    Text(
                                                      "${provider.inCorrectAnswer}",
                                                      style: TextStyle(color: CommonColor.black, fontSize: 18.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                                    ),
                                                    Text(
                                                      CommonString.incorrect,
                                                      style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                                                    )
                                                  ],
                                                ),
                                              )),
                                              SizedBox(width: 10.w),
                                              Expanded(
                                                  child: Container(
                                                padding: EdgeInsets.all(10.h),
                                                decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0)),
                                                child: Column(
                                                  crossAxisAlignment: CrossAxisAlignment.start,
                                                  children: [
                                                    Container(
                                                      height: 20.h,
                                                      width: 20.h,
                                                      padding: EdgeInsets.all(2.h),
                                                      decoration: BoxDecoration(color: CommonColor.bg_button, borderRadius: BorderRadius.circular(15.h)),
                                                      child: SvgPicture.asset(CommonImage.ic_skip, fit: BoxFit.cover),
                                                    ),
                                                    SizedBox(height: 15.h),
                                                    Text(
                                                      "${provider.skippedAnswer}",
                                                      style: TextStyle(color: CommonColor.black, fontSize: 18.sp, fontWeight: FontWeight.bold, fontFamily: Constant.manrope),
                                                    ),
                                                    Text(
                                                      CommonString.skipped,
                                                      style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                                                    )
                                                  ],
                                                ),
                                              ))
                                            ],
                                          ),
                                          SizedBox(height: 15.h),
                                          ListView.builder(
                                            shrinkWrap: true,
                                            physics: const NeverScrollableScrollPhysics(),
                                            itemCount: provider.answerList.length,
                                            itemBuilder: (context, index) {
                                              return Container(
                                                margin: EdgeInsets.only(bottom: 15.h),
                                                child: Row(
                                                  children: [
                                                    if (provider.answerList[index].answerType == CommonString.correct)
                                                      SvgPicture.asset(
                                                        CommonImage.ic_checkbox_checked,
                                                        height: 20.h,
                                                        width: 20.h,
                                                        fit: BoxFit.cover,
                                                      ),
                                                    if (provider.answerList[index].answerType == CommonString.incorrect)
                                                      SvgPicture.asset(
                                                        CommonImage.ic_wrong,
                                                        height: 20.h,
                                                        width: 20.h,
                                                        fit: BoxFit.cover,
                                                      ),
                                                    if (provider.answerList[index].answerType == CommonString.skipped)
                                                      Container(
                                                        height: 20.h,
                                                        width: 20.h,
                                                        padding: EdgeInsets.all(2.h),
                                                        decoration: BoxDecoration(color: CommonColor.bg_button, borderRadius: BorderRadius.circular(15.h)),
                                                        child: SvgPicture.asset(CommonImage.ic_skip, fit: BoxFit.cover),
                                                      ),
                                                    SizedBox(width: 10.w),
                                                    Expanded(
                                                      child: Text(
                                                        provider.answerList[index].question,
                                                        style: TextStyle(color: CommonColor.black, fontSize: 16.sp, fontWeight: FontWeight.normal, fontFamily: Constant.manrope),
                                                      ),
                                                    )
                                                  ],
                                                ),
                                              );
                                            },
                                          )
                                        ],
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            )
                          : Container(
                              margin: EdgeInsets.all(10.h),
                              child: provider.questionList.isEmpty
                                  ? Container(
                                      alignment: Alignment.center,
                                      height: MediaQuery.of(context).size.height - 180.h,
                                      child: Text(
                                        CommonString.no_course_found,
                                        style: TextStyle(fontSize: 18.sp, fontWeight: FontWeight.bold, color: CommonColor.black, fontFamily: Constant.manrope),
                                      ),
                                    )
                                  : Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Row(
                                          children: [
                                            Expanded(
                                              child: SliderTheme(
                                                data: SliderTheme.of(context).copyWith(
                                                  activeTrackColor: CommonColor.bg_button,
                                                  inactiveTrackColor: CommonColor.grey,
                                                  trackHeight: 8.h,
                                                  thumbShape: NoThumbShape(),
                                                  overlayShape: RoundSliderOverlayShape(overlayRadius: 1.h),
                                                ),
                                                child: Slider(value: provider.sliderValue, min: 0, max: 100, divisions: 100, onChanged: (value) {}),
                                              ),
                                            ),
                                            SizedBox(width: 10.w),
                                            Text(
                                              "${provider.currentQuestion} of ${provider.questionList.length}",
                                              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15.sp, color: CommonColor.black, fontFamily: Constant.manrope),
                                            ),
                                          ],
                                        ),
                                        SizedBox(height: 10.h),
                                        Text(
                                          "${CommonString.question} ${provider.currentQuestion}",
                                          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18.sp, color: CommonColor.black, fontFamily: Constant.manrope),
                                        ),
                                        SizedBox(height: 20.h),
                                        Text(
                                          provider.questionList[provider.currentQuestion - 1].question,
                                          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16.sp, color: CommonColor.black, fontFamily: Constant.manrope),
                                        ),
                                        SizedBox(height: 20.h),
                                        GestureDetector(
                                          onTap: () {
                                            setState(() {
                                                if (!provider.isAnswerConfirmed) {
                                                  provider.selectOption(1);
                                                }
                                            });
                                          },
                                          child: Container(
                                            padding: EdgeInsets.all(15.h),
                                            decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0)),
                                            child: Row(
                                              children: [
                                                Container(
                                                  height: 22.h,
                                                  width: 22.h,
                                                  decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0), borderRadius: BorderRadius.circular(15.h)),
                                                  child: provider.isFirstAnswerChoose
                                                      ? SvgPicture.asset(CommonImage.ic_checkbox_checked, fit: BoxFit.cover, colorFilter: ColorFilter.mode(CommonColor.bg_button, BlendMode.srcIn))
                                                      : Container(),
                                                ),
                                                SizedBox(width: 10.w),
                                                Expanded(
                                                  child: Text(
                                                    provider.questionList[provider.currentQuestion - 1].option1,
                                                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14.sp, color: CommonColor.black, fontFamily: Constant.manrope),
                                                  ),
                                                ),
                                              ],
                                            ),
                                          ),
                                        ),
                                        SizedBox(height: 15.h),
                                        GestureDetector(
                                          onTap: () {
                                            setState(() {
                                                if (!provider.isAnswerConfirmed) {
                                                  provider.selectOption(2);
                                                }
                                            });
                                          },
                                          child: Container(
                                            padding: EdgeInsets.all(15.h),
                                            decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0)),
                                            child: Row(
                                              children: [
                                                Container(
                                                  height: 22.h,
                                                  width: 22.h,
                                                  decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0), borderRadius: BorderRadius.circular(15.h)),
                                                  child: provider.isSecondAnswerChoose
                                                      ? SvgPicture.asset(CommonImage.ic_checkbox_checked, fit: BoxFit.cover, colorFilter: ColorFilter.mode(CommonColor.bg_button, BlendMode.srcIn))
                                                      : Container(),
                                                ),
                                                SizedBox(width: 10.w),
                                                Expanded(
                                                  child: Text(
                                                    provider.questionList[provider.currentQuestion - 1].option2,
                                                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14.sp, color: CommonColor.black, fontFamily: Constant.manrope),
                                                  ),
                                                ),
                                              ],
                                            ),
                                          ),
                                        ),
                                        SizedBox(height: 15.h),
                                        GestureDetector(
                                          onTap: () {
                                            setState(() {
                                                if (!provider.isAnswerConfirmed) {
                                                  provider.selectOption(3);
                                                }
                                            });
                                          },
                                          child: Container(
                                            padding: EdgeInsets.all(15.h),
                                            decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0)),
                                            child: Row(
                                              children: [
                                                Container(
                                                  height: 22.h,
                                                  width: 22.h,
                                                  decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0), borderRadius: BorderRadius.circular(15.h)),
                                                  child: provider.isThirdAnswerChoose
                                                      ? SvgPicture.asset(CommonImage.ic_checkbox_checked, fit: BoxFit.cover, colorFilter: ColorFilter.mode(CommonColor.bg_button, BlendMode.srcIn))
                                                      : Container(),
                                                ),
                                                SizedBox(width: 10.w),
                                                Expanded(
                                                  child: Text(
                                                    provider.questionList[provider.currentQuestion - 1].option3,
                                                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14.sp, color: CommonColor.black, fontFamily: Constant.manrope),
                                                  ),
                                                ),
                                              ],
                                            ),
                                          ),
                                        ),
                                        SizedBox(height: 15.h),
                                        GestureDetector(
                                          onTap: () {
                                            setState(() {
                                                if (!provider.isAnswerConfirmed) {
                                                  provider.selectOption(4);
                                                }
                                            });
                                          },
                                          child: Container(
                                            padding: EdgeInsets.all(15.h),
                                            decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0)),
                                            child: Row(
                                              children: [
                                                Container(
                                                  height: 22.h,
                                                  width: 22.h,
                                                  decoration: BoxDecoration(border: Border.all(color: CommonColor.grey, width: 1.0), borderRadius: BorderRadius.circular(15.h)),
                                                  child: provider.isFourthAnswerChoose
                                                      ? SvgPicture.asset(CommonImage.ic_checkbox_checked, fit: BoxFit.cover, colorFilter: ColorFilter.mode(CommonColor.bg_button, BlendMode.srcIn))
                                                      : Container(),
                                                ),
                                                SizedBox(width: 10.w),
                                                Expanded(
                                                  child: Text(
                                                    provider.questionList[provider.currentQuestion - 1].option4,
                                                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14.sp, color: CommonColor.black, fontFamily: Constant.manrope),
                                                  ),
                                                ),
                                              ],
                                            ),
                                          ),
                                        ),
                                      ],
                                    ),
                            ),
            ],
          );
        },
        ),
      ),
    );
  }
}

class NoThumbShape extends SliderComponentShape {
  @override
  Size getPreferredSize(bool isEnabled, bool isDiscrete) {
    return Size.zero;
  }

  @override
  void paint(
    PaintingContext context,
    Offset center, {
    required Animation<double> activationAnimation,
    required Animation<double> enableAnimation,
    required bool isDiscrete,
    required TextPainter labelPainter,
    required RenderBox parentBox,
    required SliderThemeData sliderTheme,
    required TextDirection textDirection,
    required double value,
    required double textScaleFactor,
    required Size sizeWithOverflow,
  }) {}
}
