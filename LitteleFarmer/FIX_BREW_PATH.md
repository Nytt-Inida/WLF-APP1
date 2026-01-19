# ✅ Homebrew Installed - Now Configure It!

## 🎉 Great! Homebrew is Installed!

But the terminal doesn't know where `brew` is yet. We need to add it to PATH.

---

## 🔧 Quick Fix (2 Steps)

### Step 1: Add Homebrew to PATH (for this terminal session)

Copy and paste this command:

```bash
eval "$(/opt/homebrew/bin/brew shellenv zsh)"
```

Press Enter.

### Step 2: Verify it works

Type this and press Enter:

```bash
brew --version
```

**If you see a version number** → Success! ✅  
**If you see "command not found"** → Try the command again

---

## 🚀 Then Install CocoaPods

Once `brew --version` works, install CocoaPods:

```bash
brew install cocoapods
```

This will take 2-5 minutes.

---

## 📋 Complete Commands (Copy All)

Run these commands one by one:

```bash
# 1. Add Homebrew to PATH
eval "$(/opt/homebrew/bin/brew shellenv zsh)"

# 2. Check if it works
brew --version

# 3. Install CocoaPods
brew install cocoapods

# 4. Check CocoaPods
pod --version
```

---

## ✅ After CocoaPods is Installed

Then continue with:

```bash
# Go to iOS folder
cd ios

# Install dependencies
pod install

# Go back
cd ..

# Build and install
flutter run
```

---

## 💡 Quick Summary

**Right now:**
1. Run: `eval "$(/opt/homebrew/bin/brew shellenv zsh)"`
2. Then: `brew install cocoapods`
3. Then: Continue with `pod install`

**Just copy and paste the first command above!** 🚀
