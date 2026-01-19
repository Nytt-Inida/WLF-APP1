#!/bin/bash

# ============================================
# Simple Video Replacement Script for iOS Audio Fix
# ============================================
# 
# USAGE:
# 1. Edit VIDEO_DIR below with your actual path
# 2. Run: bash REPLACE_VIDEOS_SIMPLE.sh
#
# ============================================

# ⚠️ STEP 1: EDIT THIS PATH TO MATCH YOUR SERVER
# Find your path by running: find /var/www -name "videos" -type d | grep storage
VIDEO_DIR="/var/www/html/welittlefarmers.com/storage/app/videos"
BACKUP_DIR="/var/www/html/welittlefarmers.com/storage/app/videos_backup"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo "=========================================="
echo "iOS Video Audio Fix - Replacement Script"
echo "=========================================="
echo ""

# Check if video directory exists
if [ ! -d "$VIDEO_DIR" ]; then
    echo -e "${RED}❌ Video directory not found: $VIDEO_DIR${NC}"
    echo ""
    echo "Please edit VIDEO_DIR in the script with your actual path."
    echo "Find your path by running:"
    echo "  find /var/www -name 'videos' -type d | grep storage"
    echo "  OR"
    echo "  find /home -name 'videos' -type d | grep storage"
    exit 1
fi

# Check if ffmpeg is installed
if ! command -v ffmpeg &> /dev/null; then
    echo -e "${RED}❌ ffmpeg is not installed${NC}"
    echo ""
    echo "Install it with:"
    echo "  Ubuntu/Debian: sudo apt-get install -y ffmpeg"
    echo "  CentOS/RHEL:   sudo yum install -y ffmpeg"
    exit 1
fi

echo -e "${GREEN}✅ Video directory found: $VIDEO_DIR${NC}"
echo -e "${GREEN}✅ ffmpeg is installed${NC}"
echo ""

# Count videos
VIDEO_COUNT=$(ls -1 "$VIDEO_DIR"/*.mp4 2>/dev/null | wc -l)
if [ "$VIDEO_COUNT" -eq 0 ]; then
    echo -e "${RED}❌ No .mp4 videos found in $VIDEO_DIR${NC}"
    exit 1
fi

echo -e "${YELLOW}Found $VIDEO_COUNT video(s)${NC}"
echo ""

# Ask for mode
echo "Select mode:"
echo "  1) Test mode (process 1 video only) - RECOMMENDED FIRST"
echo "  2) Batch mode (process 5 videos)"
echo "  3) All videos (process everything)"
read -p "Enter choice (1/2/3): " mode

# Create backup directory
echo ""
echo -e "${YELLOW}📦 Creating backup...${NC}"
mkdir -p "$BACKUP_DIR"
cp "$VIDEO_DIR"/*.mp4 "$BACKUP_DIR/" 2>/dev/null
BACKUP_COUNT=$(ls -1 "$BACKUP_DIR"/*.mp4 2>/dev/null | wc -l)
echo -e "${GREEN}✅ Backed up $BACKUP_COUNT video(s)${NC}"
echo ""

# Process videos
cd "$VIDEO_DIR" || exit 1

case $mode in
    1)
        # Test mode - first video only
        FIRST_VIDEO=$(ls -1 *.mp4 | head -1)
        VIDEOS=("$FIRST_VIDEO")
        echo -e "${YELLOW}Test mode: Processing 1 video${NC}"
        ;;
    2)
        # Batch mode - 5 videos
        VIDEOS=($(ls -1 *.mp4 | head -5))
        echo -e "${YELLOW}Batch mode: Processing 5 videos${NC}"
        ;;
    3)
        # All videos
        VIDEOS=($(ls -1 *.mp4))
        echo -e "${YELLOW}Processing all $VIDEO_COUNT videos${NC}"
        echo -e "${RED}⚠️  This will take a while!${NC}"
        read -p "Continue? (yes/no): " confirm
        if [ "$confirm" != "yes" ]; then
            echo "Cancelled."
            exit 0
        fi
        ;;
    *)
        echo -e "${RED}Invalid choice${NC}"
        exit 1
        ;;
esac

PROCESSED=0
FAILED=0

echo ""
echo "=========================================="
echo "Processing videos..."
echo "=========================================="
echo ""

for VIDEO in "${VIDEOS[@]}"; do
    echo -e "${YELLOW}Processing: $VIDEO${NC}"
    
    # Create AAC version
    TEMP_FILE="${VIDEO}.aac_temp"
    
    ffmpeg -i "$VIDEO" \
        -c:v copy \
        -c:a aac \
        -b:a 128k \
        -ar 44100 \
        -ac 2 \
        -movflags +faststart \
        -y \
        "$TEMP_FILE" \
        2>&1 | grep -E "(error|Error|ERROR)" > /dev/null
    
    if [ $? -eq 0 ] || [ ! -f "$TEMP_FILE" ]; then
        echo -e "  ${RED}❌ Conversion failed${NC}"
        rm -f "$TEMP_FILE"
        ((FAILED++))
        continue
    fi
    
    # Check file size (should be similar to original)
    ORIGINAL_SIZE=$(stat -f%z "$VIDEO" 2>/dev/null || stat -c%s "$VIDEO" 2>/dev/null)
    NEW_SIZE=$(stat -f%z "$TEMP_FILE" 2>/dev/null || stat -c%s "$TEMP_FILE" 2>/dev/null)
    
    if [ -z "$NEW_SIZE" ] || [ "$NEW_SIZE" -lt 1000 ]; then
        echo -e "  ${RED}❌ Output file too small (conversion failed)${NC}"
        rm -f "$TEMP_FILE"
        ((FAILED++))
        continue
    fi
    
    # Atomic swap
    mv "$TEMP_FILE" "$VIDEO"
    
    if [ $? -eq 0 ]; then
        echo -e "  ${GREEN}✅ Success: $VIDEO${NC}"
        ((PROCESSED++))
    else
        echo -e "  ${RED}❌ Replacement failed${NC}"
        # Restore from backup
        cp "$BACKUP_DIR/$VIDEO" "$VIDEO"
        ((FAILED++))
    fi
    
    echo ""
done

# Summary
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
echo "   2. Test videos on iOS (audio should work now!)"
echo "   3. If all good, continue with remaining videos"
echo "   4. If issues, restore from backup:"
echo "      cp $BACKUP_DIR/*.mp4 $VIDEO_DIR/"
echo ""
