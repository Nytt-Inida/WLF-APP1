# How to Install FFmpeg (for Video Codec Checking)

## Option 1: Install via Homebrew (Recommended)

If you have permission issues, run this command in your terminal:

```bash
# Fix Homebrew permissions first (if needed)
sudo chown -R $(whoami) /opt/homebrew/Cellar
sudo chown -R $(whoami) /opt/homebrew/Library/Homebrew

# Then install ffmpeg
brew install ffmpeg
```

## Option 2: Install via Homebrew (Alternative Path)

If you're using Intel Mac or different Homebrew path:

```bash
# For Intel Mac
sudo chown -R $(whoami) /usr/local/Cellar
brew install ffmpeg

# Or try without sudo first
brew install ffmpeg
```

## Option 3: Download Pre-built Binary

1. Go to: https://evermeet.cx/ffmpeg/
2. Download `ffmpeg` and `ffprobe`
3. Place them in `/usr/local/bin/` or add to PATH

## Option 4: Use MacPorts (if you have it)

```bash
sudo port install ffmpeg
```

## Verify Installation

After installation, verify it works:

```bash
ffprobe -version
```

You should see version information.

## Then Run the Codec Check

Once ffmpeg is installed, run:

```bash
cd LitteleFarmer
./CHECK_VIDEO_CODEC.sh "YOUR_VIDEO_URL_HERE"
```

## Quick Alternative: Check Codec via Backend

If you can't install ffmpeg right now, you can:

1. **Check via backend server** (if you have SSH access):
   ```bash
   ssh your-server
   ffprobe video_file.mp4
   ```

2. **Check via online tool**:
   - Upload video to: https://www.online-convert.com/
   - Or use: https://www.ffmpeg.org/download.html

3. **Check via browser DevTools**:
   - Open video URL in Chrome
   - Open DevTools → Network tab
   - Click on video request
   - Check Response Headers for codec info
