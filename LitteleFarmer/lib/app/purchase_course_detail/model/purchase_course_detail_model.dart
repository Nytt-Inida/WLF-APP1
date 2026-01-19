import 'package:little_farmer/utils/constant.dart';

class PurchaseCourseDetailModel {
  final String courseTitle;
  final List<PurchaseCourseDetailSection> sections;

  PurchaseCourseDetailModel({
    required this.courseTitle,
    required this.sections,
  });

  factory PurchaseCourseDetailModel.fromJson(Map<String, dynamic> json) {
    return PurchaseCourseDetailModel(
      courseTitle: json[Constant.courseTitle],
      sections: (json[Constant.sections] as List).map((i) => PurchaseCourseDetailSection.fromJson(i)).toList(),
    );
  }
}

class PurchaseCourseDetailSection {
  final String sectionTitle;
  final List<PurchaseCourseDetailItem> items;

  PurchaseCourseDetailSection({
    required this.sectionTitle,
    required this.items,
  });

  factory PurchaseCourseDetailSection.fromJson(Map<String, dynamic> json) {
    return PurchaseCourseDetailSection(
      sectionTitle: json[Constant.sectionTitle],
      items: (json[Constant.items] as List).map((i) => PurchaseCourseDetailItem.fromJson(i)).toList(),
    );
  }
}

class PurchaseCourseDetailItem {
  final int? lessonId;
  final int? articleId;
  final int? questionId;
  final String itemName;
  final String itemType;
  final String lessonDuration;
  final String lessonVideoUrl;
  bool isComplete;
  final bool isAccessible; // New field to indicate if lesson can be accessed
  final bool isVideo;
  final bool isArticle;
  final bool isQuiz;
  final String? articleUrl;

  PurchaseCourseDetailItem({
    required this.lessonId,
    required this.articleId,
    required this.questionId,
    required this.itemName,
    required this.itemType,
    required this.lessonDuration,
    required this.lessonVideoUrl,
    required this.isComplete,
    required this.isAccessible,
    required this.isVideo,
    required this.isArticle,
    required this.isQuiz,
    this.articleUrl,
  });

  factory PurchaseCourseDetailItem.fromJson(Map<String, dynamic> json) {
    return PurchaseCourseDetailItem(
      lessonId: json[Constant.lessonId],
      articleId: json[Constant.articleId],
      questionId: json[Constant.questionId],
      itemName: json[Constant.itemName],
      itemType: json[Constant.itemType],
      lessonDuration: json[Constant.lessonDuration],
      lessonVideoUrl: json[Constant.lessonVideoUrl],
      isComplete: json[Constant.isComplete] ?? false,
      isAccessible: json['isAccessible'] ?? false,
      isVideo: json[Constant.isVideo],
      isArticle: json[Constant.isArticle],
      isQuiz: json[Constant.isQuiz],
      articleUrl: json[Constant.articleUrl],
    );
  }
}
