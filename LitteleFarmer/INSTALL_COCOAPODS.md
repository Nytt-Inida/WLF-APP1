# 🔧 Install CocoaPods - Alternative Methods

## ❌ CocoaPods Not Installed

The `sudo gem install` method didn't work. Let's try alternative methods.

---

## ✅ Method 1: Install via Homebrew (Recommended - Easiest)

If you have Homebrew installed:

```bash
brew install cocoapods
```

**If you don't have Homebrew**, install it first:
```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

Then install CocoaPods:
```bash
brew install cocoapods
```

---

## ✅ Method 2: Install CocoaPods with User Permissions

Sometimes `sudo` doesn't work. Try installing for your user only:

```bash
# Install without sudo (user-level installation)
gem install cocoapods --user-install

# Add to PATH (add this line to your ~/.zshrc)
echo 'export PATH="$HOME/.gem/ruby/3.0.0/bin:$PATH"' >> ~/.zshrc

# Reload shell
source ~/.zshrc

# Check if it works
pod --version
```

---

## ✅ Method 3: Try sudo gem install Again (with verbose output)

Sometimes the installation fails silently. Try with verbose output:

```bash
sudo gem install cocoapods -V
```

The `-V` flag shows detailed output so you can see what's happening.

---

## ✅ Method 4: Use Bundler (if you have Ruby projects)

```bash
gem install bundler
bundle install
```

---

## 🎯 Recommended: Try Homebrew First

**Easiest method:**

1. **Check if Homebrew is installed:**
   ```bash
   brew --version
   ```

2. **If Homebrew is installed**, run:
   ```bash
   brew install cocoapods
   ```

3. **If Homebrew is NOT installed**, install it first:
   ```bash
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   ```
   Then:
   ```bash
   brew install cocoapods
   ```

---

## ✅ After CocoaPods is Installed

Once `pod --version` shows a version number:

```bash
# Go to project folder
cd /Users/nytt/Downloads/WLF-APP-main/LitteleFarmer

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

## 🔍 Check Installation

After trying any method, always check:

```bash
pod --version
```

**If you see a version number** → Success! ✅  
**If you see "command not found"** → Try next method ❌

---

## 💡 Quick Steps Right Now

**Try this first:**

```bash
# Check if Homebrew is installed
brew --version
```

**If Homebrew works:**
```bash
brew install cocoapods
```

**If Homebrew doesn't work:**
```bash
# Try user-level installation
gem install cocoapods --user-install
export PATH="$HOME/.gem/ruby/3.0.0/bin:$PATH"
pod --version
```

---

**Try the Homebrew method first - it's usually the most reliable!** 🚀
