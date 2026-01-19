#!/bin/bash

# Fix CocoaPods Issues and Build Script

set -e

echo "🔧 Fixing CocoaPods Issues..."
echo ""

cd "$(dirname "$0")"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# Step 1: Check Flutter
echo -e "${YELLOW}Step 1: Checking Flutter...${NC}"
if ! command -v flutter &> /dev/null; then
    echo -e "${RED}❌ Flutter not found in PATH${NC}"
    echo "Please add Flutter to PATH or run: export PATH=\"\$PATH:/path/to/flutter/bin\""
    exit 1
fi
echo -e "${GREEN}✅ Flutter found${NC}"
echo ""

# Step 2: Check CocoaPods
echo -e "${YELLOW}Step 2: Checking CocoaPods...${NC}"
if ! command -v pod &> /dev/null; then
    echo -e "${YELLOW}Installing CocoaPods...${NC}"
    sudo gem install cocoapods
else
    echo -e "${GREEN}✅ CocoaPods found${NC}"
fi
echo ""

# Step 3: Clean and reinstall pods
echo -e "${YELLOW}Step 3: Cleaning and reinstalling CocoaPods...${NC}"
cd ios

# Remove old pods
echo "Removing old Pods..."
rm -rf Pods
rm -f Podfile.lock
rm -rf .symlinks
rm -rf Flutter/Flutter.framework
rm -rf Flutter/Flutter.podspec

# Clean pod cache
echo "Cleaning pod cache..."
pod cache clean --all 2>/dev/null || true

# Install pods
echo "Installing pods (this may take a few minutes)..."
pod install

cd ..
echo -e "${GREEN}✅ CocoaPods installed${NC}"
echo ""

# Step 4: Get Flutter dependencies
echo -e "${YELLOW}Step 4: Getting Flutter dependencies...${NC}"
flutter pub get
echo -e "${GREEN}✅ Flutter dependencies installed${NC}"
echo ""

# Step 5: Clean Flutter build
echo -e "${YELLOW}Step 5: Cleaning Flutter build...${NC}"
flutter clean
echo -e "${GREEN}✅ Build cleaned${NC}"
echo ""

# Step 6: Check connected devices
echo -e "${YELLOW}Step 6: Checking for connected iPhone...${NC}"
DEVICES=$(flutter devices | grep -i "iphone\|ios" | grep -v "Simulator" || true)
if [ -z "$DEVICES" ]; then
    echo -e "${YELLOW}⚠️  No iPhone detected${NC}"
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

# Step 7: Build and run
echo -e "${YELLOW}Step 7: Building and installing on iPhone...${NC}"
echo ""
echo -e "${GREEN}🚀 Starting build...${NC}"
echo "This will take 5-10 minutes for the first build."
echo ""

flutter run

echo ""
echo -e "${GREEN}✅ Build process completed!${NC}"
