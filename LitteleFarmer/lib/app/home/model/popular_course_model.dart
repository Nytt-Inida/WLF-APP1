import 'package:little_farmer/utils/constant.dart';

class PopularCourseModel {
  final int code;
  final List<PopularCourse> courses;

  PopularCourseModel({
    required this.code,
    required this.courses,
  });

  factory PopularCourseModel.fromJson(Map<String, dynamic> json) {
    return PopularCourseModel(
      code: json[Constant.code],
      courses: (json[Constant.courses] as List).map((i) => PopularCourse.fromJson(i)).toList(),
    );
  }
}

class PopularCourse {
  final int id;
  final String title;
  final String ageGroup;
  final String price;
  final String? priceFormatted; // Formatted price with currency symbol (e.g., "₹2,500" or "$29.00")
  final String? currency; // Currency code (INR or USD)
  final double? priceUsd; // USD price if available
  final int numberOfClasses;
  final String image;
  int isFavorite;
  bool isPurchase;

  PopularCourse({
    required this.id,
    required this.title,
    required this.ageGroup,
    required this.price,
    this.priceFormatted,
    this.currency,
    this.priceUsd,
    required this.numberOfClasses,
    required this.image,
    required this.isFavorite,
    required this.isPurchase,
  });

  factory PopularCourse.fromJson(Map<String, dynamic> json) {
    return PopularCourse(
      id: json[Constant.id],
      title: json[Constant.title],
      ageGroup: json[Constant.ageGroup],
      price: json[Constant.price]?.toString() ?? '0',
      priceFormatted: json['price_formatted']?.toString(),
      currency: json['currency']?.toString(),
      priceUsd: json['price_usd'] != null ? (json['price_usd'] is double ? json['price_usd'] : double.tryParse(json['price_usd'].toString())) : null,
      numberOfClasses: json[Constant.numberOfClasses],
      image: json[Constant.image],
      isFavorite: json[Constant.isFavorite],
      isPurchase: json[Constant.isPurchase],
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
