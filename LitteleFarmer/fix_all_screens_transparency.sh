#!/bin/bash

# Script to fix SafeArea wrapping Scaffold in all screens
# This causes black bars on iOS

echo "Fixing transparency in all screens..."

# List of files to fix (add more as needed)
FILES=(
  "lib/app/profile/ui/profile_screen.dart"
  "lib/app/profile/ui/about_screen.dart"
  "lib/app/profile/ui/referral_code_screen.dart"
  "lib/app/live_sessions/ui/live_session_list_screen.dart"
  "lib/app/live_sessions/ui/live_session_detail_screen.dart"
  "lib/app/popular_course/ui/popular_course_screen.dart"
  "lib/app/payment/ui/manual_payment_screen.dart"
  "lib/app/course_verify_done/ui/course_verify_done_screen.dart"
  "lib/app/purchase_login/ui/purchase_login_screen.dart"
  "lib/app/search/ui/search_screen.dart"
  "lib/app/update_profile/ui/update_profile_screen.dart"
  "lib/app/quiz/ui/quiz_screen.dart"
  "lib/app/all_courses/ui/all_courses_screen.dart"
  "lib/app/certificate/ui/certificate_screen.dart"
  "lib/app/profile/ui/faq_screen.dart"
  "lib/app/signup/ui/sing_up_screen.dart"
  "lib/app/purchase_course/ui/purchase_course_screen.dart"
  "lib/app/favorite/ui/favorite_screen.dart"
  "lib/app/profile/ui/policy_screen.dart"
  "lib/app/profile/ui/privacy_policy_screen.dart"
  "lib/app/download_certificate/ui/download_certificate_screen.dart"
  "lib/app/profile/ui/contact_screen.dart"
  "lib/app/splash/ui/splash_screen.dart"
  "lib/app/course_detail/ui/course_detail_screen.dart"
)

echo "Note: This script lists files that need manual fixing."
echo "Pattern to fix:"
echo "  OLD: return SafeArea(child: Scaffold("
echo "  NEW: return Scaffold( ... body: SafeArea(child:"
echo ""
echo "Files that need fixing:"
for file in "${FILES[@]}"; do
  if [ -f "$file" ]; then
    echo "  - $file"
  fi
done
