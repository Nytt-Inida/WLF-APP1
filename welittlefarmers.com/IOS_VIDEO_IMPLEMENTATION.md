# iOS Video Fix - Implementation Guide

## Overview

This guide shows how to serve iOS-compatible videos without affecting Android/Web users.

## Step 1: Create iOS Video Storage Folder

On your server, create the iOS videos folder:

```bash
cd /path/to/your/laravel/project
mkdir -p storage/app/videos/ios
chmod 755 storage/app/videos/ios
```

## Step 2: Transcode Videos (One-Time Setup)

### Option A: Transcode All Videos at Once

```bash
cd storage/app/videos

# Transcode all videos to iOS-compatible format
for video in *.mp4; do
    echo "Processing: $video"
    ffmpeg -i "$video" \
        -c:v copy \
        -c:a aac \
        -b:a 128k \
        -ar 44100 \
        -ac 2 \
        -movflags +faststart \
        "ios/$video" \
        2>&1 | grep -E "(error|Error|ERROR)" || echo "✅ Done: $video"
done
```

### Option B: Transcode in Batches (Safer)

```bash
# Process 10 videos at a time
cd storage/app/videos
ls *.mp4 | head -10 | while read video; do
    echo "Processing: $video"
    ffmpeg -i "$video" \
        -c:v copy \
        -c:a aac \
        -b:a 128k \
        -ar 44100 \
        -ac 2 \
        -movflags +faststart \
        "ios/$video"
done
```

### Option C: Transcode Single Video (For Testing)

```bash
cd storage/app/videos
ffmpeg -i "test_video.mp4" \
    -c:v copy \
    -c:a aac \
    -b:a 128k \
    -ar 44100 \
    -ac 2 \
    -movflags +faststart \
    "ios/test_video.mp4"
```

## Step 3: Update Backend Code

Modify `app/Http/Controllers/Api/ApiVideoStreamController.php`:

### Change 1: Update `getVideoPath()` method

**Find this method (around line 277):**

```php
private function getVideoPath($lesson)
{
    $videoUrl = $lesson->video_url;

    // Case 1: If it's just a filename
    if (!filter_var($videoUrl, FILTER_VALIDATE_URL) && !str_contains($videoUrl, '/')) {
        return 'videos/' . $videoUrl;
    }

    // Case 2: If it's a full URL, extract filename
    if (filter_var($videoUrl, FILTER_VALIDATE_URL)) {
        $path = parse_url($videoUrl, PHP_URL_PATH);
        $filename = basename($path);
        return 'videos/' . $filename;
    }

    // Case 3: If it already has 'videos/' prefix
    if (str_starts_with($videoUrl, 'videos/')) {
        return $videoUrl;
    }

    // Case 4: If it's a path without videos/ prefix
    $filename = basename($videoUrl);
    return 'videos/' . $filename;
}
```

**Replace with:**

```php
private function getVideoPath($lesson, $isIOS = false)
{
    $videoUrl = $lesson->video_url;
    $filename = null;

    // Extract filename from various formats
    if (!filter_var($videoUrl, FILTER_VALIDATE_URL) && !str_contains($videoUrl, '/')) {
        $filename = $videoUrl;
    } elseif (filter_var($videoUrl, FILTER_VALIDATE_URL)) {
        $path = parse_url($videoUrl, PHP_URL_PATH);
        $filename = basename($path);
    } elseif (str_starts_with($videoUrl, 'videos/')) {
        $filename = basename($videoUrl);
    } else {
        $filename = basename($videoUrl);
    }

    // If iOS request and iOS version exists, return iOS path
    if ($isIOS) {
        $iosPath = 'videos/ios/' . $filename;
        if (Storage::disk('local')->exists($iosPath)) {
            return $iosPath;
        }
        // Fallback to original if iOS version doesn't exist yet
    }

    // Return original video path
    return 'videos/' . $filename;
}
```

### Change 2: Add iOS Detection Method

**Add this new method after `getVideoPath()`:**

