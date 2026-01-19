import 'package:little_farmer/utils/constant.dart';

class CertificateListModel {
  final String message;
  final List<CompletedCourse> completedCourses;

  CertificateListModel({
    required this.message,
    required this.completedCourses,
  });
  factory CertificateListModel.fromJson(Map<String, dynamic> json) {
    return CertificateListModel(
      message: json[Constant.message],
      completedCourses: (json[Constant.completedCourses] as List).map((i) => CompletedCourse.fromJson(i)).toList(),
    );
  }
}

class CompletedCourse {
  final int courseId;
  final String title;
  final String image;

  CompletedCourse({
    required this.courseId,
    required this.title,
    required this.image,
  });

  factory CompletedCourse.fromJson(Map<String, dynamic> json) {
    return CompletedCourse(
      courseId: json[Constant.courseId],
      title: json[Constant.title],
      image: json[Constant.image],
    );
  }
}
