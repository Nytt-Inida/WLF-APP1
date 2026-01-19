#!/bin/bash

# Script to replace videos with AAC audio codec (for iOS compatibility)
# This replaces originals (no duplicate storage needed)

# Configuration
VIDEO_DIR="/path/to/your/laravel/project/storage/app/videos"
BACKUP_DIR="/path/to/your/laravel/project/storage/app/videos_backup"
BATCH_SIZE=5  # Process 5 videos at a time

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo "=========================================="
echo "Video Replacement Script (AAC for iOS)"
echo "=========================================="
echo ""

# Check if ffmpeg is installed
if ! command -v ffmpeg &> /dev/null; then
    echo -e "${RED}❌ ffmpeg is not installed${NC}"
    echo "Install it with: apt-get install ffmpeg (Ubuntu) or brew install ffmpeg (macOS)"
    exit 1
fi

# Check if video directory exists
if [ ! -d "$VIDEO_DIR" ]; then
    echo -e "${RED}❌ Video directory not found: $VIDEO_DIR${NC}"
    echo "Please update VIDEO_DIR in the script"
    exit 1
fi

# Create backup directory
echo -e "${YELLOW}📦 Creating backup directory...${NC}"
mkdir -p "$BACKUP_DIR"

# Ask for confirmation
echo ""
echo -e "${YELLOW}⚠️  This script will:${NC}"
echo "   1. Backup videos to: $BACKUP_DIR"
echo "   2. Replace videos with AAC audio codec"
echo "   3. Keep same file names"
echo ""
read -p "Continue? (yes/no): " confirm

if [ "$confirm" != "yes" ]; then
    echo "Cancelled."
    exit 0
fi

# Count videos
VIDEO_COUNT=$(find "$VIDEO_DIR" -maxdepth 1 -name "*.mp4" | wc -l)
echo ""
echo -e "${GREEN}Found $VIDEO_COUNT video(s)${NC}"
echo ""

# Ask for mode
echo "Select mode:"
echo "  1) Test mode (process 1 video only)"
echo "  2) Batch mode (process $BATCH_SIZE videos)"
echo "  3) All videos (process everything)"
read -p "Enter choice (1/2/3): " mode

case $mode in
    1)
        # Test mode - process first video only
        VIDEO=$(find "$VIDEO_DIR" -maxdepth 1 -name "*.mp4" | head -1)
        if [ -z "$VIDEO" ]; then
            echo -e "${RED}❌ No videos found${NC}"
            exit 1
        fi
        VIDEOS=("$VIDEO")
        ;;
    2)
        # Batch mode
        VIDEOS=($(find "$VIDEO_DIR" -maxdepth 1 -name "*.mp4" | head -$BATCH_SIZE))
        ;;
    3)
        # All videos
        VIDEOS=($(find "$VIDEO_DIR" -maxdepth 1 -name "*.mp4"))
        ;;
    *)
        echo -e "${RED}Invalid choice${NC}"
        exit 1
        ;;
esac

PROCESSED=0
FAILED=0

# Process each video
for VIDEO in "${VIDEOS[@]}"; do
    FILENAME=$(basename "$VIDEO")
    TEMP_FILE="${VIDEO}.aac_temp"
    
    echo ""
    echo -e "${YELLOW}Processing: $FILENAME${NC}"
    
    # Backup original
    echo "  📦 Backing up..."
    cp "$VIDEO" "$BACKUP_DIR/$FILENAME"
    
    if [ $? -ne 0 ]; then
        echo -e "  ${RED}❌ Backup failed${NC}"
        ((FAILED++))
        continue
    fi
    
    # Convert to AAC
    echo "  🔄 Converting to AAC..."
    ffmpeg -i "$VIDEO" \
        -c:v copy \
        -c:a aac \
        -b:a 128k \
        -ar 44100 \
        -ac 2 \
        -movflags +faststart \
        -y \
        "$TEMP_FILE" \
        2>&1 | grep -E "(error|Error|ERROR)" && {
        echo -e "  ${RED}❌ Conversion failed${NC}"
        ((FAILED++))
        rm -f "$TEMP_FILE"
        continue
    }
    
    # Check if temp file was created
    if [ ! -f "$TEMP_FILE" ]; then
        echo -e "  ${RED}❌ Conversion failed (no output file)${NC}"
        ((FAILED++))
        continue
    fi
    
    # Replace original
    echo "  ✅ Replacing original..."
    mv "$TEMP_FILE" "$VIDEO"
    
    if [ $? -eq 0 ]; then
        echo -e "  ${GREEN}✅ Success: $FILENAME${NC}"
        ((PROCESSED++))
    else
        echo -e "  ${RED}❌ Replacement failed${NC}"
        # Restore from backup
        cp "$BACKUP_DIR/$FILENAME" "$VIDEO"
        ((FAILED++))
    fi
done

# Summary
echo ""
echo "=========================================="
echo "Summary"
echo "=========================================="
echo -e "${GREEN}✅ Processed: $PROCESSED${NC}"
echo -e "${RED}❌ Failed: $FAILED${NC}"
echo ""
echo "Backups saved to: $BACKUP_DIR"
echo ""
echo -e "${YELLOW}⚠️  Next steps:${NC}"
echo "   1. Test videos on Android/Web (should work same)"
echo "   2. Test videos on iOS (audio should work now)"
echo "   3. If all good, you can delete backups to save space"
echo ""
