# 🚀 iOS Quick Start Guide

## Fastest Way to Build and Install on iPhone

### Prerequisites (One-Time Setup)

1. **Mac Computer** ✅ (You're on macOS)
2. **Install Xcode** from Mac App Store
3. **Install Flutter**: https://flutter.dev/docs/get-started/install/macos
4. **Install CocoaPods**: `sudo gem install cocoapods`

---

## 📱 iPhone Setup (One-Time)

1. **Enable Developer Mode:**
   - Settings → Privacy & Security → Developer Mode → ON
   - Restart iPhone when prompted

2. **Connect iPhone:**
   - Connect iPhone to Mac via USB
   - Unlock iPhone
   - Tap "Trust This Computer" when prompted

---

## 🏗️ Build Steps (Every Time)

### Option 1: Automated Script (Easiest)

```bash
cd /Users/nytt/Downloads/WLF-APP-main/LitteleFarmer

# First time setup
./setup_ios.sh

# Build and install
./build_ios.sh
```

### Option 2: Manual Steps

```bash
cd /Users/nytt/Downloads/WLF-APP-main/LitteleFarmer

# 1. Get dependencies
flutter pub get
cd ios && pod install && cd ..

# 2. Open in Xcode
open ios/Runner.xcworkspace
```

**In Xcode:**
1. Select your iPhone from device menu (top toolbar)
2. Click Run button (▶️) or press `Cmd + R`
3. First time: Trust developer certificate on iPhone
   - Settings → General → VPN & Device Management
   - Tap your Apple ID → Trust

### Option 3: Flutter CLI

```bash
cd /Users/nytt/Downloads/WLF-APP-main/LitteleFarmer
flutter run
```

---

## ⚠️ First Time Only

When you first build, you'll need to:

1. **Configure Signing in Xcode:**
   - Open `ios/Runner.xcworkspace`
   - Select "Runner" target
   - Go to "Signing & Capabilities"
   - Check "Automatically manage signing"
   - Select your Apple ID team

2. **Trust Certificate on iPhone:**
   - After first install, go to:
   - Settings → General → VPN & Device Management
   - Tap your Apple ID → Trust

---

## 📚 Full Documentation

- **Complete Guide**: See `IOS_BUILD_GUIDE.md`
- **Configuration Details**: See `IOS_CONFIGURATION_SUMMARY.md`

---

## ❓ Troubleshooting

**"No devices found"**
- Unlock iPhone
- Check USB cable
- Enable Developer Mode on iPhone

**"Signing error"**
- Open Xcode → Signing & Capabilities
- Select your Apple ID team

**"Pod install failed"**
```bash
cd ios
rm -rf Pods Podfile.lock
pod install
```

---

## ✅ Success!

When it works, you'll see:
- ✅ Build succeeded in Xcode
- ✅ App appears on iPhone
- ✅ App launches automatically

🎉 **You're done!**
