# Video Optimization Tip - Reduce Audio Lag

## 🎯 Issue: Audio Lagging on iPhone

Even though video works in Safari, audio might lag on iPhone app due to:
1. Video buffering
2. Network latency
3. Video file optimization

## ✅ Solution: Re-encode with Better Settings

When converting videos, use these optimized settings to reduce lag:

```bash
ffmpeg -i input.mp4 \
    -c:v copy \
    -c:a aac \
    -b:a 128k \
    -ar 44100 \
    -ac 2 \
    -movflags +faststart \
    -frag_duration 2000000 \
    -frag_size 2000000 \
    -min_frag_duration 2000000 \
    output.mp4
```

**Key optimizations:**
- `+faststart` - Moves metadata to beginning (already using this ✅)
- `-frag_duration` - Sets fragment duration for better streaming
- `-frag_size` - Sets fragment size for better buffering
- `-min_frag_duration` - Minimum fragment duration

## 🔄 Alternative: Simpler Optimization

If the above doesn't work, try:

```bash
ffmpeg -i input.mp4 \
    -c:v copy \
    -c:a aac \
    -b:a 128k \
    -ar 44100 \
    -ac 2 \
    -movflags +faststart+empty_moov \
    output.mp4
```

**This ensures:**
- Metadata at beginning
- Empty moov atom (better for streaming)

## 📝 For Your Current Video

If the converted video has audio lag, re-convert with:

```bash
cd /www/wwwroot/welittlefarmers.com/storage/app/videos

# Re-convert with better settings
ffmpeg -i f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4 \
    -c:v copy \
    -c:a aac \
    -b:a 128k \
    -ar 44100 \
    -ac 2 \
    -movflags +faststart+empty_moov \
    f47ac10b-58cc-4372-a567-0e02b2c3d479_optimized.mp4

# Replace
mv f47ac10b-58cc-4372-a567-0e02b2c3d479_optimized.mp4 f47ac10b-58cc-4372-a567-0e02b2c3d479.mp4
```

## ✅ Summary

**For future conversions, use:**
```bash
ffmpeg -i input.mp4 \
    -c:v copy \
    -c:a aac \
    -b:a 128k \
    -ar 44100 \
    -ac 2 \
    -movflags +faststart+empty_moov \
    output.mp4
```

This should reduce audio lag on iPhone! 🚀
