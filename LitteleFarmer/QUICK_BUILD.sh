#!/bin/bash

# Quick Build Script - Minimal checks, maximum speed

echo "🚀 Quick iOS Build"
echo ""

cd "$(dirname "$0")"

# Try to find Flutter
FLUTTER_CMD=""
if command -v flutter &> /dev/null; then
    FLUTTER_CMD="flutter"
elif [ -f "$HOME/flutter/bin/flutter" ]; then
    FLUTTER_CMD="$HOME/flutter/bin/flutter"
    export PATH="$HOME/flutter/bin:$PATH"
elif [ -f "/usr/local/flutter/bin/flutter" ]; then
    FLUTTER_CMD="/usr/local/flutter/bin/flutter"
    export PATH="/usr/local/flutter/bin:$PATH"
else
    echo "❌ Flutter not found!"
    echo ""
    echo "Quick install options:"
    echo "  1. Homebrew: brew install --cask flutter"
    echo "  2. Manual: https://flutter.dev/docs/get-started/install/macos"
    echo ""
    echo "Or if Flutter is installed elsewhere, run:"
    echo "  export PATH=\"\$PATH:/path/to/flutter/bin\""
    echo "  ./QUICK_BUILD.sh"
    exit 1
fi

echo "✅ Using Flutter: $FLUTTER_CMD"
echo ""

# Install CocoaPods if needed
if ! command -v pod &> /dev/null; then
    echo "Installing CocoaPods..."
    sudo gem install cocoapods
fi

# Get dependencies
echo "📦 Getting dependencies..."
$FLUTTER_CMD pub get

echo "📦 Installing iOS dependencies..."
cd ios
pod install
cd ..

# Build and run
echo "🏗️  Building and installing..."
echo ""
$FLUTTER_CMD run
