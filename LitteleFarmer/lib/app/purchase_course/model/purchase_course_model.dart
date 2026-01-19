import 'package:little_farmer/utils/constant.dart';

class PurchaseCourseModel {
  int code;
  List<PurchaseCourse> purchaseCourses;

  PurchaseCourseModel({
    required this.code,
    required this.purchaseCourses,
  });

  factory PurchaseCourseModel.fromJson(Map<String, dynamic> json) {
    return PurchaseCourseModel(
      code: json[Constant.code],
      purchaseCourses: (json[Constant.courses] as List).map((i) => PurchaseCourse.fromJson(i)).toList(),
    );
  }
}

class PurchaseCourse {
  int id;
  String title;
  String ageGroup;
  String price;
  String? priceFormatted; // Formatted price with currency symbol (e.g., "₹2,500" or "$29.00")
  String? currency; // Currency code (INR or USD)
  double? priceUsd; // USD price if available
  int courseId;
  int numberOfClasses;
  String image;
  int isFavorite;

  PurchaseCourse({
    required this.id,
    required this.title,
    required this.ageGroup,
    required this.price,
    this.priceFormatted,
    this.currency,
    this.priceUsd,
    required this.courseId,
    required this.numberOfClasses,
    required this.image,
    required this.isFavorite,
  });

  factory PurchaseCourse.fromJson(Map<String, dynamic> json) {
    return PurchaseCourse(
      id: json[Constant.id],
      title: json[Constant.title],
      ageGroup: json[Constant.ageGroup],
      price: json[Constant.price]?.toString() ?? '0',
      priceFormatted: json['price_formatted']?.toString(),
      currency: json['currency']?.toString(),
      priceUsd: json['price_usd'] != null ? (json['price_usd'] is double ? json['price_usd'] : double.tryParse(json['price_usd'].toString())) : null,
      courseId: json[Constant.courseId],
      numberOfClasses: json[Constant.numberOfClasses],
      image: json[Constant.image],
      isFavorite: json[Constant.isFavorite],
    );
  }
  
  // Get display price - use formatted price if available, otherwise format manually
  String get displayPrice {
    if (priceFormatted != null && priceFormatted!.isNotEmpty) {
      return priceFormatted!;
    }
    // Fallback: format based on currency if available
    if (currency == 'USD' && priceUsd != null) {
      return '\$${priceUsd!.toStringAsFixed(2)}';
    }
    // Default: assume INR
    final priceNum = double.tryParse(price) ?? 0;
    return '₹${priceNum.toStringAsFixed(0)}';
  }
}
