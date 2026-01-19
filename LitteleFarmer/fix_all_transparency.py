#!/usr/bin/env python3
"""
Script to fix all screens with SafeArea wrapping Scaffold
Changes them to have SafeArea inside Scaffold body for transparency
"""

import re
import os

# Files to fix
files_to_fix = [
    "lib/app/profile/ui/about_screen.dart",
    "lib/app/profile/ui/referral_code_screen.dart",
    "lib/app/live_sessions/ui/live_session_list_screen.dart",
    "lib/app/live_sessions/ui/live_session_detail_screen.dart",
    "lib/app/purchase_course/ui/purchase_course_screen.dart",
    "lib/app/favorite/ui/favorite_screen.dart",
    "lib/app/quiz/ui/quiz_screen.dart",
    "lib/app/download_certificate/ui/download_certificate_screen.dart",
    "lib/app/signup/ui/sing_up_screen.dart",
    "lib/app/purchase_login/ui/purchase_login_screen.dart",
    "lib/app/profile/ui/faq_screen.dart",
    "lib/app/update_profile/ui/update_profile_screen.dart",
    "lib/app/certificate/ui/certificate_screen.dart",
    "lib/app/profile/ui/privacy_policy_screen.dart",
    "lib/app/course_verify_done/ui/course_verify_done_screen.dart",
    "lib/app/all_courses/ui/all_courses_screen.dart",
    "lib/app/search/ui/search_screen.dart",
    "lib/app/profile/ui/contact_screen.dart",
    "lib/app/profile/ui/policy_screen.dart",
    "lib/app/payment/ui/manual_payment_screen.dart",
    "lib/app/popular_course/ui/popular_course_screen.dart",
]

def fix_file(filepath):
    """Fix a single file"""
    if not os.path.exists(filepath):
        print(f"File not found: {filepath}")
        return False
    
    with open(filepath, 'r') as f:
        content = f.read()
    
    # Pattern 1: return SafeArea(child: Scaffold(
    pattern1 = r'return\s+SafeArea\(\s*child:\s*Scaffold\('
    replacement1 = 'return Scaffold('
    
    # Pattern 2: return SafeArea(\n        child: Scaffold(
    pattern2 = r'return\s+SafeArea\(\s*\n\s*child:\s*Scaffold\('
    replacement2 = 'return Scaffold('
    
    # Pattern 3: return SafeArea(\n      child: Scaffold(
    pattern3 = r'return\s+SafeArea\(\s*\n\s+child:\s*Scaffold\('
    replacement3 = 'return Scaffold('
    
    original_content = content
    
    # Try pattern 1
    content = re.sub(pattern1, replacement1, content, flags=re.MULTILINE)
    
    # Try pattern 2
    content = re.sub(pattern2, replacement2, content, flags=re.MULTILINE)
    
    # Try pattern 3
    content = re.sub(pattern3, replacement3, content, flags=re.MULTILINE)
    
    if content == original_content:
        print(f"No changes needed: {filepath}")
        return False
    
    # Add extendBodyBehindAppBar and move body to SafeArea
    # Find Scaffold with body
    scaffold_pattern = r'(Scaffold\(\s*backgroundColor:[^,]+,\s*)body:'
    scaffold_replacement = r'\1extendBodyBehindAppBar: false,\n      body: SafeArea(\n        child:'
    
    content = re.sub(scaffold_pattern, scaffold_replacement, content, flags=re.MULTILINE)
    
    # Fix closing brackets - find the last ),) before ); and add one more )
    # This is tricky, so we'll do it manually for each file
    
    with open(filepath, 'w') as f:
        f.write(content)
    
    print(f"Fixed: {filepath}")
    return True

if __name__ == "__main__":
    print("Fixing transparency in all screens...")
    fixed_count = 0
    for filepath in files_to_fix:
        if fix_file(filepath):
            fixed_count += 1
    
    print(f"\nFixed {fixed_count} files")
    print("Note: You may need to manually fix closing brackets")
