import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:provider/provider.dart';
import 'package:little_farmer/app/payment/provider/payment_provider.dart';
import 'package:little_farmer/app/profile/provider/profile_provider.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/constant.dart';

class ManualPaymentScreen extends StatefulWidget {
  final int courseId;
  final String title;
  final String price;

  const ManualPaymentScreen({
    Key? key,
    required this.courseId,
    required this.title,
    required this.price,
  }) : super(key: key);

  @override
  State<ManualPaymentScreen> createState() => _ManualPaymentScreenState();
}

class _ManualPaymentScreenState extends State<ManualPaymentScreen> {
  final TextEditingController _couponController = TextEditingController();
  bool _couponFieldHasText = false;

  @override
  void initState() {
    super.initState();
    // Add listener to enable/disable apply button
    _couponController.addListener(() {
      setState(() {
        _couponFieldHasText = _couponController.text.trim().isNotEmpty;
      });
    });
    
    // Auto-apply referral discount if user signed up with referral code
    WidgetsBinding.instance.addPostFrameCallback((_) async {
      final profileProvider = Provider.of<ProfileProvider>(context, listen: false);
      final paymentProvider = Provider.of<PaymentProvider>(context, listen: false);
      
      // Fetch profile to get signup referral code
      await profileProvider.fetchProfileData();
      if (profileProvider.signupReferralCode != null && 
          profileProvider.signupReferralCode!.isNotEmpty) {
        // Apply referral discount automatically
        double originalPrice = double.tryParse(widget.price) ?? 0.0;
        await paymentProvider.applyReferralDiscountIfAvailable(
          signupReferralCode: profileProvider.signupReferralCode,
          courseId: widget.courseId,
          originalPrice: originalPrice,
        );
        // Force rebuild to show discounted price
        setState(() {});
      }
    });
  }

  @override
  void dispose() {
    _couponController.dispose();
    super.dispose();
  }

