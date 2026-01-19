import Flutter
import UIKit
import AVFoundation

@main
@objc class AppDelegate: FlutterAppDelegate {
  override func application(
    _ application: UIApplication,
    didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?
  ) -> Bool {
    // Configure audio session for video playback FIRST (CRITICAL for iOS audio)
    // This must be done before any video playback to ensure audio works
    do {
      let audioSession = AVAudioSession.sharedInstance()
      // CRITICAL: Use .playback category with .default mode (not .moviePlayback) for better compatibility
      // This ensures audio plays even when device is in silent mode
      try audioSession.setCategory(.playback, mode: .default, options: [])
      // CRITICAL: Activate with .notifyOthersOnDeactivation to ensure audio plays
      try audioSession.setActive(true, options: [.notifyOthersOnDeactivation])
      print("Audio session configured successfully for video playback")
    } catch {
      print("Failed to configure audio session: \(error)")
      // Try with simpler configuration as fallback
      do {
        let audioSession = AVAudioSession.sharedInstance()
        try audioSession.setCategory(.playback, options: [])
        try audioSession.setActive(true)
        print("Audio session configured with fallback settings")
      } catch {
        print("Failed to configure audio session with fallback: \(error)")
      }
    }
    
    // Register plugins
    GeneratedPluginRegistrant.register(with: self)
    
    // Set up method channel for audio session activation AFTER plugin registration
    // Get the FlutterViewController after registration
    guard let controller = window?.rootViewController as? FlutterViewController else {
      return super.application(application, didFinishLaunchingWithOptions: launchOptions)
    }
    
    let audioChannel = FlutterMethodChannel(name: "little_farmer/audio", binaryMessenger: controller.binaryMessenger)
    
    audioChannel.setMethodCallHandler { (call: FlutterMethodCall, result: @escaping FlutterResult) in
      if call.method == "activateAudioSession" {
        do {
          let audioSession = AVAudioSession.sharedInstance()
          // CRITICAL: Deactivate first to reset any previous state
          try audioSession.setActive(false, options: [.notifyOthersOnDeactivation])
          // Wait for deactivation to complete
          Thread.sleep(forTimeInterval: 0.15)
          // CRITICAL: Use .playback category with NO options to ensure audio plays even when muted
          // This is the most reliable configuration for video playback
          // CRITICAL: Do NOT use .mixWithOthers or other options - they can cause audio issues
          try audioSession.setCategory(.playback, mode: .default, options: [])
          // CRITICAL: Activate with .notifyOthersOnDeactivation to ensure audio plays
          try audioSession.setActive(true, options: [.notifyOthersOnDeactivation])
          print("✅ Audio session activated successfully via method channel")
          print("   Category: .playback, Mode: .default, Options: []")
          result(true)
        } catch {
          print("Error activating audio session: \(error)")
          // Try fallback - simplest configuration
          do {
            let audioSession = AVAudioSession.sharedInstance()
            try audioSession.setCategory(.playback)
            try audioSession.setActive(true)
            print("Audio session activated with fallback")
            result(true)
          } catch {
            print("Error with fallback audio session: \(error)")
            result(false)
          }
        }
      } else {
        result(FlutterMethodNotImplemented)
      }
    }
    return super.application(application, didFinishLaunchingWithOptions: launchOptions)
  }
}
