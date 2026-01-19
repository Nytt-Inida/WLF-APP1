import 'package:little_farmer/utils/constant.dart';

class LoginModel {
  int code;
  String message;
  int id;
  String name;
  String email;
  String country;
  String profilePhoto;
  String token;
  String schoolName;
  int age;
  String referralCode;
  bool isReferralEnabled;

  LoginModel({
    required this.code,
    required this.message,
    required this.id,
    required this.name,
    required this.email,
    required this.country,
    required this.profilePhoto,
    required this.token,
    required this.schoolName,
    required this.age,
    required this.referralCode,
    required this.isReferralEnabled,
  });

  factory LoginModel.fromJson(Map<String, dynamic> json) {
    return LoginModel(
      code: json[Constant.code],
      message: json[Constant.message],
      id: json[Constant.id],
      name: json[Constant.name],
      email: json[Constant.email],
      country: json[Constant.country],
      schoolName: json[Constant.schoolName],
      profilePhoto: json[Constant.profilePhoto],
      token: json[Constant.token],
      age: json[Constant.age],
      referralCode: json[Constant.referralCode] ?? "",
      isReferralEnabled: json['is_referral_enabled'] ?? true,
    );
  }
}