  InputDecoration _buildInputDecoration(String hint) {
    return InputDecoration(
      hintText: hint,
      hintStyle: TextStyle(color: Colors.grey, fontSize: 14.sp),
      contentPadding: EdgeInsets.symmetric(horizontal: 15.w, vertical: 15.h),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(10.h),
        borderSide: BorderSide(color: Colors.grey.shade300),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(10.h),
        borderSide: BorderSide(color: Colors.grey.shade300),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(10.h),
        borderSide: BorderSide(color: CommonColor.bg_button),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        backgroundColor: CommonColor.bg_main,
        extendBodyBehindAppBar: false,
        appBar: AppBar(
          title: Text("Complete Payment", style: TextStyle(color: CommonColor.black, fontWeight: FontWeight.bold, fontSize: 18.sp, fontFamily: Constant.manrope)),
          backgroundColor: Colors.white,
          elevation: 0,
          leading: IconButton(
            icon: Icon(Icons.arrow_back, color: Colors.black),
            onPressed: () => Navigator.pop(context),
          ),
        ),
        body: SafeArea(
          child: Consumer<PaymentProvider>(
            builder: (context, provider, child) {
            return SingleChildScrollView(
              padding: EdgeInsets.all(20.w),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  SizedBox(height: 20.h),
                  Text(
                    widget.title,
                    style: TextStyle(color: CommonColor.black, fontWeight: FontWeight.bold, fontSize: 18.sp, fontFamily: Constant.manrope),
                    textAlign: TextAlign.center,
                  ),
                  SizedBox(height: 10.h),
                  Consumer<PaymentProvider>(
                    builder: (context, paymentProvider, _) {
                      final displayPrice = paymentProvider.discountedPrice ?? double.tryParse(widget.price) ?? 0.0;
                      final originalPrice = double.tryParse(widget.price) ?? 0.0;
                      final hasDiscount = paymentProvider.discountedPrice != null && paymentProvider.discountedPrice! < originalPrice;
                      final hasReferralDiscount = paymentProvider.appliedReferralCode != null && paymentProvider.appliedReferralCode!.isNotEmpty;
                      
                      return Column(
                        crossAxisAlignment: CrossAxisAlignment.center,
                        children: [
                          if (hasDiscount) ...[
                            Text(
                              "Original Price: ₹${originalPrice.toStringAsFixed(2)}",
                              style: TextStyle(
                                fontSize: 14.sp,
                                color: Colors.grey,
                                decoration: TextDecoration.lineThrough,
                                fontFamily: Constant.manrope,
                              ),
                            ),
                            SizedBox(height: 5.h),
                          ],
                          Text(
                            "Price: ₹${displayPrice.toStringAsFixed(2)}",
                            style: TextStyle(color: CommonColor.bg_button, fontWeight: FontWeight.bold, fontSize: 24.sp, fontFamily: Constant.manrope),
                          ),
                          if (hasReferralDiscount) ...[
                            SizedBox(height: 8.h),
                            Container(
                              padding: EdgeInsets.all(8.w),
                              decoration: BoxDecoration(
                                color: Colors.blue.shade50,
                                borderRadius: BorderRadius.circular(8.r),
                                border: Border.all(color: Colors.blue.shade200),
                              ),
                              child: Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Icon(Icons.card_giftcard, color: Colors.blue, size: 16.h),
                                  SizedBox(width: 8.w),
                                  Flexible(
                                    child: Text(
                                      paymentProvider.couponMessage ?? 'Referral discount applied!',
                                      style: TextStyle(
                                        fontSize: 12.sp,
                                        color: Colors.blue.shade900,
                                        fontFamily: Constant.manrope,
                                      ),
                                      textAlign: TextAlign.center,
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ],
                          if (paymentProvider.couponMessage != null && paymentProvider.appliedCouponCode != null) ...[
                            SizedBox(height: 8.h),
                            Container(
                              padding: EdgeInsets.all(8.w),
                              decoration: BoxDecoration(
                                color: Colors.green.shade50,
                                borderRadius: BorderRadius.circular(8.r),
                                border: Border.all(color: Colors.green.shade200),
                              ),
                              child: Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Icon(Icons.check_circle, color: Colors.green, size: 16.h),
                                  SizedBox(width: 8.w),
                                  Flexible(
                                    child: Text(
                                      paymentProvider.couponMessage!,
                                      style: TextStyle(
                                        fontSize: 12.sp,
                                        color: Colors.green.shade700,
                                        fontFamily: Constant.manrope,
                                      ),
                                      textAlign: TextAlign.center,
                                    ),
                                  ),
                                  SizedBox(width: 8.w),
                                  GestureDetector(
                                    onTap: () {
                                      paymentProvider.clearCoupon();
                                      _couponController.clear();
                                    },
                                    child: Icon(Icons.close, color: Colors.green.shade700, size: 18.h),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ],
                      );
                    },
                  ),
                  SizedBox(height: 20.h),
                  // Coupon Code Section
                  Row(
                    children: [
                      Expanded(
                        child: TextField(
                          controller: _couponController,
                          decoration: _buildInputDecoration("Enter coupon code"),
                          textInputAction: TextInputAction.done,
                          onChanged: (value) {
                            setState(() {
                              _couponFieldHasText = value.trim().isNotEmpty;
                            });
                          },
                        ),
                      ),
                      SizedBox(width: 10.w),
                      Consumer<PaymentProvider>(
                        builder: (context, paymentProvider, _) {
                          return ElevatedButton(
                            onPressed: paymentProvider.isCheckingCoupon || !_couponFieldHasText
                                ? null
                                : () async {
                                    await paymentProvider.checkCoupon(
                                      code: _couponController.text.trim(),
                                      courseId: widget.courseId,
                                    );
                                  },
                            style: ElevatedButton.styleFrom(
                              backgroundColor: CommonColor.bg_button,
                              padding: EdgeInsets.symmetric(horizontal: 20.w, vertical: 15.h),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(10.h),
                              ),
                            ),
                            child: paymentProvider.isCheckingCoupon
                                ? SizedBox(
                                    width: 16.h,
                                    height: 16.h,
                                    child: CircularProgressIndicator(
                                      color: Colors.white,
                                      strokeWidth: 2,
                                    ),
                                  )
                                : Text(
                                    "Apply",
                                    style: TextStyle(
                                      color: Colors.white,
                                      fontSize: 14.sp,
                                      fontFamily: Constant.manrope,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                          );
                        },
                      ),
                    ],
                  ),
                  SizedBox(height: 30.h),
                  
                  // QR Code Section
                  Container(
                    padding: EdgeInsets.all(20.w),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(20),
                      boxShadow: [
                        BoxShadow(color: Colors.black12, blurRadius: 10, spreadRadius: 2),
                      ],
                    ),
                    child: Column(
                      children: [
                         Image.network(
                          "https://welittlefarmers.com/assets/img/payment-qr.jpg", // Use live URL 
                          height: 250.h,
                          width: 250.w,
                          fit: BoxFit.contain,
                          errorBuilder: (context, error, stackTrace) {
                            return Container(
                              height: 250.h, 
                              width: 250.w, 
                              color: Colors.grey[200],
                              child: Center(child: Text("QR Code Failed to Load\nCheck Internet", textAlign: TextAlign.center, style: TextStyle(fontFamily: Constant.manrope))),
                            );
                          },
                        ),
                        SizedBox(height: 15.h),
                        Text("Scan with any UPI App", style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontFamily: Constant.manrope)),
                      ],
                    ),
                  ),
                  
                  SizedBox(height: 40.h),
                  
                  // Instructions
                  Align(
                    alignment: Alignment.centerLeft,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text("How to Pay:", style: TextStyle(color: CommonColor.black, fontWeight: FontWeight.bold, fontSize: 16.sp, fontFamily: Constant.manrope)),
                        SizedBox(height: 10.h),
                        _instructionRow("1", "Scan the QR code above using GPay, PhonePe, or Paytm."),
                        SizedBox(height: 10.h),
                        Consumer<PaymentProvider>(
                          builder: (context, paymentProvider, _) {
                            final displayPrice = paymentProvider.discountedPrice ?? double.tryParse(widget.price) ?? 0.0;
                            return _instructionRow("2", "Pay the amount: ₹${displayPrice.toStringAsFixed(2)}");
                          },
                        ),
                        SizedBox(height: 10.h),
                        _instructionRow("3", "Come back here and click 'I Have Paid'."),
                      ],
                    ),
                  ),

                  SizedBox(height: 40.h),

                  SizedBox(
                    width: double.infinity,
                    height: 50.h,
                    child: ElevatedButton(
                      onPressed: provider.isPaymentProcessing 
                          ? null 
                          : () => provider.submitManualPayment(context: context, courseId: widget.courseId),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: CommonColor.bg_button,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                      ),
                      child: provider.isPaymentProcessing
                          ? CircularProgressIndicator(color: Colors.white)
                          : Text("I Have Paid", style: TextStyle(color: CommonColor.white, fontWeight: FontWeight.bold, fontSize: 16.sp, fontFamily: Constant.manrope)),
                    ),
                  ),
                  SizedBox(height: 20.h),
                  Text(
                    "We verify payments manually. Access will be granted within 24 hours.",
                    style: TextStyle(color: Colors.grey, fontSize: 12.sp, fontFamily: Constant.manrope),
                    textAlign: TextAlign.center,
                  ),
                ],
              ),
            );
          },
        ),
      ),
    );
  }

  Widget _instructionRow(String number, String text) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        CircleAvatar(
          radius: 12,
          backgroundColor: Colors.grey[200],
          child: Text(number, style: TextStyle(color: CommonColor.black, fontWeight: FontWeight.bold, fontSize: 12.sp, fontFamily: Constant.manrope)),
        ),
        SizedBox(width: 10.w),
        Expanded(child: Text(text, style: TextStyle(color: CommonColor.black, fontSize: 14.sp, fontFamily: Constant.manrope))),
      ],
    );
  }
}
