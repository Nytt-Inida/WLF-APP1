#!/bin/bash

# iOS Setup Script for Little Farmers Courses App
# This script sets up the development environment for iOS

set -e  # Exit on error

echo "🔧 Setting up iOS Development Environment..."
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Get the directory where the script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

echo -e "${BLUE}📁 Project Directory: $SCRIPT_DIR${NC}"
echo ""

# Check if running on macOS
if [[ "$OSTYPE" != "darwin"* ]]; then
    echo -e "${RED}❌ This script must be run on macOS${NC}"
    exit 1
fi

# Step 1: Check Flutter
echo -e "${YELLOW}Step 1: Checking Flutter...${NC}"
if ! command -v flutter &> /dev/null; then
    echo -e "${RED}❌ Flutter not found${NC}"
    echo "Please install Flutter: https://flutter.dev/docs/get-started/install/macos"
    exit 1
fi
echo -e "${GREEN}✅ Flutter installed${NC}"
flutter --version | head -n 1
echo ""

# Step 2: Check Xcode
echo -e "${YELLOW}Step 2: Checking Xcode...${NC}"
if ! command -v xcodebuild &> /dev/null; then
    echo -e "${RED}❌ Xcode not found${NC}"
    echo "Please install Xcode from the Mac App Store"
    exit 1
fi
echo -e "${GREEN}✅ Xcode installed${NC}"
xcodebuild -version | head -n 1
echo ""

# Step 3: Install CocoaPods
echo -e "${YELLOW}Step 3: Checking CocoaPods...${NC}"
if ! command -v pod &> /dev/null; then
    echo -e "${YELLOW}Installing CocoaPods...${NC}"
    sudo gem install cocoapods
else
    echo -e "${GREEN}✅ CocoaPods installed${NC}"
    pod --version
fi
echo ""

# Step 4: Flutter pub get
echo -e "${YELLOW}Step 4: Getting Flutter packages...${NC}"
flutter pub get
echo -e "${GREEN}✅ Flutter packages installed${NC}"
echo ""

# Step 5: Install iOS pods
echo -e "${YELLOW}Step 5: Installing iOS CocoaPods...${NC}"
cd ios
pod install
cd ..
echo -e "${GREEN}✅ iOS CocoaPods installed${NC}"
echo ""

# Step 6: Check firebase_options.dart
echo -e "${YELLOW}Step 6: Checking Firebase configuration...${NC}"
if [ -f "lib/firebase_options.dart" ]; then
    echo -e "${GREEN}✅ firebase_options.dart exists${NC}"
else
    echo -e "${YELLOW}⚠️  firebase_options.dart not found${NC}"
    echo "The file has been created, but you may need to regenerate it with:"
    echo "  flutterfire configure"
fi
echo ""

# Step 7: Flutter doctor
echo -e "${YELLOW}Step 7: Running Flutter doctor...${NC}"
flutter doctor
echo ""

# Summary
echo ""
echo -e "${GREEN}✅ Setup Complete!${NC}"
echo ""
echo "Next steps:"
echo "  1. Connect your iPhone via USB"
echo "  2. On iPhone: Settings → Privacy & Security → Enable Developer Mode"
echo "  3. Trust this computer when prompted on iPhone"
echo "  4. Run: ./build_ios.sh"
echo "   OR"
echo "  5. Open Xcode: open ios/Runner.xcworkspace"
echo ""
echo "For detailed instructions, see: IOS_BUILD_GUIDE.md"
