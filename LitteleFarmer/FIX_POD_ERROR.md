# 🔧 Fix CocoaPods Firebase Version Conflict

## ❌ The Problem

CocoaPods found a version conflict:
- **Old version** in Podfile.lock: Firebase 10.25.0
- **New version** needed: Firebase 12.4.0
- **CocoaPods specs** are out-of-date

---

## ✅ Quick Fix (3 Steps)

### Step 1: Update CocoaPods Repository

```bash
pod repo update
```

This updates the CocoaPods specs repository. Takes 2-5 minutes.

### Step 2: Clean Old Pods

```bash
cd /Users/nytt/Downloads/WLF-APP-main/LitteleFarmer/ios
rm -rf Pods
rm -f Podfile.lock
```

### Step 3: Reinstall Pods

```bash
pod install --repo-update
```

This installs pods with updated repository.

---

## 🚀 Complete Commands (Copy All)

Run these commands one by one:

```bash
# 1. Go to iOS folder
cd /Users/nytt/Downloads/WLF-APP-main/LitteleFarmer/ios

# 2. Update CocoaPods repository
pod repo update

# 3. Clean old pods
rm -rf Pods
rm -f Podfile.lock

# 4. Reinstall pods with updated repo
pod install --repo-update

# 5. Go back to project root
cd ..

# 6. Build and install
flutter run
```

---

## ⏱️ Timeline

- **pod repo update**: 2-5 minutes
- **pod install**: 2-5 minutes
- **Total**: ~5-10 minutes

---

## ✅ After Pods Install Successfully

Once `pod install` completes without errors:

```bash
# Go back to project root
cd ..

# Build and install on iPhone
flutter run
```

---

## 💡 Quick Summary

**The issue:** Old Firebase version conflict  
**The fix:** Update CocoaPods repo and reinstall pods  
**Commands:**
1. `pod repo update`
2. `rm -rf Pods Podfile.lock`
3. `pod install --repo-update`
4. `flutter run`

**Just run these commands in order!** 🚀
