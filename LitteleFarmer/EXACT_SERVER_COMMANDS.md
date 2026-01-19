# Exact Commands for Your Server

## Your Server Details:
- **Project Root:** `/www/wwwroot/welittlefarmers.com`
- **Video Path:** `/www/wwwroot/welittlefarmers.com/storage/app/videos`
- **Test Video:** `f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4`

---

## Step-by-Step Commands (Copy-Paste Ready)

### Step 1: Backup the Test Video (Safety First!)

```bash
# You're already in videos directory, so:
cd /www/wwwroot/welittlefarmers.com/storage/app

# Create backup folder
mkdir -p videos_backup

# Backup the test video
cp videos/f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 videos_backup/

# Verify backup
ls -lh videos_backup/f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4
echo "✅ Backup created!"
```

### Step 2: Check if ffmpeg is Installed

```bash
# Check if ffmpeg exists
which ffmpeg

# If not found, install it:
# For Ubuntu/Debian:
apt-get update && apt-get install -y ffmpeg

# For CentOS/RHEL:
yum install -y ffmpeg

# Verify installation
ffmpeg -version
```

### Step 3: Convert Test Video to AAC

```bash
# Go back to videos directory
cd videos

# Convert the test video (takes 1-2 minutes)
ffmpeg -i f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 \
    -c:v copy \
    -c:a aac \
    -b:a 128k \
    -ar 44100 \
    -ac 2 \
    -movflags +faststart \
    f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4.new

# Check if conversion succeeded
ls -lh f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4.new
```

**Expected:** You should see a new file with similar size to original

### Step 4: Atomic Swap (Replace Original)

```bash
# Replace original with new version (instant swap)
mv f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4.new f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4

# Verify replacement
ls -lh f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4
echo "✅ Video replaced successfully!"
```

### Step 5: Test on All Platforms

**Now test the video:**
1. **Android app** - Play video, verify it works
2. **Website** - Play video, verify it works  
3. **iOS app** - Play video, verify **audio works** 🎉

---

## Complete One-Liner (If You Want to Do It All at Once)

```bash
# Backup
cd /www/wwwroot/welittlefarmers.com/storage/app && \
mkdir -p videos_backup && \
cp videos/f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 videos_backup/ && \
cd videos && \
ffmpeg -i f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 -c:v copy -c:a aac -b:a 128k -ar 44100 -ac 2 -movflags +faststart f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4.new && \
mv f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4.new f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 && \
echo "✅ Done! Test on iOS app now."
```

---

## If Something Goes Wrong (Rollback)

```bash
# Restore from backup
cd /www/wwwroot/welittlefarmers.com/storage/app
cp videos_backup/f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 videos/
echo "✅ Restored from backup"
```

---

## Next Steps After Testing

**If test is successful:**
1. Continue with remaining videos
2. Process 5-10 videos per day
3. During low-traffic hours (2-5 AM)

**If test fails:**
1. Restore from backup
2. Check ffmpeg installation
3. Check video file permissions
4. Contact for help

---

## Quick Reference

| Command | Purpose |
|---------|---------|
| `cd /www/wwwroot/welittlefarmers.com/storage/app` | Navigate to storage |
| `mkdir -p videos_backup` | Create backup folder |
| `cp videos/f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 videos_backup/` | Backup video |
| `cd videos` | Go to videos folder |
| `ffmpeg -i f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 -c:v copy -c:a aac -b:a 128k -ar 44100 -ac 2 -movflags +faststart f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4.new` | Convert to AAC |
| `mv f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4.new f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4` | Replace original |

---

## ✅ Summary

**You're ready to go!** Just run the commands above step by step, and test the video on iOS. If audio works, you can continue with the rest of the videos! 🚀
