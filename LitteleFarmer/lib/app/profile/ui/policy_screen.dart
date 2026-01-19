import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/constant.dart';

class PolicyScreen extends StatelessWidget {
  final String policyType; // "refund" or "delivery"

  const PolicyScreen({super.key, required this.policyType});

  @override
  Widget build(BuildContext context) {
    final isRefund = policyType == "refund";
    final title = isRefund ? "Refund Policy" : "Delivery Policy";

    return Scaffold(
        backgroundColor: CommonColor.bg_main,
        extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Column(
          children: [
            Container(
              margin: EdgeInsets.all(10.h),
              child: Row(
                children: [
                  InkWell(
                    onTap: () {
                      Navigator.pop(context);
                    },
                    child: Container(
                      height: 40.h,
                      width: 40.h,
                      padding: EdgeInsets.all(10.h),
                      decoration: BoxDecoration(
                          color: CommonColor.white,
                          borderRadius: BorderRadius.circular(50.h)),
                      child: SvgPicture.asset(CommonImage.ic_back,
                          fit: BoxFit.cover,
                          colorFilter: ColorFilter.mode(
                              CommonColor.black, BlendMode.srcIn)),
                    ),
                  ),
                  Expanded(
                    child: Text(
                      title,
                      textAlign: TextAlign.center,
                      style: TextStyle(
                          color: Colors.grey[900],
                          fontSize: 18.sp,
                          fontWeight: FontWeight.w900,
                          fontFamily: Constant.manrope),
                    ),
                  ),
                  SizedBox(width: 40.h),
                ],
              ),
            ),
            Expanded(
              child: SingleChildScrollView(
                padding: EdgeInsets.all(15.w),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: isRefund ? _buildRefundContent() : _buildDeliveryContent(),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  List<Widget> _buildRefundContent() {
    return [
      Text(
        "Refund & Cancellation Policy",
        style: TextStyle(
            color: Colors.grey[900],
            fontSize: 24.sp,
            fontWeight: FontWeight.bold,
            fontFamily: Constant.manrope),
      ),
      SizedBox(height: 10.h),
      Text(
        "Last updated: October 30, 2025",
        style: TextStyle(
            color: Colors.grey[700], fontSize: 14.sp, fontFamily: Constant.manrope),
      ),
      SizedBox(height: 20.h),
      _buildSection(
        title: "Introduction",
        content:
            "At Little Farmers Academy, we value your trust and aim to deliver meaningful, high-quality learning experiences for every child. This Refund & Cancellation Policy outlines the terms under which refunds, replacements, or cancellations are accepted for purchases made on welittlefarmers.com or via our official payment channels.",
      ),
      _buildSection(
        title: "1. Payment Methods",
        content: "We currently accept:",
        items: [
          "For India: UPI, Debit/Credit Cards, Net Banking, and Wallets (via our secure Indian payment partners).",
          "For International Customers: PayPal (USD transactions only).",
        ],
        additionalText:
            "All transactions are processed securely through PCI-compliant gateways. We do not store your full payment details.",
      ),
      _buildSection(
        title: "2. Course Purchases (Digital Content)",
        items: [
          "Instant Access Courses / Digital Programs: Once access credentials, learning materials, or downloadable content have been delivered, refunds are not applicable, as these are digital products.",
          "Live or Scheduled Sessions: Cancellations made at least 72 hours before the start of a live class or workshop are eligible for a 100% refund, minus payment gateway fees.",
          "Missed Sessions: If you miss a live session, a recording or alternate batch (if available) will be provided. Refunds are not issued for non-attendance.",
        ],
      ),
      _buildSection(
        title: "3. Physical Kits and Materials",
        content:
            "For courses that include a starter kit or farm/PCB box:",
        items: [
          "If your kit arrives damaged or incomplete, please contact us within 7 days of delivery at contact@welittlefarmers.com with photos of the package.",
          "After verification, we will arrange a free replacement or full refund (excluding delivery charges, if applicable).",
          "Returns are not accepted after the kit has been used or partially assembled.",
        ],
      ),
      _buildSection(
        title: "4. Refund Timelines",
        content: "Once your refund request is approved:",
        items: [
          "UPI / Indian Bank Accounts: Within 7–10 business days.",
          "PayPal Transactions (International): Within 5–7 business days after processing through PayPal's refund system.",
          "Refunds will always be made to the original payment method used at the time of purchase.",
        ],
      ),
      _buildSection(
        title: "5. Order Cancellation",
        items: [
          "Orders for physical kits can be cancelled before dispatch by emailing support@welittlefarmers.com or messaging via our website contact form.",
          "Once dispatched, the order cannot be cancelled, but you may request a return if eligible (see Section 3).",
        ],
      ),
      _buildSection(
        title: "6. Non-Refundable Situations",
        content: "Refunds will not be granted for:",
        items: [
          "Completed or accessed digital courses.",
          "Gift cards or promotional credits.",
          "Orders where incorrect shipping details were provided by the customer.",
          "Course access suspended due to policy violations or misuse.",
        ],
      ),
      _buildSection(
        title: "7. Technical Issues or Duplicate Payments",
        content:
            "If you were charged twice or faced a failed transaction, please share the payment reference (Transaction ID or UTR) at contact@welittlefarmers.com. We will verify and issue a full refund or adjustment within 5 business days.",
      ),
      _buildSection(
        title: "8. Dispute Resolution",
        content: "For any disputes:",
        items: [
          "Indian users are covered under the Consumer Protection (E-Commerce) Rules, 2020.",
          "International users paying via PayPal may also raise a claim under PayPal Buyer Protection if applicable. We strive to resolve all refund or return requests amicably and within 10 business days of receiving the complaint.",
        ],
      ),
      _buildSection(
        title: "9. Contact Us",
        content:
            "If you have any questions about this Refund Policy, reach us at:\n📧 contact@welittlefarmers.com",
      ),
      SizedBox(height: 30.h),
    ];
  }

  List<Widget> _buildDeliveryContent() {
    return [
      Text(
        "Delivery Policy",
        style: TextStyle(
            color: Colors.grey[900],
            fontSize: 24.sp,
            fontWeight: FontWeight.bold,
            fontFamily: Constant.manrope),
      ),
      SizedBox(height: 10.h),
      Text(
        "Last updated: October 30, 2025",
        style: TextStyle(
            color: Colors.grey[700], fontSize: 14.sp, fontFamily: Constant.manrope),
      ),
      SizedBox(height: 20.h),
      _buildSection(
        title: "Introduction",
        content:
            "Welcome to Little Farmers Academy! We're excited to deliver your learning kits and give you access to our fun, educational courses. This Delivery Policy explains how we process, ship, and deliver physical kits and digital course materials when you order from welittlefarmers.com.",
      ),
      _buildSection(
        title: "1. Scope of Delivery",
        content: "We currently provide:",
        items: [
          "Physical Deliveries: Starter kits, course boxes, or printed materials shipped across India and selected international destinations.",
          "Digital Deliveries: Online course access, login credentials, and downloadable content delivered electronically to your registered email or dashboard.",
        ],
      ),
      _buildSection(
        title: "2. Order Processing Time",
        items: [
          "Orders are usually processed within 1–3 business days after successful payment confirmation.",
          "During peak seasons, workshops, or new launches, processing may take up to 5 business days.",
          "You'll receive an email or WhatsApp confirmation once your order is packed and ready for dispatch.",
        ],
      ),
      _buildSection(
        title: "3. Delivery of Physical Kits",
        subsections: [
          _buildSubsection(
            title: "a. Domestic Deliveries (India)",
            items: [
              "Shipped through trusted logistics partners such as DTDC, India Post, BlueDart, or Delhivery.",
              "Estimated delivery time: 3–7 business days depending on location.",
              "Tracking details will be shared via email or SMS once the shipment is dispatched.",
            ],
          ),
          _buildSubsection(
            title: "b. International Deliveries",
            items: [
              "Shipped via registered international courier partners (DHL, Aramex, or similar).",
              "Estimated delivery time: 10–15 business days depending on destination and customs clearance.",
              "International buyers are responsible for any customs duties, import taxes, or clearance fees applicable in their country.",
            ],
          ),
          _buildSubsection(
            title: "c. Packaging",
            items: [
              "All kits are carefully packed using eco-friendly, recyclable materials to ensure your items reach you in perfect condition.",
            ],
          ),
        ],
      ),
      _buildSection(
        title: "4. Delivery of Digital Courses",
        items: [
          "Once payment is confirmed, course access is automatically activated within 24 hours (or immediately for PayPal/UPI instant payments).",
          "You'll receive an email with login details or a unique course access link.",
          "For live classes or scheduled batches, the class start date and joining link will be shared in advance by email or WhatsApp.",
        ],
      ),
      _buildSection(
        title: "5. Delivery Delays",
        content:
            "While we strive to deliver on time, occasional delays may occur due to:",
        items: [
          "Public holidays, strikes, or courier disruptions.",
          "Remote delivery locations or adverse weather conditions.",
          "Customs inspections (for international orders).",
        ],
        additionalText:
            "In case of a delay exceeding 10 days beyond the estimated window, please contact us at contact@welittlefarmers.com — our support team will investigate and provide a status update within 48 hours.",
      ),
      _buildSection(
        title: "6. Damaged or Missing Packages",
        content: "If your kit arrives damaged, incomplete, or tampered with:",
        items: [
          "Please take clear photos of the package and send them to contact@welittlefarmers.com within 48 hours of delivery.",
          "We will verify and, if confirmed, arrange a free replacement or refund as per our Refund Policy.",
        ],
      ),
      _buildSection(
        title: "7. Undelivered Orders",
        content:
            "If the courier marks your order as \"undelivered\" due to an incorrect address, failed contact attempts, or refusal to accept the package:",
        items: [
          "We will attempt redelivery once after confirming the address with you.",
          "Additional shipping charges may apply for repeated delivery attempts.",
        ],
      ),
      _buildSection(
        title: "8. Order Tracking",
        items: [
          "Once your order is dispatched, a tracking ID and courier name will be sent to your registered email or WhatsApp.",
          "You can track your package on the courier's website using this tracking number.",
        ],
      ),
      _buildSection(
        title: "9. Shipping Charges",
        items: [
          "India: Standard shipping is free for most courses and kits unless specified.",
          "International: Shipping charges vary based on destination, weight, and customs. Exact costs will be shown at checkout before payment.",
        ],
      ),
      _buildSection(
        title: "10. Contact Us",
        content:
            "For questions about your order, delivery status, or tracking updates, please reach out to:\n📧 contact@welittlefarmers.com",
      ),
      SizedBox(height: 30.h),
    ];
  }

  Widget _buildSection({
    required String title,
    String? content,
    List<String>? items,
    List<Widget>? subsections,
    String? additionalText,
  }) {
    return Container(
      margin: EdgeInsets.only(bottom: 20.h),
      padding: EdgeInsets.all(15.w),
      decoration: BoxDecoration(
        color: CommonColor.white,
        borderRadius: BorderRadius.circular(12.r),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: Offset(0, 5),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: TextStyle(
                color: Colors.grey[900],
                fontSize: 18.sp,
                fontWeight: FontWeight.bold,
                fontFamily: Constant.manrope),
          ),
          if (content != null) ...[
            SizedBox(height: 12.h),
            Text(
              content,
              style: TextStyle(
                  color: Colors.grey[800],
                  fontSize: 14.sp,
                  height: 1.6,
                  fontWeight: FontWeight.w500,
                  fontFamily: Constant.manrope),
            ),
          ],
          if (subsections != null) ...[
            SizedBox(height: 12.h),
            ...subsections,
          ],
          if (items != null) ...[
            SizedBox(height: items.isNotEmpty ? 10.h : 0),
            ...items.map((item) => Padding(
                  padding: EdgeInsets.only(bottom: 8.h),
                  child: Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        "• ",
                        style: TextStyle(
                            color: Colors.grey[800],
                            fontSize: 16.sp,
                            fontWeight: FontWeight.bold),
                      ),
                      Expanded(
                        child: Text(
                          item,
                          style: TextStyle(
                              color: Colors.grey[800],
                              fontSize: 14.sp,
                              height: 1.6,
                              fontWeight: FontWeight.w500,
                              fontFamily: Constant.manrope),
                        ),
                      ),
                    ],
                  ),
                )),
          ],
          if (additionalText != null) ...[
            SizedBox(height: 12.h),
            Text(
              additionalText,
              style: TextStyle(
                  color: Colors.grey[800],
                  fontSize: 14.sp,
                  height: 1.6,
                  fontWeight: FontWeight.w500,
                  fontFamily: Constant.manrope),
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildSubsection({
    required String title,
    required List<String> items,
  }) {
    return Container(
      margin: EdgeInsets.only(top: 12.h),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: TextStyle(
                color: Colors.grey[900],
                fontSize: 16.sp,
                fontWeight: FontWeight.bold,
                fontFamily: Constant.manrope),
          ),
          SizedBox(height: 8.h),
          ...items.map((item) => Padding(
                padding: EdgeInsets.only(bottom: 6.h, left: 8.w),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      "• ",
                      style: TextStyle(
                          color: Colors.grey[800],
                          fontSize: 16.sp,
                          fontWeight: FontWeight.bold),
                    ),
                    Expanded(
                      child: Text(
                        item,
                        style: TextStyle(
                            color: Colors.grey[800],
                            fontSize: 14.sp,
                            height: 1.6,
                            fontWeight: FontWeight.w500,
                            fontFamily: Constant.manrope),
                      ),
                    ),
                  ],
                ),
              )),
        ],
      ),
    );
  }
}
