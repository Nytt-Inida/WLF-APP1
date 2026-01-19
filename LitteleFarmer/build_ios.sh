#!/bin/bash

# iOS Build Script for Little Farmers Courses App
# This script automates the iOS build process

set -e  # Exit on error

echo "🚀 Starting iOS Build Process..."
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Get the directory where the script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

echo -e "${GREEN}📁 Project Directory: $SCRIPT_DIR${NC}"
echo ""

# Step 1: Check Flutter installation
echo -e "${YELLOW}Step 1: Checking Flutter installation...${NC}"
if ! command -v flutter &> /dev/null; then
    echo -e "${RED}❌ Flutter is not installed or not in PATH${NC}"
    echo "Please install Flutter from: https://flutter.dev/docs/get-started/install/macos"
    exit 1
fi
echo -e "${GREEN}✅ Flutter found${NC}"
flutter --version | head -n 1
echo ""

# Step 2: Check Xcode installation
echo -e "${YELLOW}Step 2: Checking Xcode installation...${NC}"
if ! command -v xcodebuild &> /dev/null; then
    echo -e "${RED}❌ Xcode is not installed${NC}"
    echo "Please install Xcode from the Mac App Store"
    exit 1
fi
echo -e "${GREEN}✅ Xcode found${NC}"
xcodebuild -version | head -n 1
echo ""

# Step 3: Flutter doctor check
echo -e "${YELLOW}Step 3: Running Flutter doctor...${NC}"
flutter doctor
echo ""

# Step 4: Get Flutter dependencies
echo -e "${YELLOW}Step 4: Getting Flutter dependencies...${NC}"
flutter pub get
echo -e "${GREEN}✅ Flutter dependencies installed${NC}"
echo ""

# Step 5: Check CocoaPods
echo -e "${YELLOW}Step 5: Checking CocoaPods...${NC}"
if ! command -v pod &> /dev/null; then
    echo -e "${YELLOW}⚠️  CocoaPods not found. Installing...${NC}"
    sudo gem install cocoapods
fi
echo -e "${GREEN}✅ CocoaPods found${NC}"
pod --version
echo ""

# Step 6: Install iOS dependencies
echo -e "${YELLOW}Step 6: Installing iOS CocoaPods dependencies...${NC}"
cd ios
if [ -f "Podfile.lock" ]; then
    echo "Podfile.lock exists, running pod install..."
else
    echo "No Podfile.lock found, running pod install..."
fi
pod install
cd ..
echo -e "${GREEN}✅ iOS dependencies installed${NC}"
echo ""

# Step 7: Check for connected devices
echo -e "${YELLOW}Step 7: Checking for connected iOS devices...${NC}"
DEVICES=$(flutter devices | grep -i "iphone\|ios" || true)
if [ -z "$DEVICES" ]; then
    echo -e "${YELLOW}⚠️  No iOS device connected${NC}"
    echo "Please connect your iPhone via USB and ensure:"
    echo "  1. iPhone is unlocked"
    echo "  2. You've trusted this computer on your iPhone"
    echo "  3. Developer Mode is enabled (Settings → Privacy & Security → Developer Mode)"
    echo ""
    echo "Available devices:"
    flutter devices
    echo ""
    read -p "Do you want to continue anyway? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
else
    echo -e "${GREEN}✅ iOS device found${NC}"
    echo "$DEVICES"
fi
echo ""

# Step 8: Build options
echo -e "${YELLOW}Step 8: Build Options${NC}"
echo "Choose build type:"
echo "  1) Debug build (for development)"
echo "  2) Release build (for testing)"
echo "  3) Open in Xcode (manual build)"
read -p "Enter choice (1-3): " BUILD_CHOICE

case $BUILD_CHOICE in
    1)
        echo -e "${YELLOW}Building debug version...${NC}"
        flutter run --debug
        ;;
    2)
        echo -e "${YELLOW}Building release version...${NC}"
        flutter build ios --release
        echo -e "${GREEN}✅ Release build completed!${NC}"
        echo "To install on device, open Xcode and:"
        echo "  1. Open ios/Runner.xcworkspace"
        echo "  2. Select your device"
        echo "  3. Product → Archive"
        ;;
    3)
        echo -e "${YELLOW}Opening Xcode...${NC}"
        open ios/Runner.xcworkspace
        echo -e "${GREEN}✅ Xcode opened${NC}"
        echo "In Xcode:"
        echo "  1. Select your iPhone from the device menu"
        echo "  2. Click the Run button (▶️) or press Cmd+R"
        ;;
    *)
        echo -e "${RED}Invalid choice${NC}"
        exit 1
        ;;
esac

echo ""
echo -e "${GREEN}🎉 Build process completed!${NC}"
