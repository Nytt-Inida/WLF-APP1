#!/bin/bash

# Immediate iOS Build Script
# This script will check prerequisites and build the app

set -e

echo "🚀 Starting iOS Build Process..."
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

echo -e "${BLUE}📁 Project: $SCRIPT_DIR${NC}"
echo ""

# Function to find Flutter
find_flutter() {
    # Check common Flutter locations
    FLUTTER_PATHS=(
        "$HOME/flutter/bin/flutter"
        "/usr/local/flutter/bin/flutter"
        "$HOME/development/flutter/bin/flutter"
        "$HOME/Documents/flutter/bin/flutter"
        "/opt/flutter/bin/flutter"
    )
    
    for path in "${FLUTTER_PATHS[@]}"; do
        if [ -f "$path" ]; then
            echo "$path"
            return 0
        fi
    done
    
    # Check if Flutter is in PATH via which
    if command -v flutter &> /dev/null; then
        which flutter
        return 0
    fi
    
    return 1
}

# Step 1: Check Flutter
echo -e "${YELLOW}Step 1: Checking Flutter...${NC}"
FLUTTER_PATH=$(find_flutter)
if [ -z "$FLUTTER_PATH" ]; then
    echo -e "${RED}❌ Flutter not found!${NC}"
    echo ""
    echo "Flutter is required to build this app."
    echo ""
    echo "To install Flutter:"
    echo "  1. Download from: https://flutter.dev/docs/get-started/install/macos"
    echo "  2. Extract to a location (e.g., ~/flutter)"
    echo "  3. Add to PATH:"
    echo "     export PATH=\"\$PATH:\$HOME/flutter/bin\""
    echo "  4. Run: flutter doctor"
    echo ""
    echo "Or install via Homebrew:"
    echo "  brew install --cask flutter"
    echo ""
    read -p "Do you have Flutter installed in a custom location? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        read -p "Enter full path to Flutter (e.g., /Users/yourname/flutter/bin/flutter): " CUSTOM_FLUTTER
        if [ -f "$CUSTOM_FLUTTER" ]; then
            FLUTTER_PATH="$CUSTOM_FLUTTER"
            export PATH="$(dirname "$CUSTOM_FLUTTER"):$PATH"
        else
            echo -e "${RED}Flutter not found at that path. Exiting.${NC}"
            exit 1
        fi
    else
        exit 1
    fi
else
    echo -e "${GREEN}✅ Flutter found: $FLUTTER_PATH${NC}"
    export PATH="$(dirname "$FLUTTER_PATH"):$PATH"
    flutter --version | head -n 1
fi
echo ""

# Step 2: Check Xcode
echo -e "${YELLOW}Step 2: Checking Xcode...${NC}"
if ! command -v xcodebuild &> /dev/null; then
    echo -e "${RED}❌ Xcode not found!${NC}"
    echo "Please install Xcode from the Mac App Store"
    exit 1
fi
echo -e "${GREEN}✅ Xcode found${NC}"
xcodebuild -version | head -n 1
echo ""

# Step 3: Check CocoaPods
echo -e "${YELLOW}Step 3: Checking CocoaPods...${NC}"
if ! command -v pod &> /dev/null; then
    echo -e "${YELLOW}⚠️  CocoaPods not found. Installing...${NC}"
    sudo gem install cocoapods
else
    echo -e "${GREEN}✅ CocoaPods found${NC}"
    pod --version
fi
echo ""

# Step 4: Flutter doctor
echo -e "${YELLOW}Step 4: Running Flutter doctor...${NC}"
flutter doctor
echo ""

# Step 5: Get dependencies
echo -e "${YELLOW}Step 5: Getting Flutter dependencies...${NC}"
flutter pub get
echo -e "${GREEN}✅ Dependencies installed${NC}"
echo ""

# Step 6: Install iOS pods
echo -e "${YELLOW}Step 6: Installing iOS CocoaPods...${NC}"
cd ios
pod install
cd ..
echo -e "${GREEN}✅ iOS dependencies installed${NC}"
echo ""

# Step 7: Check connected devices
echo -e "${YELLOW}Step 7: Checking for connected iPhone...${NC}"
DEVICES=$(flutter devices | grep -i "iphone\|ios" || true)
if [ -z "$DEVICES" ]; then
    echo -e "${YELLOW}⚠️  No iPhone detected${NC}"
    echo ""
    echo "Please ensure:"
    echo "  1. iPhone is connected via USB"
    echo "  2. iPhone is unlocked"
    echo "  3. Developer Mode is enabled (Settings → Privacy & Security → Developer Mode)"
    echo "  4. You've trusted this computer on iPhone"
    echo ""
    echo "Available devices:"
    flutter devices
    echo ""
    read -p "Continue anyway? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
else
    echo -e "${GREEN}✅ iPhone detected${NC}"
    echo "$DEVICES"
fi
echo ""

# Step 8: Build and run
echo -e "${YELLOW}Step 8: Building and installing app...${NC}"
echo ""
echo -e "${BLUE}This will:${NC}"
echo "  1. Build the iOS app"
echo "  2. Install it on your iPhone"
echo "  3. Launch it automatically"
echo ""
echo -e "${YELLOW}Note: First build may take 5-10 minutes${NC}"
echo ""
read -p "Continue? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo ""
    echo -e "${GREEN}🚀 Starting build...${NC}"
    flutter run
else
    echo ""
    echo "Opening Xcode for manual build..."
    open ios/Runner.xcworkspace
    echo ""
    echo "In Xcode:"
    echo "  1. Select your iPhone from the device menu"
    echo "  2. Click Run (▶️) or press Cmd+R"
    echo "  3. First time: Configure signing in Signing & Capabilities"
fi

echo ""
echo -e "${GREEN}✅ Process completed!${NC}"
