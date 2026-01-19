# Alternative Ways to Check Video Codec (Without FFmpeg)

Since ffmpeg installation requires permissions, here are alternative methods:

## Method 1: Check via Browser DevTools

1. **Open video URL in Chrome** (not Safari):
   ```
   https://welittlefarmers.com/api/video/stream/YOUR_URL
   ```

2. **Open DevTools**:
   - Press `F12` or `Cmd+Option+I`
   - Go to **Network** tab

3. **Reload the page** to capture the video request

4. **Click on the video request** (look for `/video/stream/`)

5. **Check Headers**:
   - Look for `Content-Type` header
   - Check Response headers for codec hints

## Method 2: Use Online Video Analyzer

1. **Download a sample video** from your server:
   ```bash
   curl -o test_video.mp4 "YOUR_VIDEO_URL"
   ```

2. **Upload to online analyzer**:
   - https://www.online-convert.com/ (has codec info)
   - Or use: https://www.mediainfo.org/online

3. **Check the audio codec** shown in the analysis

## Method 3: Check via Backend (If You Have Access)

If you have SSH access to your backend server:

```bash
# SSH into server
ssh your-server

# Navigate to video storage
cd /path/to/video/storage

# Check codec of one video file
ffprobe video_file.mp4 2>&1 | grep -i "audio"

# Or get detailed info
ffprobe -v error -select_streams a:0 -show_entries stream=codec_name,codec_long_name,bit_rate,sample_rate,channels -of default=noprint_wrappers=1 video_file.mp4
```

## Method 4: Use MediaInfo (GUI Tool)

1. **Download MediaInfo**:
   - macOS: https://mediaarea.net/en/MediaInfo/Download/Mac_OS
   - Or via Homebrew: `brew install --cask mediainfo`

2. **Open video file** in MediaInfo

3. **Check Audio section**:
   - Look for "Codec" field
   - Should show: AAC, MP3, Opus, etc.

## Method 5: Quick Test - Re-encode One Video

If you want to test quickly without checking codec first:

1. **Download one video** from your server
2. **Re-encode it** (if you have ffmpeg on another machine):
   ```bash
   ffmpeg -i input.mp4 -c:v copy -c:a aac -b:a 128k -ar 44100 -ac 2 -movflags +faststart output.mp4
   ```
3. **Upload it back** and test in Safari
4. **If audio works**, then codec was the issue

## Method 6: Check Laravel Backend Logs

If your backend logs video processing, check:

1. **Video upload logs** - might show codec info
2. **Encoding logs** - if videos are processed after upload
3. **Database** - might store codec metadata

## Most Likely Scenario

Based on your test results (works on Android/Windows, not Safari), the issue is almost certainly:

- **Audio codec is NOT AAC**
- Most likely: **MP3** or **Opus** or **non-standard AAC**

**Solution**: Re-encode all videos with AAC audio codec.

## Quick Fix Without Checking

If you want to fix it immediately without checking:

1. **Re-encode all videos** with this command:
   ```bash
   ffmpeg -i input.mp4 -c:v copy -c:a aac -b:a 128k -ar 44100 -ac 2 -movflags +faststart output.mp4
   ```

2. **This will work** regardless of current codec because:
   - `-c:a aac` forces AAC codec
   - Safari supports AAC
   - Other platforms also support AAC

3. **Test in Safari** - audio should work

## Recommended Next Steps

1. **Install ffmpeg** (when you have permissions):
   ```bash
   sudo chown -R $(whoami) /opt/homebrew/Cellar
   brew install ffmpeg
   ```

2. **Or use backend server** to check codec (if you have access)

3. **Or proceed directly** with re-encoding (safest approach)
