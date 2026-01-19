import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/constant.dart';

class PrivacyPolicyScreen extends StatelessWidget {
  const PrivacyPolicyScreen({super.key});

  @override
  Widget build(BuildContext context) {
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
                      "Privacy Policy",
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
                  children: [
                    Text(
                      "Privacy Policy",
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
                          color: Colors.grey[700],
                          fontSize: 14.sp,
                          fontFamily: Constant.manrope),
                    ),
                    SizedBox(height: 20.h),
                    _buildSection(
                      title: "Introduction",
                      content:
                          "Welcome to Little Farmers Academy (\"we,\" \"our,\" or \"us\"). We are committed to protecting your privacy and ensuring that your personal information is handled responsibly and transparently. This Privacy Policy explains how we collect, use, and safeguard your data when you interact with our website welittlefarmers.com, mobile app, or related services.",
                    ),
                    _buildSection(
                      title: "1. Information We Collect",
                      content: "We collect information to provide you with the best possible learning experience. The types of information we may collect include:",
                      subsections: [
                        _buildSubsection(
                          title: "a. Personal Information (Provided by You)",
                          items: [
                            "Contact Information: Your name, email address, phone number, and postal address.",
                            "Account Data: Login credentials, course enrollments, and communication preferences.",
                            "Payment Details: Billing address, transaction ID, and limited card/payment gateway details (processed securely by third-party providers).",
                          ],
                        ),
                        _buildSubsection(
                          title: "b. Automatically Collected Information",
                          items: [
                            "Usage Data: Your IP address, browser type, device model, operating system, pages visited, and session duration.",
                            "Cookies and Similar Technologies: We use cookies to improve website functionality, remember your preferences, and measure engagement.",
                          ],
                        ),
                        _buildSubsection(
                          title: "c. Child Information",
                          items: [
                            "Little Farmers Academy is designed for children aged 5 to 15, and we collect limited information only with verified parental consent.",
                          ],
                        ),
                      ],
                    ),
                    _buildSection(
                      title: "2. How We Use Your Information",
                      content: "We use your information to:",
                      items: [
                        "Provide access to online courses, lessons, and community features.",
                        "Process payments and deliver physical materials (kits or packages, where applicable).",
                        "Personalize learning recommendations and track progress.",
                        "Communicate updates, new courses, and special offers.",
                        "Improve our products, website performance, and customer experience.",
                        "Maintain data security and prevent fraudulent activity.",
                      ],
                      additionalText: "We will not sell or rent your personal data to third parties.",
                    ),
                    _buildSection(
                      title: "3. Data Sharing and Disclosure",
                      content: "We may share limited information with:",
                      items: [
                        "Service Providers: Trusted partners who help us host courses, process payments, manage analytics, and deliver products.",
                        "Legal and Compliance Authorities: Only when required by applicable law or regulatory obligation.",
                        "International Transfers: If data is transferred outside your country (e.g., between India, Sweden, or the UAE), we ensure it is protected under recognized international safeguards (e.g., Standard Contractual Clauses).",
                      ],
                    ),
                    _buildSection(
                      title: "4. Data Security",
                      content: "We implement administrative, technical, and physical safeguards to protect your data, including:",
                      items: [
                        "SSL encryption on our website and payment gateways.",
                        "Regular security reviews and access controls.",
                        "Restricted access to personal data only to authorized personnel.",
                      ],
                      additionalText: "However, please note that no online transmission is 100% secure; you share information at your own risk.",
                    ),
                    _buildSection(
                      title: "5. Data Retention",
                      content: "We retain personal data only as long as necessary to:",
                      items: [
                        "Fulfill course access, certification, and after-sales support.",
                        "Meet legal, accounting, or tax requirements. Afterward, your data will be securely deleted or anonymized.",
                      ],
                    ),
                    _buildSection(
                      title: "6. Your Rights",
                      content: "Depending on your region, you have the following rights:",
                      items: [
                        "Access and Correction: Request copies or updates of your personal information.",
                        "Erasure (\"Right to Be Forgotten\"): Ask for deletion of your account and data.",
                        "Objection: Opt out of marketing communications anytime.",
                        "Data Portability: Request export of your information in a machine-readable format.",
                      ],
                      additionalText: "To exercise any of these rights, email us at contact@welittlefarmers.com.",
                    ),
                    _buildSection(
                      title: "7. Children's Privacy",
                      content:
                          "We do not knowingly collect data from children under 16 without verifiable parental consent. If a parent or guardian believes their child has shared personal data without permission, they should contact us immediately, and we will delete the data promptly.",
                    ),
                    _buildSection(
                      title: "8. Cookies and Tracking",
                      content:
                          "You can manage cookies through your browser settings. Some cookies are essential for our site to function properly (for example, to keep you logged in during a learning session).",
                    ),
                    _buildSection(
                      title: "9. International Users",
                      content:
                          "If you are located in the European Union, United Arab Emirates, or any other region with specific data laws, your information may be processed in India or other jurisdictions where our servers are hosted. We ensure compliance with GDPR (EU), DPDPA (India 2023), and UAE Federal Decree-Law No. 45 of 2021.",
                    ),
                    _buildSection(
                      title: "10. Updates to This Policy",
                      content:
                          "We may update this Privacy Policy periodically to reflect changes in law, technology, or our operations. Updates will be posted on this page with a new \"Last Updated\" date.",
                    ),
                    _buildSection(
                      title: "11. Contact Us",
                      content:
                          "If you have questions or concerns regarding this Privacy Policy, please contact us:\ncontact@welittlefarmers.com",
                    ),
                    SizedBox(height: 30.h),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSection({
    required String title,
    required String content,
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
          if (subsections != null) ...subsections,
          if (items != null) ...[
            SizedBox(height: 10.h),
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
