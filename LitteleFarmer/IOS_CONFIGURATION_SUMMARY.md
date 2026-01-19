# iOS Configuration Summary

## 📋 Project Configuration Details

### Bundle Identifier
- **Current**: `littlefarmer.kids.course`
- **Location**: `ios/Runner.xcodeproj/project.pbxproj`
- **Note**: You may need to change this if it conflicts with an existing app

### iOS Deployment Target
- **Minimum iOS Version**: 12.0
- **Location**: `ios/Podfile` (line 3)
- **Compatible with**: iPhone 5s and later

### App Display Name
- **Display Name**: "Little Farmers Courses"
- **Location**: `ios/Runner/Info.plist` (CFBundleDisplayName)

### Firebase Configuration
- ✅ **GoogleService-Info.plist**: Present at `ios/Runner/GoogleService-Info.plist`
- ✅ **firebase_options.dart**: Created at `lib/firebase_options.dart`
- **Project ID**: `little-farmers-courses-78a8a`
- **Database URL**: `https://little-farmers-courses-78a8a-default-rtdb.firebaseio.com`

### Dependencies Status

#### Flutter Dependencies
- All dependencies listed in `pubspec.yaml`
- Run `flutter pub get` to install

#### iOS CocoaPods Dependencies
- Podfile configured correctly
- Run `cd ios && pod install` to install
- Dependencies will be installed automatically when building

### Signing Configuration
- **Status**: Needs to be configured in Xcode
- **Action Required**: 
  1. Open `ios/Runner.xcworkspace` in Xcode
  2. Select Runner target
  3. Go to Signing & Capabilities
  4. Enable "Automatically manage signing"
  5. Select your Apple ID team

### Required Permissions (Info.plist)
The app requests permissions for:
- Network access (HTTPS/HTTP)
- External apps (WhatsApp, Instagram, LinkedIn)
- Camera/Photos (if needed by dependencies)
- Notifications (flutter_local_notifications)

### Build Configuration Files
- ✅ `ios/Podfile` - CocoaPods configuration
- ✅ `ios/Runner/Info.plist` - App configuration
- ✅ `ios/Runner/AppDelegate.swift` - App delegate
- ✅ `ios/Runner/GoogleService-Info.plist` - Firebase config
- ✅ `ios/Flutter/Debug.xcconfig` - Debug build settings
- ✅ `ios/Flutter/Release.xcconfig` - Release build settings

### Architecture Support
- **Supported**: arm64 (all modern iPhones)
- **Simulator**: x86_64, arm64

### Orientation Support
- **iPhone**: Portrait, Landscape Left, Landscape Right
- **iPad**: All orientations

---

## ✅ Pre-Build Checklist

Before building, ensure:

1. **Mac Environment**
   - [ ] Running macOS 10.15 or later
   - [ ] Xcode installed (latest version recommended)
   - [ ] Xcode Command Line Tools installed

2. **Flutter Setup**
   - [ ] Flutter SDK installed
   - [ ] Flutter in PATH
   - [ ] `flutter doctor` shows no critical issues

3. **Dependencies**
   - [ ] CocoaPods installed (`pod --version`)
   - [ ] Flutter packages installed (`flutter pub get`)
   - [ ] iOS pods installed (`cd ios && pod install`)

4. **Firebase**
   - [ ] `GoogleService-Info.plist` in `ios/Runner/`
   - [ ] `firebase_options.dart` in `lib/`

5. **Xcode Configuration**
   - [ ] Opened `ios/Runner.xcworkspace` (NOT .xcodeproj)
   - [ ] Signing configured with your Apple ID
   - [ ] Bundle identifier set correctly

6. **iPhone Setup**
   - [ ] iPhone connected via USB
   - [ ] iPhone unlocked
   - [ ] Developer Mode enabled on iPhone
   - [ ] Computer trusted on iPhone

---

## 🔧 Configuration Files Location

```
LitteleFarmer/
├── ios/
│   ├── Podfile                    # CocoaPods dependencies
│   ├── Podfile.lock               # Locked dependency versions
│   ├── Runner/
│   │   ├── Info.plist            # App configuration
│   │   ├── AppDelegate.swift     # App entry point
│   │   ├── GoogleService-Info.plist  # Firebase config
│   │   └── Assets.xcassets/      # App icons
│   ├── Runner.xcodeproj/         # Xcode project
│   └── Runner.xcworkspace/       # Xcode workspace (USE THIS!)
├── lib/
│   ├── main.dart                 # App entry point
│   └── firebase_options.dart     # Firebase options
└── pubspec.yaml                  # Flutter dependencies
```

---

## 🚨 Common Configuration Issues

### Issue: Bundle Identifier Conflict
**Solution**: Change bundle identifier in Xcode to something unique like `com.yourname.littlefarmer`

### Issue: Signing Errors
**Solution**: 
- Ensure you're signed in to Xcode with your Apple ID
- Enable "Automatically manage signing"
- Select your team

### Issue: Pod Install Fails
**Solution**:
```bash
cd ios
rm -rf Pods Podfile.lock
pod cache clean --all
pod install
```

### Issue: Firebase Not Working
**Solution**:
- Verify `GoogleService-Info.plist` is in `ios/Runner/`
- Verify `firebase_options.dart` exists
- Check bundle identifier matches Firebase project

---

## 📱 Device Requirements

### Minimum Requirements
- **iOS Version**: 12.0 or later
- **Device**: iPhone 5s or later
- **Storage**: ~50MB for app installation

### Recommended
- **iOS Version**: 14.0 or later
- **Device**: iPhone 8 or later
- **Storage**: 100MB+ for app and data

---

## 🔐 Security Notes

1. **GoogleService-Info.plist** contains Firebase API keys
   - These are safe to include in the app
   - Firebase has security rules on the backend

2. **Bundle Identifier** should be unique
   - Prevents conflicts with other apps
   - Required for App Store submission

3. **Code Signing** is required
   - Even for development builds
   - Free Apple ID works for 7-day certificates

---

## 📝 Next Steps

1. Run setup script: `./setup_ios.sh`
2. Follow the iOS Build Guide: `IOS_BUILD_GUIDE.md`
3. Build and deploy: `./build_ios.sh` or use Xcode

For detailed instructions, see: **IOS_BUILD_GUIDE.md**
