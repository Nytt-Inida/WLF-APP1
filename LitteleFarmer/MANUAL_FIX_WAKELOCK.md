# 🔧 Manual Fix for wakelock_plus Header File

## ✅ I Found the Problem!

The `messages.g.h` file exists but in the wrong location:
- **Actual location**: `/Users/nytt/.pub-cache/hosted/pub.dev/wakelock_plus-1.3.0/ios/wakelock_plus/Sources/wakelock_plus/include/wakelock_plus/messages.g.h`
- **Expected location**: `/Users/nytt/.pub-cache/hosted/pub.dev/wakelock_plus-1.3.0/ios/wakelock_plus/Sources/wakelock_plus/messages.g.h`

---

## ✅ Solution: Create Symlink

Run this command in your terminal:

```bash
cd /Users/nytt/.pub-cache/hosted/pub.dev/wakelock_plus-1.3.0/ios/wakelock_plus/Sources/wakelock_plus
ln -sf include/wakelock_plus/messages.g.h messages.g.h
```

This creates a symlink so the build can find the file.

---

## 🚀 Then Build Again

### Option 1: In Xcode

1. **Product → Clean Build Folder** (Cmd + Shift + K)
2. **Product → Build** (Cmd + B)
3. If successful, **Product → Run** (Cmd + R)

### Option 2: Via Flutter CLI

```bash
cd /Users/nytt/Downloads/WLF-APP-main/LitteleFarmer
flutter clean
flutter run
```

---

## ⚠️ If Symlink Gets Removed

If `flutter clean` removes the symlink, you'll need to recreate it. You can create a script:

```bash
# Create fix script
cat > /Users/nytt/Downloads/WLF-APP-main/LitteleFarmer/fix_wakelock.sh << 'EOF'
#!/bin/bash
cd /Users/nytt/.pub-cache/hosted/pub.dev/wakelock_plus-1.3.0/ios/wakelock_plus/Sources/wakelock_plus
ln -sf include/wakelock_plus/messages.g.h messages.g.h
echo "✅ Fixed wakelock_plus header file"
EOF

chmod +x /Users/nytt/Downloads/WLF-APP-main/LitteleFarmer/fix_wakelock.sh
```

Then run it before building:
```bash
./fix_wakelock.sh
flutter run
```

---

## ✅ Quick Steps

1. **Run the symlink command** (see above)
2. **Clean build in Xcode** (Cmd + Shift + K)
3. **Build** (Cmd + B)
4. **Run** (Cmd + R)

**This should fix the issue!** 🚀
