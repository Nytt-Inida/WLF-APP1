import 'package:little_farmer/utils/constant.dart';

class FavoriteCourseModel {
  int code;
  List<FavoriteCourse> favoriteCourses;

  FavoriteCourseModel({
    required this.code,
    required this.favoriteCourses,
  });

  factory FavoriteCourseModel.fromJson(Map<String, dynamic> json) {
    return FavoriteCourseModel(
      code: json[Constant.code],
      favoriteCourses: (json[Constant.courses] as List).map((i) => FavoriteCourse.fromJson(i)).toList(),
    );
  }
}

class FavoriteCourse {
  int id;
  String title;
  String ageGroup;
  String price;
  int courseId;
  int numberOfClasses;
  String image;
  bool isFavorite;
  bool isPurchase;

  FavoriteCourse({
    required this.id,
    required this.title,
    required this.ageGroup,
    required this.price,
    required this.courseId,
    required this.numberOfClasses,
    required this.image,
    required this.isFavorite,
    required this.isPurchase,
  });

  factory FavoriteCourse.fromJson(Map<String, dynamic> json) {
    return FavoriteCourse(
      id: json[Constant.id],
      title: json[Constant.title],
      ageGroup: json[Constant.ageGroup],
      price: json[Constant.price],
      courseId: json[Constant.courseId],
      numberOfClasses: json[Constant.numberOfClasses],
      image: json[Constant.image],
      isFavorite: json[Constant.isFavorite],
      isPurchase: json[Constant.isPurchase],
    );
  }
}
