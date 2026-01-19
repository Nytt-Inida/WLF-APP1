# Fix FFmpeg Command - Use .mp4 Extension

## ❌ The Problem

FFmpeg doesn't recognize `.new` as a video file extension. It needs a proper video format extension like `.mp4`.

## ✅ Solution: Use .mp4 Extension for Temporary File

### Corrected Command:

```bash
# Use .mp4 extension for temporary file
ffmpeg -i f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 \
    -c:v copy \
    -c:a aac \
    -b:a 128k \
    -ar 44100 \
    -ac 2 \
    -movflags +faststart \
    f47ac10b-58cc-4372-a567-0e02b2c3d479_temp.mp4

# Then replace original
mv f47ac10b-58cc-4372-a567-0e02b2c3d479_temp.mp4 f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4
```

### Alternative: Use Different Name

```bash
# Create with different name
ffmpeg -i f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 \
    -c:v copy \
    -c:a aac \
    -b:a 128k \
    -ar 44100 \
    -ac 2 \
    -movflags +faststart \
    f47ac10b-58cc-4372-a567-0e02b2c3d479_aac.mp4

# Replace original
mv f47ac10b-58cc-4372-a567-0e02b2c3d479_aac.mp4 f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4
```

---

## 📋 Complete Corrected Steps

### Step 1: Backup First (If Not Done)

```bash
cd /www/wwwroot/welittlefarmers.com/storage/app
mkdir -p videos_backup
cp videos/f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 videos_backup/
```

### Step 2: Convert with Correct Extension

```bash
cd videos

# Convert with .mp4 extension
ffmpeg -i f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 \
    -c:v copy \
    -c:a aac \
    -b:a 128k \
    -ar 44100 \
    -ac 2 \
    -movflags +faststart \
    f47ac10b-58cc-4372-a567-0e02b2c3d479_temp.mp4
```

**This should work!** You'll see progress output.

### Step 3: Verify New File

```bash
# Check if new file was created
ls -lh f47ac10b-58cc-4372-a567-0e02b2c3d479_temp.mp4

# Should show similar size to original
```

### Step 4: Replace Original

```bash
# Atomic swap
mv f47ac10b-58cc-4372-a567-0e02b2c3d479_temp.mp4 f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4

# Verify
ls -lh f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4
echo "✅ Video replaced! Test on iOS app now."
```

---

## 🎯 Quick Copy-Paste (All Steps)

```bash
# Backup
cd /www/wwwroot/welittlefarmers.com/storage/app && \
mkdir -p videos_backup && \
cp videos/f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 videos_backup/ && \
cd videos && \
ffmpeg -i f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 -c:v copy -c:a aac -b:a 128k -ar 44100 -ac 2 -movflags +faststart f47ac10b-58cc-4372-a567-0e02b2c3d479_temp.mp4 && \
mv f47ac10b-58cc-4372-a567-0e02b2c3d479_temp.mp4 f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 && \
echo "✅ Done! Test on iOS app now."
```

---

## ✅ Summary

**The Issue:** FFmpeg needs `.mp4` extension, not `.new`

**The Fix:** Use `_temp.mp4` or `_aac.mp4` instead of `.new`

**Try again with the corrected command above!** 🚀
