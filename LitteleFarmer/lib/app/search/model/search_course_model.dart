import 'package:little_farmer/utils/constant.dart';

class SearchCourseModel {
  final int code;
  final List<SearchCourse> courses;

  SearchCourseModel({
    required this.code,
    required this.courses,
  });

  factory SearchCourseModel.fromJson(Map<String, dynamic> json) {
    return SearchCourseModel(
      code: json[Constant.code],
      courses: (json[Constant.courses] as List).map((i) => SearchCourse.fromJson(i)).toList(),
    );
  }
}

class SearchCourse {
  final int id;
  final int userId;
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

  SearchCourse({
    required this.id,
    required this.userId,
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

  factory SearchCourse.fromJson(Map<String, dynamic> json) {
    return SearchCourse(
      id: json[Constant.id] is int ? json[Constant.id] : int.tryParse(json[Constant.id].toString()) ?? 0,
      userId: json[Constant.userId] is int ? json[Constant.userId] : int.tryParse(json[Constant.userId].toString()) ?? 0,
      title: json[Constant.title]?.toString() ?? '',
      ageGroup: json[Constant.ageGroup]?.toString() ?? '',
      price: json[Constant.price]?.toString() ?? '0',
      priceFormatted: json['price_formatted']?.toString(),
      currency: json['currency']?.toString(),
      priceUsd: json['price_usd'] != null ? (json['price_usd'] is double ? json['price_usd'] : double.tryParse(json['price_usd'].toString())) : null,
      numberOfClasses: json[Constant.numberOfClasses] is int ? json[Constant.numberOfClasses] : int.tryParse(json[Constant.numberOfClasses].toString()) ?? 0,
      image: json[Constant.image]?.toString() ?? '',
      isFavorite: json[Constant.isFavorite] is int ? json[Constant.isFavorite] : (json[Constant.isFavorite] == true || json[Constant.isFavorite] == 1 ? 1 : 0),
      isPurchase: json[Constant.isPurchase] is bool ? json[Constant.isPurchase] : (json[Constant.isPurchase] == true || json[Constant.isPurchase] == 1 || json[Constant.isPurchase] == '1'),
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
