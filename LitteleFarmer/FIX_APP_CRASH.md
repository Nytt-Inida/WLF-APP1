# 🔧 Fix App Crash on Launch

## ✅ Great News!

- ✅ **Build succeeded!**
- ✅ **App installed on iPhone!**
- ⚠️ **App crashes when opened**

This is usually a configuration or permissions issue. Let's fix it!

---

## 🔍 Common Causes & Fixes

### 1. Trust Developer Certificate (Most Common)

**On your iPhone:**
1. Go to: **Settings → General → VPN & Device Management**
2. Tap on **"Nytt TVM"** or your Apple ID
3. Tap **"Trust"**
4. Confirm by tapping **"Trust"** again
5. **Close the app completely** (swipe up and remove from app switcher)
6. **Reopen the app**

---

### 2. Check Xcode Console for Errors

**In Xcode:**
1. Keep Xcode open
2. **View → Debug Area → Show Debug Area** (Cmd + Shift + Y)
3. **Run the app again** (Cmd + R)
4. **Watch the console** for error messages
5. Share the error messages you see

---

### 3. Check iPhone Console

**On your iPhone:**
1. Connect iPhone to Mac
2. Open **Console.app** on Mac (Applications → Utilities → Console)
3. Select your iPhone from the left sidebar
4. **Filter by "Runner"** or your app name
5. **Open the app on iPhone**
6. **Watch for error messages** in Console
7. Share any errors you see

---

### 4. Common Issues & Fixes

#### Missing Firebase Configuration
- Check if `GoogleService-Info.plist` is correct
- Verify `firebase_options.dart` exists

#### Missing Permissions
- Check Info.plist for required permissions
- App might need network, camera, or storage permissions

#### Code Signing Issue
- In Xcode: **Signing & Capabilities** → Make sure signing is correct

---

## 🚀 Quick Fix Steps

### Step 1: Trust Certificate
**On iPhone:** Settings → General → VPN & Device Management → Trust

### Step 2: Check Console
**In Xcode:** View → Debug Area → Show Debug Area → Run app → Check errors

### Step 3: Share Error Messages
Tell me what errors you see in the console!

---

## 📱 What to Check

1. **Trust certificate** on iPhone (most common fix!)
2. **Xcode console** for error messages
3. **iPhone console** (via Console.app on Mac)
4. **App permissions** in Settings

---

## ✅ Most Likely Fix

**Trust the developer certificate:**
1. Settings → General → VPN & Device Management
2. Tap your Apple ID
3. Tap "Trust"
4. Close and reopen app

**This fixes 90% of crash-on-launch issues!** 🚀

---

## 🆘 If Still Crashing

Share the error messages from:
- Xcode console (bottom panel when running)
- Or iPhone console (Console.app on Mac)

This will help identify the exact issue!
