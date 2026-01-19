# How to Test Video URL in Safari on iPhone

## Method 1: Get URL from App Logs (Easiest)

### Step 1: Run the app and play a video
```bash
cd LitteleFarmer
flutter run
```

### Step 2: Open a course and play a video
- Navigate to a course
- Click on a video lesson
- Wait for video to start loading

### Step 3: Check terminal logs
Look for this line in the terminal:
```
=== VIDEO INITIALIZATION DEBUG ===
Video URL: https://welittlefarmers.com/api/video/stream/...
```

**Copy the entire URL** from the log output.

### Step 4: Test in Safari on iPhone
1. Open Safari on your iPhone
2. Paste the URL in the address bar
3. Press Enter/Go
4. The video should start playing
5. **Check if audio plays:**
   - If audio works in Safari → Issue is with Flutter configuration
   - If audio doesn't work in Safari → Issue is with video file or backend

---

## Method 2: Get URL from Network Inspector (More Detailed)

### Step 1: Enable Network Logging
The app already logs the video URL. But you can also:

1. Open Xcode
2. Connect your iPhone
3. Go to **Window → Devices and Simulators**
4. Select your iPhone
5. Click **Open Console**
6. Filter for "Video URL" or "video/stream"

### Step 2: Copy the URL
The URL will look like:
```
https://welittlefarmers.com/api/video/stream/eyJpdiI6...
```

### Step 3: Test in Safari
Same as Method 1, Step 4

---

## Method 3: Use Flutter DevTools Network Tab

### Step 1: Open DevTools
When you run `flutter run`, you'll see:
```
The Flutter DevTools debugger and profiler on iPhone is available at:
http://127.0.0.1:XXXXX/.../devtools/?uri=...
```

### Step 2: Open the DevTools URL in your browser
- Copy the DevTools URL
- Open it in Chrome/Safari on your Mac

### Step 3: Go to Network Tab
1. Click **Network** tab in DevTools
2. Play a video in the app
3. Look for requests to `/api/video/stream/`
4. Click on the request
5. Copy the **Request URL**

### Step 4: Test in Safari on iPhone
Same as Method 1, Step 4

---

## Method 4: Add Temporary UI to Display URL (For Testing)

If you want to see the URL directly in the app, we can add a debug button.

---

## What to Look For When Testing in Safari

### ✅ If Audio Works in Safari:
- **Conclusion**: Video file has audio, backend is fine
- **Issue**: Flutter/iOS configuration problem
- **Next Steps**: 
  - Check audio session configuration
  - Verify volume settings
  - Check if device mute switch is OFF

### ❌ If Audio Doesn't Work in Safari:
- **Conclusion**: Video file or backend issue
- **Possible Causes**:
  1. Video file has no audio track
  2. Video file audio track is corrupted
  3. Backend is not serving audio properly
- **Next Steps**:
  - Check video file on server
  - Verify video encoding
  - Test with a different video file

---

## Additional Test: Check Video File Format

### On Your Mac (if you have access to video files):

```bash
# Install ffmpeg if not already installed
brew install ffmpeg

# Check video file for audio track
ffprobe -v error -select_streams a:0 -show_entries stream=codec_name,codec_type,bit_rate -of default=noprint_wrappers=1 video_file.mp4

# If output shows audio stream → video has audio
# If no output → video has NO audio track
```

### Full Video Analysis:
```bash
ffprobe video_file.mp4
```

This will show:
- Video stream info
- Audio stream info (if present)
- Codec information
- Duration, bitrate, etc.

---

## Quick Test Checklist

- [ ] Get video URL from app logs
- [ ] Open URL in Safari on iPhone
- [ ] Check if video plays
- [ ] Check if audio plays
- [ ] Note the result:
  - ✅ Audio works in Safari → Flutter issue
  - ❌ Audio doesn't work in Safari → Video file/backend issue

---

## Expected Results

### If Video Has Audio:
- Safari will play video with audio
- You should hear sound
- Volume controls work

### If Video Has No Audio:
- Safari will play video but no sound
- This confirms the video file is the problem
- Need to re-encode video with audio track
