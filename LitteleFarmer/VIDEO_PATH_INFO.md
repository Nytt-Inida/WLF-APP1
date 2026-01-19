# Video Storage Path - Found in Backend Code

## ✅ Video Path Location

Based on the backend code analysis, here's where videos are stored:

### Path Structure:

**Relative to Laravel project root:**
```
storage/app/videos/
```

**Full path on server (example):**
```
/var/www/html/welittlefarmers.com/storage/app/videos/
```
OR
```
/home/username/public_html/welittlefarmers.com/storage/app/videos/
```

---

## 📋 Code Evidence

### 1. Filesystem Configuration
**File:** `welittlefarmers.com/config/filesystems.php`

```php
'local' => [
    'driver' => 'local',
    'root' => storage_path('app'),  // Points to storage/app/
    'throw' => false,
],

'videos' => [
    'driver' => 'local',
    'root' => storage_path('app/videos'),  // Points to storage/app/videos/
    'visibility' => 'private',
],
```

### 2. Video Controller
**File:** `welittlefarmers.com/app/Http/Controllers/Api/ApiVideoStreamController.php`

```php
private function getVideoPath($lesson)
{
    // Returns: 'videos/' . $filename
    return 'videos/' . $videoUrl;
}

private function streamVideoFile($path, Request $request)
{
    // Uses Storage::disk('local') which points to storage/app/
    $fullPath = Storage::disk('local')->path($path);
    // Result: /path/to/project/storage/app/videos/filename.mp4
}
```

---

## 🎯 Exact Commands for Your Server

### Step 1: Find Your Project Root

**On your server, run:**
```bash
# Find Laravel project location
find /var/www -name "welittlefarmers.com" -type d 2>/dev/null
find /home -name "welittlefarmers.com" -type d 2>/dev/null
find /var/www -name "artisan" -type f 2>/dev/null | head -1
```

**The path should look like:**
```
/var/www/html/welittlefarmers.com
OR
/home/username/public_html/welittlefarmers.com
```

### Step 2: Navigate to Videos

```bash
# Replace YOUR_PROJECT_PATH with the path you found above
cd YOUR_PROJECT_PATH/storage/app/videos

# Verify you're in the right place
pwd
# Should show: /path/to/welittlefarmers.com/storage/app/videos

# List videos
ls -la *.mp4 | head -5
```

### Step 3: Complete Replacement Commands

```bash
# 1. Navigate to project root
cd /YOUR_PROJECT_PATH/welittlefarmers.com

# 2. Go to videos directory
cd storage/app/videos

# 3. Verify location
pwd
ls -la *.mp4 | head -3

# 4. Create backup (go up one level)
cd ..
mkdir -p videos_backup

# 5. Backup all videos
cp videos/*.mp4 videos_backup/
echo "✅ Backed up: $(ls videos_backup/*.mp4 | wc -l) videos"

# 6. Go back to videos
cd videos

# 7. Check ffmpeg
which ffmpeg || echo "Need to install: sudo apt-get install -y ffmpeg"

# 8. Test with ONE video
TEST_VIDEO=$(ls *.mp4 | head -1)
echo "Testing: $TEST_VIDEO"

ffmpeg -i "$TEST_VIDEO" \
    -c:v copy \
    -c:a aac \
    -b:a 128k \
    -ar 44100 \
    -ac 2 \
    -movflags +faststart \
    "${TEST_VIDEO}.new"

# 9. Atomic swap
mv "${TEST_VIDEO}.new" "$TEST_VIDEO"
echo "✅ Done: $TEST_VIDEO"

# 10. Test on Android, Web, iOS
# 11. If good, continue with rest
```

---

## 📝 Quick Reference

| Item | Path |
|------|------|
| **Project Root** | `/var/www/html/welittlefarmers.com` (example) |
| **Storage Root** | `{PROJECT_ROOT}/storage/app/` |
| **Videos Directory** | `{PROJECT_ROOT}/storage/app/videos/` |
| **Backup Directory** | `{PROJECT_ROOT}/storage/app/videos_backup/` |

---

## 🔍 How to Find Your Exact Path

**Run these commands on your server:**

```bash
# Method 1: Find by project name
find /var/www -name "welittlefarmers.com" -type d 2>/dev/null
find /home -name "welittlefarmers.com" -type d 2>/dev/null

# Method 2: Find by Laravel artisan file
find /var/www -name "artisan" -type f 2>/dev/null | grep welittlefarmers

# Method 3: Find videos directory directly
find /var/www -path "*/storage/app/videos" -type d 2>/dev/null
find /home -path "*/storage/app/videos" -type d 2>/dev/null

# Method 4: Check common locations
ls -la /var/www/html/*/storage/app/videos 2>/dev/null
ls -la /home/*/public_html/*/storage/app/videos 2>/dev/null
```

**Once you find it, the video path will be:**
```
{YOUR_FOUND_PATH}/storage/app/videos/
```

---

## ✅ Summary

**Video Storage Path:**
- **Relative:** `storage/app/videos/` (from project root)
- **Full:** `/path/to/welittlefarmers.com/storage/app/videos/`

**What to do:**
1. Find your project root path (use commands above)
2. Navigate to `{PROJECT_ROOT}/storage/app/videos/`
3. Run the replacement commands
4. Test and verify

**That's it!** The backend code confirms videos are in `storage/app/videos/` directory. 🎯
