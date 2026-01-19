# 📱 How to Find "Installed Apps" in Xcode

## 🎯 Where to Find It

The "Installed Apps" section is in the **Devices and Simulators** window, NOT in the main Xcode window.

---

## 🚀 Step-by-Step Instructions

### Step 1: Open Devices and Simulators Window

**Method 1: Using Menu**
1. In Xcode, click **"Window"** in the top menu bar
2. Click **"Devices and Simulators"**
   - Keyboard shortcut: **Cmd + Shift + 2**

**Method 2: Using Keyboard**
- Press **Cmd + Shift + 2**

---

### Step 2: Select Your iPhone

1. A new window will open: **"Devices and Simulators"**
2. On the **left sidebar**, you'll see:
   - **"Devices"** tab (selected by default)
   - List of devices (your iPhone should be listed)
3. **Click on your iPhone** in the list
   - It should show your iPhone name (e.g., "John's iPhone")

---

### Step 3: Find "Installed Apps" Section

After selecting your iPhone, the **right side** of the window will show:

**Top Section:**
- Device information (name, iOS version, etc.)

**Middle Section:**
- **"Installed Apps"** ← This is what you're looking for!
- Below it, you'll see a list of apps installed on your iPhone
- There's a **"+"** button (plus sign) at the bottom of the app list

---

### Step 4: Install IPA File

1. Click the **"+"** button (below the "Installed Apps" list)
2. A file picker will open
3. Navigate to: `/Users/nytt/Downloads/WLF-APP-main/LitteleFarmer/`
4. Select **`LittleFarmer_Release.ipa`**
5. Click **"Open"**
6. Xcode will code-sign and install the app on your iPhone

---

## 🔍 Visual Guide

**Xcode Menu Bar:**
```
Window → Devices and Simulators
```

**Devices and Simulators Window Layout:**
```
┌─────────────────────────────────────┐
│ Devices and Simulators              │
├──────────┬──────────────────────────┤
│ Devices │                          │
│          │  [Your iPhone]          │
│ • iPhone │  ────────────────────   │
│          │                          │
│          │  Installed Apps          │
│          │  ┌──────────────────┐   │
│          │  │ App 1             │   │
│          │  │ App 2             │   │
│          │  │ ...               │   │
│          │  └──────────────────┘   │
│          │  [+]  ← Click here!      │
│          │                          │
└──────────┴──────────────────────────┘
```

---

## 🆘 If You Don't See "Installed Apps"

**Check these:**

1. **Is your iPhone selected?**
   - Make sure you clicked on your iPhone in the left sidebar
   - Not "Simulators" tab

2. **Is your iPhone connected?**
   - Make sure iPhone is connected via USB
   - iPhone should be unlocked
   - "Trust This Computer" should be tapped

3. **Try refreshing:**
   - Disconnect and reconnect iPhone
   - Or close and reopen Devices window

---

## 🎯 Quick Method

**Fastest way:**

1. Press **Cmd + Shift + 2** (opens Devices window)
2. Click **your iPhone** (left sidebar)
3. Look for **"Installed Apps"** section (right side)
4. Click **"+"** button
5. Select your IPA file

---

**Press Cmd + Shift + 2 to open the Devices window!** 🚀