```php
/**
 * Detect if request is from iOS device
 */
private function isIOSRequest(Request $request, $tokenData = null)
{
    // Check User-Agent header
    $userAgent = $request->header('User-Agent', '');
    if (preg_match('/iPhone|iPad|iPod|iOS/i', $userAgent)) {
        return true;
    }

    // Check token data (if available)
    if ($tokenData && isset($tokenData['user_agent'])) {
        if (preg_match('/iPhone|iPad|iPod|iOS/i', $tokenData['user_agent'])) {
            return true;
        }
    }

    return false;
}
```

### Change 3: Update `streamVideo()` method

**Find the `streamVideo()` method (around line 138) and locate this section:**

```php
// Get video file path
$videoPath = $this->getVideoPath($lesson);

if (!Storage::disk('local')->exists($videoPath)) {
    Log::error('Video file not found (API)', [
        'path' => $videoPath,
        'lesson_video_url' => $lesson->video_url
    ]);
    return $this->videoError('Video file not found', 404);
}
```

**Replace with:**

```php
// Detect iOS request
$isIOS = $this->isIOSRequest($request, $tokenData);

// Get video file path (iOS version if iOS, original otherwise)
$videoPath = $this->getVideoPath($lesson, $isIOS);

if (!Storage::disk('local')->exists($videoPath)) {
    Log::error('Video file not found (API)', [
        'path' => $videoPath,
        'lesson_video_url' => $lesson->video_url,
        'is_ios' => $isIOS
    ]);
    return $this->videoError('Video file not found', 404);
}

// Log iOS detection for debugging (optional, remove in production)
if ($isIOS) {
    Log::info('Serving iOS-compatible video', [
        'lesson_id' => $lesson->id,
        'video_path' => $videoPath
    ]);
}
```

## Step 4: Test the Implementation

### Test 1: Verify iOS Detection

1. Generate a video URL from iOS app
2. Check Laravel logs: `storage/logs/laravel.log`
3. Look for "Serving iOS-compatible video" message
4. Verify `video_path` shows `videos/ios/` prefix

### Test 2: Verify Android Still Works

1. Generate a video URL from Android app
2. Video should play normally (no change)
3. Check logs - should NOT see iOS detection

### Test 3: Verify iOS Audio Works

1. Play video on iOS device
2. Audio should now be audible
3. Video controls should work

## Step 5: Monitoring & Rollout

### Gradual Rollout Strategy

1. **Week 1**: Transcode 10 most popular videos
2. **Week 2**: Transcode next 20 videos
3. **Week 3**: Transcode remaining videos
4. **Monitor**: Check logs daily for any issues

### Monitoring Checklist

- [ ] Check Laravel logs for iOS detection
- [ ] Monitor video serving performance
- [ ] Verify Android users unaffected
- [ ] Track iOS user feedback
- [ ] Monitor server storage usage

## Rollback Plan

If something goes wrong:

1. **Revert code changes**:
   ```bash
   git checkout app/Http/Controllers/Api/ApiVideoStreamController.php
   ```

2. **Or manually remove iOS detection**:
   - Change `getVideoPath($lesson, $isIOS = false)` back to `getVideoPath($lesson)`
   - Remove `isIOSRequest()` method
   - Remove iOS detection in `streamVideo()`

3. **All users get original videos** (back to current state)

4. **No data loss** - iOS videos remain in `videos/ios/` folder (can delete later)

## Troubleshooting

### Issue: iOS videos not found

**Solution**: Check if videos are in correct location:
```bash
ls -la storage/app/videos/ios/
```

### Issue: iOS detection not working

**Solution**: Check User-Agent in logs:
```php
Log::info('User-Agent check', [
    'user_agent' => $request->header('User-Agent'),
    'is_ios' => $isIOS
]);
```

### Issue: Transcoding taking too long

**Solution**: 
- Use `-c:v copy` (copies video, doesn't re-encode)
- Only audio is re-encoded (much faster)
- Run transcoding during off-peak hours

### Issue: Storage running out

**Solution**:
- Transcode in batches
- Monitor disk usage: `df -h`
- Consider cloud storage for iOS videos

## Performance Notes

- **Video codec**: Copied (no re-encoding) - very fast
- **Audio codec**: Re-encoded to AAC - takes ~1-2 min per video
- **File size**: Similar to original (audio is small part)
- **Serving**: No performance impact (just different file path)

## Security Notes

- iOS videos in `storage/app/videos/ios/` are protected by same access controls
- Token validation still applies
- No security changes needed
