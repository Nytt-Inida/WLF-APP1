# Exact Server Commands - Step by Step

## 🔍 First: We Need to Know Your Server Paths

Before we start, I need to know where your videos are stored on the server.

### Find Your Video Path

Run this command on your server to find where videos are:

```bash
# Find Laravel project location
find /var/www -name "storage" -type d 2>/dev/null | head -5

# Or if you know your project name
find /home -name "storage" -type d 2>/dev/null | head -5

# Or check common locations
ls -la /var/www/html/storage/app/videos 2>/dev/null
ls -la /home/*/public_html/storage/app/videos 2>/dev/null
ls -la ~/welittlefarmers.com/storage/app/videos 2>/dev/null
```

**What to look for:**
- Path should end with: `storage/app/videos`
- Example: `/var/www/html/welittlefarmers.com/storage/app/videos`

---

## 📋 Step-by-Step Commands

### Step 1: Connect to Your Server

```bash
# SSH into your server
ssh your_username@your_server_ip
```

### Step 2: Navigate to Video Directory

**Replace `YOUR_PROJECT_PATH` with your actual path:**

```bash
# Example paths (use the one that matches your server):
cd /var/www/html/welittlefarmers.com/storage/app/videos
# OR
cd /home/username/public_html/storage/app/videos
# OR
cd ~/welittlefarmers.com/storage/app/videos

# Verify you're in the right place
pwd
ls -la *.mp4 | head -5
```

**You should see:** List of `.mp4` video files

### Step 3: Backup Videos (Safety First!)

```bash
# Create backup directory (one level up from videos)
cd ..
mkdir -p videos_backup

# Copy all videos to backup
cp videos/*.mp4 videos_backup/

# Verify backup worked
ls -la videos_backup/ | head -5
echo "Backed up: $(ls videos_backup/*.mp4 | wc -l) videos"
```

**Expected output:** Number of videos backed up

### Step 4: Check if ffmpeg is Installed

```bash
# Check if ffmpeg exists
which ffmpeg

# If not found, install it:
# Ubuntu/Debian:
sudo apt-get update
sudo apt-get install -y ffmpeg

# CentOS/RHEL:
sudo yum install -y ffmpeg

# Verify installation
ffmpeg -version
```

### Step 5: Test with ONE Video First

```bash
# Go back to videos directory
cd videos

# List videos to pick one for testing
ls -la *.mp4 | head -5

# Pick a test video (replace 'test_video.mp4' with actual filename)
TEST_VIDEO="your_test_video.mp4"

# Create AAC version (this takes 1-2 minutes)
ffmpeg -i "$TEST_VIDEO" \
    -c:v copy \
    -c:a aac \
    -b:a 128k \
    -ar 44100 \
    -ac 2 \
    -movflags +faststart \
    "${TEST_VIDEO}.new"

# Check if new file was created
ls -lh "${TEST_VIDEO}.new"

# Verify it's not empty
du -h "${TEST_VIDEO}.new"
```

**Expected:** New file should be similar size to original

### Step 6: Atomic Swap (Replace Original)

```bash
# Replace original with new version (instant swap)
mv "${TEST_VIDEO}.new" "$TEST_VIDEO"

# Verify replacement
ls -lh "$TEST_VIDEO"
```

**Expected:** File should now be AAC version

### Step 7: Test on All Platforms

**Now test the video:**
1. **Android app** - Play video, verify it works
2. **Website** - Play video, verify it works  
3. **iOS app** - Play video, verify **audio works** 🎉

### Step 8: If Test Successful, Continue Gradually

```bash
# Process 5 videos at a time (replace with actual filenames)
for video in video1.mp4 video2.mp4 video3.mp4 video4.mp4 video5.mp4; do
    echo "Processing: $video"
    
    # Create AAC version
    ffmpeg -i "$video" \
        -c:v copy \
        -c:a aac \
        -b:a 128k \
        -ar 44100 \
        -ac 2 \
        -movflags +faststart \
        "${video}.new"
    
    # Atomic swap
    mv "${video}.new" "$video"
    
    echo "✅ Done: $video"
done
```

---

## 🚀 Complete Script (Copy-Paste Ready)

**After you provide the video path, I'll give you a complete script.**

For now, here's a template:

```bash
#!/bin/bash

# ============================================
# Video Replacement Script for iOS Audio Fix
# ============================================

# STEP 1: SET YOUR VIDEO PATH HERE
VIDEO_DIR="/path/to/your/storage/app/videos"
BACKUP_DIR="/path/to/your/storage/app/videos_backup"

# STEP 2: Navigate to video directory
cd "$VIDEO_DIR" || exit 1

# STEP 3: Create backup
echo "Creating backup..."
mkdir -p "$BACKUP_DIR"
cp *.mp4 "$BACKUP_DIR/" 2>/dev/null
echo "✅ Backup created: $(ls "$BACKUP_DIR"/*.mp4 2>/dev/null | wc -l) videos"

# STEP 4: Test with first video
FIRST_VIDEO=$(ls *.mp4 | head -1)
echo "Testing with: $FIRST_VIDEO"

ffmpeg -i "$FIRST_VIDEO" \
    -c:v copy \
    -c:a aac \
    -b:a 128k \
    -ar 44100 \
    -ac 2 \
    -movflags +faststart \
    "${FIRST_VIDEO}.new"

# STEP 5: Atomic swap
mv "${FIRST_VIDEO}.new" "$FIRST_VIDEO"
echo "✅ Test video replaced: $FIRST_VIDEO"

echo ""
echo "=========================================="
echo "Next steps:"
echo "1. Test video on Android, Web, and iOS"
echo "2. If all good, continue with remaining videos"
echo "=========================================="
```

---

## 📝 What I Need From You

Please provide:

1. **Video storage path:**
   ```bash
   # Run this on your server and tell me the output:
   find /var/www -name "videos" -type d 2>/dev/null | grep storage
   # OR
   find /home -name "videos" -type d 2>/dev/null | grep storage
   ```

2. **Server OS:**
   ```bash
   # Run this:
   cat /etc/os-release
   ```

3. **Number of videos:**
   ```bash
   # Run this (after finding video path):
   ls -1 /path/to/videos/*.mp4 | wc -l
   ```

4. **Do you have SSH access?** (Yes/No)

---

## ✅ Quick Start (If You Know the Path)

If you already know your video path, here's the quick version:

```bash
# 1. Navigate to videos
cd /YOUR/PATH/TO/storage/app/videos

# 2. Backup
cd ..
mkdir -p videos_backup
cp videos/*.mp4 videos_backup/

# 3. Test one video
cd videos
ffmpeg -i "first_video.mp4" -c:v copy -c:a aac -b:a 128k -ar 44100 -ac 2 -movflags +faststart "first_video.mp4.new"
mv "first_video.mp4.new" "first_video.mp4"

# 4. Test on all platforms
# 5. If good, continue with rest
```

---

## 🆘 Troubleshooting

### Error: "ffmpeg: command not found"
```bash
# Install ffmpeg
sudo apt-get update && sudo apt-get install -y ffmpeg
```

### Error: "No such file or directory"
```bash
# Find correct path
find /var/www -name "videos" -type d
find /home -name "videos" -type d
```

### Error: "Permission denied"
```bash
# Check permissions
ls -la /path/to/videos
# Fix if needed:
sudo chown -R your_username:your_username /path/to/videos
```

---

## 📞 Next Steps

1. **Provide the info above** (video path, OS, etc.)
2. **I'll create a custom script** for your server
3. **Run the script** step by step
4. **Test and verify**

Let me know your server details and I'll give you the exact commands! 🚀
