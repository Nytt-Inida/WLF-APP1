#!/bin/bash

# Script to check video codec compatibility with Safari/iOS

echo "=========================================="
echo "Video Codec Checker for Safari Compatibility"
echo "=========================================="
echo ""

# Check if ffprobe is installed
if ! command -v ffprobe &> /dev/null; then
    echo "❌ ffprobe is not installed"
    echo "Install it with: brew install ffmpeg"
    exit 1
fi

# Get video URL from user or use first argument
if [ -z "$1" ]; then
    echo "Usage: ./CHECK_VIDEO_CODEC.sh <video_url_or_file_path>"
    echo ""
    echo "Example:"
    echo "  ./CHECK_VIDEO_CODEC.sh https://welittlefarmers.com/api/video/stream/..."
    echo "  ./CHECK_VIDEO_CODEC.sh /path/to/video.mp4"
    exit 1
fi

VIDEO_SOURCE="$1"

echo "📹 Checking video: $VIDEO_SOURCE"
echo ""

# Check if it's a URL or file path
if [[ $VIDEO_SOURCE == http* ]]; then
    echo "🌐 Downloading video from URL (first 10MB for analysis)..."
    curl -r 0-10485760 -o /tmp/video_sample.mp4 "$VIDEO_SOURCE" 2>/dev/null
    if [ $? -ne 0 ]; then
        echo "❌ Failed to download video"
        exit 1
    fi
    VIDEO_FILE="/tmp/video_sample.mp4"
else
    VIDEO_FILE="$VIDEO_SOURCE"
    if [ ! -f "$VIDEO_FILE" ]; then
        echo "❌ File not found: $VIDEO_FILE"
        exit 1
    fi
fi

echo ""
echo "🔍 Analyzing video codec..."
echo ""

# Get video codec info
VIDEO_INFO=$(ffprobe -v error -select_streams v:0 -show_entries stream=codec_name,codec_long_name,width,height,bit_rate -of default=noprint_wrappers=1 "$VIDEO_FILE" 2>/dev/null)
AUDIO_INFO=$(ffprobe -v error -select_streams a:0 -show_entries stream=codec_name,codec_long_name,bit_rate,sample_rate,channels -of default=noprint_wrappers=1 "$VIDEO_FILE" 2>/dev/null)

# Check if audio stream exists
if [ -z "$AUDIO_INFO" ]; then
    echo "❌ CRITICAL: No audio stream found in video!"
    echo "   This video has NO audio track."
    exit 1
fi

echo "📊 Video Stream:"
echo "$VIDEO_INFO"
echo ""
echo "🔊 Audio Stream:"
echo "$AUDIO_INFO"
echo ""

# Extract codec name
AUDIO_CODEC=$(echo "$AUDIO_INFO" | grep "codec_name=" | cut -d'=' -f2)
VIDEO_CODEC=$(echo "$VIDEO_INFO" | grep "codec_name=" | cut -d'=' -f2)

echo "=========================================="
echo "Safari/iOS Compatibility Check"
echo "=========================================="
echo ""

# Check video codec
if [ "$VIDEO_CODEC" = "h264" ]; then
    echo "✅ Video Codec: H.264 (Compatible with Safari/iOS)"
else
    echo "⚠️  Video Codec: $VIDEO_CODEC"
    echo "   Safari/iOS prefers H.264"
fi

# Check audio codec
if [ "$AUDIO_CODEC" = "aac" ]; then
    echo "✅ Audio Codec: AAC (Compatible with Safari/iOS)"
    
    # Get sample rate and bitrate
    SAMPLE_RATE=$(echo "$AUDIO_INFO" | grep "sample_rate=" | cut -d'=' -f2)
    BITRATE=$(echo "$AUDIO_INFO" | grep "bit_rate=" | cut -d'=' -f2)
    
    echo "   Sample Rate: $SAMPLE_RATE Hz"
    if [ ! -z "$BITRATE" ]; then
        BITRATE_KBPS=$((BITRATE / 1000))
        echo "   Bitrate: ${BITRATE_KBPS} kbps"
    fi
    
    # Check if it's a standard AAC profile
    echo "   ✅ AAC is the recommended codec for Safari/iOS"
else
    echo "❌ Audio Codec: $AUDIO_CODEC"
    echo "   ⚠️  Safari/iOS requires AAC audio codec"
    echo "   This is likely why audio doesn't work in Safari!"
    echo ""
    echo "🔧 SOLUTION: Re-encode video with AAC audio codec"
    echo ""
    echo "Re-encoding command:"
    echo "  ffmpeg -i input.mp4 \\"
    echo "    -c:v copy \\"
    echo "    -c:a aac \\"
    echo "    -b:a 128k \\"
    echo "    -ar 44100 \\"
    echo "    -ac 2 \\"
    echo "    -movflags +faststart \\"
    echo "    output.mp4"
fi

echo ""
echo "=========================================="
echo "Full Video Info:"
echo "=========================================="
ffprobe -v error "$VIDEO_FILE" 2>&1 | head -20

# Cleanup if we downloaded a sample
if [ "$VIDEO_SOURCE" != "$VIDEO_FILE" ]; then
    rm -f "$VIDEO_FILE"
fi

echo ""
echo "✅ Analysis complete!"
