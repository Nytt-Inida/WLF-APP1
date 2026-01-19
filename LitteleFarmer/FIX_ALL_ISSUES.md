# 🔧 Fix All Build & Launch Issues

## ❌ Issues Found

1. **Rosetta Missing** - Architecture mismatch
2. **Local Network Permission** - Flutter can't connect
3. **App Crashes** - After installation

---

## ✅ Fix 1: Install Rosetta (Required)

Run this command in terminal:

```bash
sudo softwareupdate --install-rosetta --agree-to-license
```

This will take 5-10 minutes. Enter your password when asked.

---

## ✅ Fix 2: Grant Local Network Permission

1. **Open System Settings** (Apple menu → System Settings)
2. Go to: **Privacy & Security → Local Network**
3. **Enable Terminal** (or your terminal app)
4. **Enable Xcode** if listed

---

## ✅ Fix 3: Build & Run from Xcode (Recommended)

Since Flutter CLI has issues, let's use Xcode directly:

### Step 1: Open Xcode

```bash
cd /Users/nytt/Downloads/WLF-APP-main/LitteleFarmer
open ios/Runner.xcworkspace
```

### Step 2: In Xcode

1. **Select your iPhone** from device menu (top toolbar)
2. **Product → Clean Build Folder** (Cmd + Shift + K)
3. **Wait for clean to complete**
4. **Product → Build** (Cmd + B)
5. **Watch for errors** - if build succeeds:
6. **Product → Run** (Cmd + R)

### Step 3: Trust Certificate on iPhone

**On your iPhone:**
1. **Settings → General → VPN & Device Management**
2. Tap **"Nytt TVM"** or your Apple ID
3. Tap **"Trust"**
4. Confirm by tapping **"Trust"** again
5. **Close the app completely** (swipe up, remove from app switcher)
6. **Reopen the app**

---

## 🔍 Check for Crash Errors

### In Xcode Console

1. **View → Debug Area → Show Debug Area** (Cmd + Shift + Y)
2. **Run the app** (Cmd + R)
3. **Watch the console** for error messages
4. **Share any errors** you see

### Common Crash Causes

1. **Certificate not trusted** → Trust in Settings (see above)
2. **Firebase not initialized** → Check `firebase_options.dart`
3. **Missing permissions** → Check Info.plist
4. **Network issues** → Check internet connection

---

## 🚀 Complete Fix Steps

### Step 1: Install Rosetta
```bash
sudo softwareupdate --install-rosetta --agree-to-license
```

### Step 2: Grant Local Network Permission
- System Settings → Privacy & Security → Local Network
- Enable Terminal and Xcode

### Step 3: Build in Xcode
```bash
open ios/Runner.xcworkspace
```
- Clean Build Folder (Cmd + Shift + K)
- Build (Cmd + B)
- Run (Cmd + R)

### Step 4: Trust Certificate on iPhone
- Settings → General → VPN & Device Management
- Trust your Apple ID

---

## ✅ Summary

**Issues:**
1. Rosetta missing → Install Rosetta
2. Local network permission → Grant in System Settings
3. App crashes → Trust certificate on iPhone

**Fix:**
1. Install Rosetta
2. Grant permissions
3. Build in Xcode
4. Trust certificate

**Follow the steps above!** 🚀
