#!/bin/bash

# Script to create IPA file from built iOS app
# Works with free Apple ID (Personal Team)

set -e

echo "📦 Creating IPA file from built app..."

# Navigate to project directory
cd "$(dirname "$0")"

# Check if app exists
APP_PATH="build/ios/iphoneos/Runner.app"
if [ ! -d "$APP_PATH" ]; then
    echo "❌ Error: $APP_PATH not found!"
    echo "Please build the app first: flutter build ios --release --no-codesign"
    exit 1
fi

echo "✅ Found app at: $APP_PATH"

# Create Payload directory
PAYLOAD_DIR="build/ios/iphoneos/Payload"
echo "📁 Creating Payload directory..."
rm -rf "$PAYLOAD_DIR"
mkdir -p "$PAYLOAD_DIR"

# Copy app to Payload
echo "📋 Copying app to Payload..."
cp -R "$APP_PATH" "$PAYLOAD_DIR/"

# Create IPA file
IPA_NAME="LittleFarmer_Release.ipa"
IPA_PATH="build/ios/iphoneos/$IPA_NAME"

echo "📦 Creating IPA file..."
cd build/ios/iphoneos
zip -r "$IPA_NAME" Payload/ > /dev/null
cd ../../..

# Move IPA to project root for easy access
mv "build/ios/iphoneos/$IPA_NAME" "./$IPA_NAME"

# Clean up
rm -rf "$PAYLOAD_DIR"

echo ""
echo "✅ IPA file created successfully!"
echo "📱 Location: $(pwd)/$IPA_NAME"
echo ""
echo "⚠️  Note: This IPA is NOT code-signed."
echo "   To install on your iPhone, you need to:"
echo "   1. Code-sign it in Xcode, OR"
echo "   2. Install directly from Xcode (Cmd + R)"
echo ""
