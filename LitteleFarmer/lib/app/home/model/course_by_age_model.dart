import 'package:little_farmer/utils/payment_config.dart';
import 'package:little_farmer/utils/constant.dart';

class CourseByAgeModel {
  final int code;
  final List<Course> courses;
// ... (rest of file)

  CourseByAgeModel({
    required this.code,
    required this.courses,
  });

  factory CourseByAgeModel.fromJson(Map<String, dynamic> json) {
    return CourseByAgeModel(
      code: json[Constant.code],
      courses: (json[Constant.courses] as List).map((i) => Course.fromJson(i)).toList(),
    );
  }
}

class Course {
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

  Course({
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

  factory Course.fromJson(Map<String, dynamic> json) {
    return Course(
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
    // Check Global Detected Country
    String? country = PaymentConfig.detectedCountryCode;
    bool isIndian = true; // Default to India 
    
    if (country != null && country.isNotEmpty) {
      isIndian = PaymentConfig.isIndianUser(country);
    }
    
    if (!isIndian) {
      // International User -> Show USD
      if (priceUsd != null) {
        return '\$${priceUsd!.toStringAsFixed(0)}'; // e.g. $29
      }
      return '\$29'; // Hardcoded fallback as per requirement
    }

    // Indian User -> Show INR
    if (priceFormatted != null && priceFormatted!.isNotEmpty) {
       return priceFormatted!;
    }
    final priceNum = double.tryParse(price) ?? 0;
    return '₹${priceNum.toStringAsFixed(0)}';
  }
}
