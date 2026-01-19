import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:little_farmer/app/course_verify_done/ui/course_verify_done_screen.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';

class PaymentProvider extends ChangeNotifier {
  bool isPaymentProcessing = false;
  bool isCheckingCoupon = false;
  String? appliedCouponCode;
  double? discountedPrice;
  String? couponMessage;
  String? appliedReferralCode; // Referral code automatically applied from signup
  bool isApplyingReferralDiscount = false;

  Future<void> processPayment({
    required BuildContext context,
    required int courseId,
    required String cardNumber,
    required String holderName,
    required String expiryDate,
    required String cvc,
  }) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isPaymentProcessing = true;
        notifyListeners();

        var response = await ApiResponse().onProcessPayment(
          courseId: courseId,
          cardNumber: cardNumber,
          holderName: holderName,
          expiryDate: expiryDate,
          cvc: cvc,
        );

        isPaymentProcessing = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          // Payment Successful
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (context) => const CourseVerifyDoneScreen()),
          );
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null) {
            // Handle validation errors (which might be an array or string)
            if (decoded[Constant.message] is Map) {
               // If it's a map of errors, just show the first one or a generic message
               String errorMsg = decoded[Constant.message].values.first[0];
               Utils.showSnackbarMessage(message: errorMsg);
            } else {
               Utils.showSnackbarMessage(message: decoded[Constant.message].toString());
            }
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
      } catch (e) {
        isPaymentProcessing = false;
        notifyListeners();
        Utils.showSnackbarMessage(message: e.toString());
      }
    } else {
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  void clearCoupon() {
    appliedCouponCode = null;
    discountedPrice = null;
    couponMessage = null;
    notifyListeners();
  }

  // Automatically apply referral discount if user signed up with referral code
  Future<void> applyReferralDiscountIfAvailable({
    required String? signupReferralCode,
    required int courseId,
    required double originalPrice,
    String? currency,
  }) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isApplyingReferralDiscount = true;
        notifyListeners();

        // Use the new getDiscountedPrice API which automatically applies referral discount
        var response = await ApiResponse().onGetDiscountedPrice(
          courseId: courseId,
          currency: currency,
        );

        isApplyingReferralDiscount = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded['has_discount'] == true) {
            appliedReferralCode = decoded['applied_referral_code'];
            discountedPrice = decoded['final_price']?.toDouble();
            couponMessage = decoded['message'] ?? 'Referral discount applied!';
            notifyListeners();
          }
        }
      } catch (e) {
        isApplyingReferralDiscount = false;
        notifyListeners();
        print("Error applying referral discount: $e");
      }
    }
  }

  // Check if coupon is a referral code (should be rejected in coupon field)
  Future<bool> checkCoupon({
    required String code,
    required int courseId,
    String? currency,
  }) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isCheckingCoupon = true;
        notifyListeners();

        var response = await ApiResponse().onCheckCoupon(
          code: code,
          courseId: courseId,
          currency: currency,
        );

        isCheckingCoupon = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded['valid'] == true) {
            // Reject referral codes in coupon field - only accept regular discount codes
            if (decoded['type'] == 'referral') {
              couponMessage = 'Referral codes are automatically applied. Please use a discount coupon code.';
              Utils.showSnackbarMessage(message: couponMessage!);
              return false;
            }
            
            // Only regular discount codes are allowed
            appliedCouponCode = code;
            // If referral discount is already applied, we need to handle which discount takes precedence
            // For now, coupon code will override referral discount
            discountedPrice = decoded['new_price']?.toDouble();
            couponMessage = decoded['message'] ?? 'Coupon applied successfully!';
            notifyListeners();
            return true;
          } else {
            couponMessage = decoded['message'] ?? 'Invalid coupon code';
            Utils.showSnackbarMessage(message: couponMessage!);
            return false;
          }
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          couponMessage = decoded['message'] ?? 'Failed to check coupon';
          Utils.showSnackbarMessage(message: couponMessage!);
          return false;
        }
      } catch (e) {
        isCheckingCoupon = false;
        notifyListeners();
        Utils.showSnackbarMessage(message: 'Error checking coupon: ${e.toString()}');
        return false;
      }
    } else {
      Utils.showSnackbarMessage(message: CommonString.no_internet);
      return false;
    }
  }

  // Verify PayPal payment on server and grant course access
  Future<bool> verifyPayPalPayment({
    required BuildContext context,
    required int courseId,
    required String orderId,
  }) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isPaymentProcessing = true;
        notifyListeners();

        var response = await ApiResponse().onVerifyPayPalPayment(
          courseId: courseId,
          orderId: orderId,
          couponCode: appliedCouponCode,
        );

        isPaymentProcessing = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded['success'] == true) {
            Utils.showSnackbarMessage(message: decoded['message'] ?? "Payment successful! Course access granted.");
            return true;
          } else {
            Utils.showSnackbarMessage(message: decoded['message'] ?? "Payment verification failed.");
            return false;
          }
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          Utils.showSnackbarMessage(message: decoded['message'] ?? CommonString.something_went_wrong);
          return false;
        }
      } catch (e) {
        isPaymentProcessing = false;
        notifyListeners();
        Utils.showSnackbarMessage(message: "Verification error: ${e.toString()}");
        return false;
      }
    } else {
      Utils.showSnackbarMessage(message: CommonString.no_internet);
      return false;
    }
  }

  Future<void> submitManualPayment({
    required BuildContext context,
    required int courseId,
  }) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isPaymentProcessing = true;
        notifyListeners();

        var response = await ApiResponse().onManualPayment(
          courseId: courseId,
        );

        isPaymentProcessing = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          // Payment Request Successful
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded['success'] == true) {
             Utils.showSnackbarMessage(message: decoded['message'] ?? "Request Submitted. Verification Pending.");
             Navigator.pop(context, true); // Return true to indicate success/refresh needed
          } else {
             Utils.showSnackbarMessage(message: decoded['message'] ?? "Something went wrong.");
          }
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          Utils.showSnackbarMessage(message: decoded['message'] ?? CommonString.something_went_wrong);
        }
      } catch (e) {
        isPaymentProcessing = false;
        notifyListeners();
        Utils.showSnackbarMessage(message: e.toString());
      }
    } else {
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }
}
