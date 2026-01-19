import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:flutter_svg/svg.dart';
import 'package:little_farmer/app/profile/provider/profile_provider.dart';
import 'package:little_farmer/utils/SharedPreferencesUtil.dart';
import 'package:little_farmer/utils/common_image.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/app/profile/ui/privacy_policy_screen.dart';
import 'package:little_farmer/app/purchase_course/ui/purchase_course_screen.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  bool _hasFetchedProfileData = false;

  @override
  void initState() {
    super.initState();
    // Load referral code from shared preferences and fetch profile data when screen loads
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final provider = Provider.of<ProfileProvider>(context, listen: false);
      provider.referralCode = SharedPreferencesUtil.getString(SharedPreferencesKey.referralCode);
      if (!_hasFetchedProfileData) {
        _hasFetchedProfileData = true;
        provider.fetchProfileData(); // Fetch to get referral enabled status
      }
      // notifyListeners is called inside fetchProfileData, no need to call it here
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Consumer(
        builder: (context, ProfileProvider provider, _) {
          return SingleChildScrollView(
            padding: EdgeInsets.only(bottom: 100.h), // Add bottom padding for navbar
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  decoration: BoxDecoration(
                    color: Theme.of(context).colorScheme.primary,
                    borderRadius: BorderRadius.only(bottomLeft: Radius.circular(24.r), bottomRight: Radius.circular(24.r)),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withOpacity(0.1),
                        blurRadius: 10,
                        offset: Offset(0, 5),
                      ),
                    ],
                  ),
                  child: Container(
                    margin: EdgeInsets.all(16.w),
                    child: Column(
                      children: [
                        Text(
                          CommonString.profile,
                          style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                            color: Theme.of(context).colorScheme.onPrimary,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        Container(
                          margin: EdgeInsets.symmetric(vertical: 24.h),
                          child: Row(
                            crossAxisAlignment: CrossAxisAlignment.center,
                            children: [
                              Container(
                                height: 74.h,
                                width: 74.h,
                                padding: EdgeInsets.all(2.h),
                                decoration: BoxDecoration(
                                  shape: BoxShape.circle,
                                  color: Theme.of(context).colorScheme.onPrimary,
                                ),
                                child: ClipRRect(
                                  borderRadius: BorderRadius.circular(50.h),
                                  child: Image.network(
                                    SharedPreferencesUtil.getString(SharedPreferencesKey.profilePhoto).contains("ui-avatars.com/")
                                        ? "${SharedPreferencesUtil.getString(SharedPreferencesKey.profilePhoto)}" "&size=100" // Increased resolution
                                        : SharedPreferencesUtil.getString(SharedPreferencesKey.profilePhoto),
                                    fit: BoxFit.cover,
                                    loadingBuilder: (BuildContext context, Widget child, ImageChunkEvent? loadingProgress) {
                                      if (loadingProgress == null) {
                                        return child;
                                      } else {
                                        return Container(
                                          alignment: Alignment.center,
                                          color: Theme.of(context).colorScheme.surface,
                                          child: CircularProgressIndicator(
                                            color: Theme.of(context).colorScheme.primary,
                                            value: loadingProgress.expectedTotalBytes != null
                                                ? loadingProgress.cumulativeBytesLoaded / (loadingProgress.expectedTotalBytes ?? 1)
                                                : null,
                                          ),
                                        );
                                      }
                                    },
                                    errorBuilder: (BuildContext context, Object error, StackTrace? stackTrace) {
                                      return SvgPicture.asset(CommonImage.app_logo, fit: BoxFit.cover);
                                    },
                                  ),
                                ),
                              ),
                              SizedBox(width: 16.w),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: [
                                    Text(
                                      provider.userName,
                                      maxLines: 1,
                                      overflow: TextOverflow.ellipsis,
                                      style: Theme.of(context).textTheme.titleLarge?.copyWith(
                                        color: Theme.of(context).colorScheme.onPrimary,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                    SizedBox(height: 4.h),
                                    Text(
                                      provider.email,
                                      maxLines: 1,
                                      overflow: TextOverflow.ellipsis,
                                      style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                                        color: Theme.of(context).colorScheme.onPrimary.withOpacity(0.8),
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
                SizedBox(height: 24.h),
                Container(
                  margin: EdgeInsets.symmetric(horizontal: 16.w),
                  child: Column(
                    children: [
                      // My Account Section
                      _buildSectionTitle(context, "My Account"),
                      _buildMenuItem(
                        context,
                        iconPath: CommonImage.ic_update_profile,
                        title: CommonString.update_profile,
                        onTap: () => provider.gotoUpdateProfileScreen(context: context),
                      ),
                      _buildMenuItem(
                        context,
                        iconPath: CommonImage.ic_certificate,
                        title: CommonString.my_certificate,
                        onTap: () => provider.gotoCertificateScreen(context: context),
                      ),
                      _buildMenuItem(
                        context,
                        iconPath: CommonImage.ic_course,
                        title: "My Courses", // Renamed from "Purchase Courses"
                        onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => const PurchaseCourseScreen())),
                      ),
                      
                      SizedBox(height: 24.h),
                      
                      // Support & Info Section
                      _buildSectionTitle(context, "Support & Information"),
                      _buildMenuItem(
                        context,
                        iconPath: CommonImage.ic_info,
                        title: "FAQs",
                        onTap: () => provider.gotoFaqScreen(context: context),
                      ),
                      _buildMenuItem(
                        context,
                        iconData: Icons.support_agent,
                        title: "Contact Us",
                        onTap: () => provider.gotoContactScreen(context: context),
                      ),
                      _buildMenuItem(
                        context,
                        iconData: Icons.lock_outline,
                        title: "Privacy Policy",
                        onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => const PrivacyPolicyScreen())),
                      ),
                      
                       // Referral Code - Only show if enabled
                      Consumer<ProfileProvider>(
                        builder: (context, profileProvider, _) {
                          if (profileProvider.isReferralEnabled) {
                            return _buildMenuItem(
                              context,
                              iconData: Icons.card_giftcard,
                              title: "Referral Code",
                              onTap: () => provider.gotoReferralCodeScreen(context: context),
                            );
                          }
                          return SizedBox.shrink();
                        },
                      ),
                      
                       // User Rewards Section
                      Consumer<ProfileProvider>(
                        builder: (context, profileProvider, _) {
                          if (profileProvider.userRewards.isNotEmpty) {
                            return Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                SizedBox(height: 16.h),
                                Text(
                                  "My Rewards",
                                  style: Theme.of(context).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
                                ),
                                SizedBox(height: 12.h),
                                ...profileProvider.userRewards.map((reward) {
                                  return Container(
                                    margin: EdgeInsets.only(bottom: 12.h),
                                    padding: EdgeInsets.all(16.w),
                                    decoration: BoxDecoration(
                                      color: Colors.green.shade50,
                                      borderRadius: BorderRadius.circular(12.r),
                                      border: Border.all(
                                        color: Colors.green.shade200,
                                        width: 1,
                                      ),
                                    ),
                                    child: Row(
                                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                      children: [
                                        Expanded(
                                          child: Column(
                                            crossAxisAlignment: CrossAxisAlignment.start,
                                            children: [
                                              Text(
                                                reward['code'] ?? '',
                                                style: Theme.of(context).textTheme.titleMedium?.copyWith(
                                                  fontWeight: FontWeight.bold,
                                                  color: Colors.green.shade900,
                                                ),
                                              ),
                                              SizedBox(height: 4.h),
                                              Text(
                                                reward['type'] == 'percent'
                                                    ? '${reward['value']}% Off'
                                                    : '₹${reward['value']} Off',
                                                style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                                                  color: Colors.green.shade700,
                                                  fontWeight: FontWeight.bold,
                                                ),
                                              ),
                                            ],
                                          ),
                                        ),
                                        Container(
                                          padding: EdgeInsets.symmetric(horizontal: 12.w, vertical: 6.h),
                                          decoration: BoxDecoration(
                                            color: Colors.green.shade600,
                                            borderRadius: BorderRadius.circular(20.r),
                                          ),
                                          child: Text(
                                            "Active",
                                            style: Theme.of(context).textTheme.labelSmall?.copyWith(
                                              color: Colors.white,
                                              fontWeight: FontWeight.bold,
                                            ),
                                          ),
                                        ),
                                      ],
                                    ),
                                  );
                                }).toList(),
                              ],
                            );
                          }
                          return SizedBox.shrink();
                        },
                      ),

                      SizedBox(height: 16.h),
                      Divider(color: Theme.of(context).dividerColor),
                      SizedBox(height: 16.h),

                      // More Info
                      _buildMenuItem(
                        context,
                        iconPath: CommonImage.ic_info,
                        title: CommonString.app_version,
                        trailingText: CommonString.application_version,
                        onTap: () {}, // No action
                      ),
                      
                      SizedBox(height: 8.h),
                      
                      _buildMenuItem(
                        context,
                        iconPath: CommonImage.ic_logout,
                        iconColor: Theme.of(context).colorScheme.error,
                        title: CommonString.logout,
                        textColor: Theme.of(context).colorScheme.error,
                        onTap: () async {
                          if (!provider.isLogoutApiCalling) {
                            final shouldLogout = await Utils.showLogoutConfirmationDialog(context: context);
                            if (shouldLogout == true) {
                              provider.onLogout(context: context);
                            }
                          }
                        },
                        showArrow: false,
                      ),
                       SizedBox(height: 20.h),
                    ],
                  ),
                )
              ],
            ),
          );
        },
      ),
    ));
  }

  Widget _buildSectionTitle(BuildContext context, String title) {
    return Padding(
      padding: EdgeInsets.only(bottom: 12.h, left: 4.w),
      child: Align(
        alignment: Alignment.centerLeft,
        child: Text(
          title,
          style: Theme.of(context).textTheme.titleSmall?.copyWith(
            color: Theme.of(context).colorScheme.secondary,
            fontWeight: FontWeight.bold,
            letterSpacing: 0.5,
          ),
        ),
      ),
    );
  }

  Widget _buildMenuItem(
    BuildContext context, {
    String? iconPath,
    IconData? iconData,
    required String title,
    required VoidCallback onTap,
    Color? iconColor,
    Color? textColor,
    String? trailingText,
    bool showArrow = true,
  }) {
    final effectiveIconColor = iconColor ?? Theme.of(context).colorScheme.onSurfaceVariant;
    final effectiveTextColor = textColor ?? Theme.of(context).textTheme.bodyLarge?.color;

    return Container(
      margin: EdgeInsets.only(bottom: 8.h),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(12.r),
          onTap: onTap,
          child: Padding(
            padding: EdgeInsets.symmetric(vertical: 12.h, horizontal: 8.w),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.center,
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Row(
                  children: [
                    if (iconPath != null)
                      SvgPicture.asset(
                        iconPath,
                        height: 22.h,
                        width: 22.h,
                        fit: BoxFit.cover,
                        colorFilter: ColorFilter.mode(effectiveIconColor, BlendMode.srcIn),
                      )
                    else if (iconData != null)
                      Icon(iconData, color: effectiveIconColor, size: 22.h),
                      
                    SizedBox(width: 16.w),
                    Text(
                      title,
                      style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                        color: effectiveTextColor,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ],
                ),
                if (trailingText != null)
                  Text(
                    trailingText,
                    style: Theme.of(context).textTheme.bodyMedium?.copyWith(fontWeight: FontWeight.bold),
                  )
                else if (showArrow)
                  SvgPicture.asset(
                    CommonImage.ic_arrow_right,
                    height: 16.h,
                    width: 16.h,
                    fit: BoxFit.cover,
                    colorFilter: ColorFilter.mode(Theme.of(context).colorScheme.onSurfaceVariant.withOpacity(0.5), BlendMode.srcIn),
                  ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
