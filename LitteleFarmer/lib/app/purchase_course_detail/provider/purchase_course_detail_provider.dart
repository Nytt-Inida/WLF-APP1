import 'dart:async';
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter/foundation.dart' show defaultTargetPlatform, TargetPlatform;
import 'package:little_farmer/app/download_certificate/ui/download_certificate_screen.dart';
import 'package:little_farmer/app/purchase_course_detail/model/purchase_course_detail_model.dart';
import 'package:little_farmer/app/quiz/ui/quiz_screen.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:little_farmer/app/course_detail/model/course_completion_model.dart';
import 'package:little_farmer/app/course_detail/model/review_question_model.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:video_player/video_player.dart';
import 'package:synchronized/synchronized.dart';

class PurchaseCourseDetailProvider extends ChangeNotifier {
  static const MethodChannel _audioChannel = MethodChannel('little_farmer/audio');
  
  late VideoPlayerController controller;
  bool isControllerInitialize = false;
  
  // Activate audio session before playing (CRITICAL for iOS)
  Future<void> _activateAudioSession() async {
    if (defaultTargetPlatform == TargetPlatform.iOS) {
      try {
        await _audioChannel.invokeMethod('activateAudioSession');
        debugPrint("Audio session activated successfully");
      } catch (e) {
        debugPrint("Error activating audio session: $e");
      }
    }
  }
  List<String> duration = [];
  Duration _currentPosition = Duration.zero;
  Duration get currentPosition => _currentPosition;
  late StreamController<Duration> streamController;
  bool isMute = false;
  int currentSection = 0;
  int currentVideoInSection = 0;
  bool isArticle = false;
  bool isQuiz = false;
  bool isVideo = false;
  PurchaseCourseDetailModel? courseDetailModel;
  bool isPurchaseCourseDetailFetchApiCalling = false;
  bool isReviewFetchApiCalling = false;
  bool isReviewSubmitApiCalling = false;
  bool isCompletionCheckApiCalling = false;
  ReviewQuestionModel? reviewQuestionModel;
  CourseCompletionModel? courseCompletionModel;
  String articleUrl = "";
  String quiz_title = "";
  int courseId = 0;
  Lock mutex = Lock();
  int retryCount = 0;
  int maxRetries = 3;
  int totalLesson = 0;
  int totalCompletedLesson = 0;

  bool isAutoAdvancing = false;
  int autoAdvanceSeconds = 3;
  Timer? autoAdvanceTimer;
  
  // Store context for auto-advance
  BuildContext? _storedContext;
  
  // Track if stream controller is initialized
  bool _isStreamControllerInitialized = false;

  bool _isFullScreen = false;
  bool get isFullScreen => _isFullScreen;
  set isFullScreen(bool value) {
    if (_isFullScreen != value) {
      _isFullScreen = value;
      // Use addPostFrameCallback to safely call notifyListeners
      WidgetsBinding.instance.addPostFrameCallback((_) {
        try {
          notifyListeners();
        } catch (e) {
          debugPrint("Error in notifyListeners (isFullScreen): $e");
        }
      });
    }
  }

  bool _isControllerVisible = false;
  bool get isControllerVisible => _isControllerVisible;
  set isControllerVisible(bool value) {
    if (_isControllerVisible != value) {
      _isControllerVisible = value;
      // Use addPostFrameCallback to safely call notifyListeners
      WidgetsBinding.instance.addPostFrameCallback((_) {
        try {
          notifyListeners();
        } catch (e) {
          debugPrint("Error in notifyListeners (isControllerVisible): $e");
        }
      });
    }
  }
  
  // Video position saving
  Timer? _positionSaveTimer;
  int? _currentLessonId;
  int _lastSavedPosition = 0;
  
  // Video URL caching (similar to website implementation)
  final Map<int, Map<String, dynamic>> _videoUrlCache = {};
  
  // Track pending video loads to cancel if needed
  bool _isLoadingVideo = false;
  
  // Throttle listener updates to reduce rebuilds
  DateTime? _lastListenerUpdate;
  static const Duration _listenerUpdateInterval = Duration(milliseconds: 250); // Update max 4 times per second
  bool? _lastIsPlaying; // Track play/pause changes so UI updates reliably (especially on iOS)

  @override
  void dispose() {
    // Cancel position save timer
    _positionSaveTimer?.cancel();
    // Properly dispose video controller
    if (isControllerInitialize) {
      try {
        // Save position before disposing
        if (_currentLessonId != null && controller.value.isInitialized) {
          _savePosition(_currentLessonId!, controller.value.position.inSeconds);
        }
        controller.removeListener(_videoListener);
        controller.pause();
        controller.dispose();
      } catch (e) {
        print("Error disposing controller in dispose: $e");
      }
    }
    // Cancel auto-advance timer
    autoAdvanceTimer?.cancel();
    // Close stream controller if initialized
    if (_isStreamControllerInitialized) {
      try {
        streamController.close();
        _isStreamControllerInitialized = false;
      } catch (e) {
        // Stream controller might already be closed
      }
    }
    super.dispose();
  }

  Future<void> resetProvider() async {
    // Properly dispose video controller to stop any background audio
    if (isControllerInitialize) {
      try {
        controller.pause();
        await controller.dispose();
        await Future.delayed(Duration(milliseconds: 200)); // Ensure disposal completes
      } catch (e) {
        print("Error disposing controller in resetProvider: $e");
      }
      isControllerInitialize = false;
    }
    duration = [];
    isMute = false;
    isArticle = false;
    isQuiz = false;
    isVideo = false;
    currentSection = 0;
    currentVideoInSection = 0;
    isMute = false;
    isPurchaseCourseDetailFetchApiCalling = false;
    articleUrl = "";
    quiz_title = "";
    courseId = 0;
    mutex = Lock();
    retryCount = 0;
    maxRetries = 3;
    totalLesson = 0;
    totalCompletedLesson = 0;
    // Cancel auto-advance timer if running
    autoAdvanceTimer?.cancel();
    isAutoAdvancing = false;
    autoAdvanceSeconds = 3;
    // Don't call notifyListeners() here - this is called during dispose
    // and the widget tree is locked. No need to notify when disposing.
  }

  Future<void> fetchCourseDetail({required BuildContext context}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isPurchaseCourseDetailFetchApiCalling = true;
        notifyListeners();

        var response = await ApiResponse().onFetchPurchaseCourseDetail(courseId: courseId);
        isPurchaseCourseDetailFetchApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          PurchaseCourseDetailModel courseDetailModel = PurchaseCourseDetailModel.fromJson(jsonMap);
          this.courseDetailModel = courseDetailModel;
          // Calculate total lessons (excluding sample tests)
          totalLesson = (jsonMap[Constant.sections] as List?)?.expand((section) => 
            (section[Constant.items] as List).where((item) => 
              item[Constant.itemName] != null && 
              !item[Constant.itemName].toString().toLowerCase().contains('sample')
            )
          ).length ?? 0;
          
          // Calculate completed lessons (excluding sample tests)
          totalCompletedLesson = (jsonMap[Constant.sections] as List?)?.expand((section) => 
            (section[Constant.items] as List).where((item) => 
              item[Constant.isComplete] == true &&
              item[Constant.itemName] != null && 
              !item[Constant.itemName].toString().toLowerCase().contains('sample')
            )
          ).length ?? 0;

          // Find first accessible and incomplete item
          for (var i = 0; i < courseDetailModel.sections.length; i++) {
            bool isUnCompleteItemFound = false;
            for (var j = 0; j < courseDetailModel.sections[i].items.length; j++) {
              final item = courseDetailModel.sections[i].items[j];
              // Check if item is accessible and not complete
              if (item.isAccessible && !item.isComplete) {
                currentSection = i;
                currentVideoInSection = j;
                isUnCompleteItemFound = true;
                gotoNextItem(context: context);
                break;
              }
            }
            if (isUnCompleteItemFound) {
              break;
            }
          }
                  notifyListeners();
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
      } catch (e) {
        isPurchaseCourseDetailFetchApiCalling = false;
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isPurchaseCourseDetailFetchApiCalling = false;
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  Future<void> onCompleteLesson({required int lessonId, required bool isVideoType}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        var response = await ApiResponse().onCompleteLesson(lessonId: lessonId, isVideoType: isVideoType);
        if (response.statusCode == Constant.response_200) {
          // Update completion count after successful backend update
          if (courseDetailModel != null) {
            // Recalculate total completed lessons (excluding sample tests)
            totalCompletedLesson = 0;
            for (var section in courseDetailModel!.sections) {
              for (var item in section.items) {
                if (item.isComplete && 
                    !item.itemName.toLowerCase().contains('sample')) {
                  totalCompletedLesson++;
                }
              }
            }
          }
          notifyListeners();
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
      } catch (e) {
        Utils.showSnackbarMessage(message: e.toString());
      }
    } else {
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  Future<void> launchArticleUrl() async {
    final Uri url = Uri.parse(articleUrl);
    if (!await launchUrl(url)) {
      throw Exception('Could not launch $url');
    } else {
      if (courseDetailModel == null) return;
      courseDetailModel!.sections[currentSection].items[currentVideoInSection].isComplete = true;
      if (courseDetailModel!.sections[currentSection].items[currentVideoInSection].articleId != null) {
        onCompleteLesson(lessonId: courseDetailModel!.sections[currentSection].items[currentVideoInSection].articleId!, isVideoType: false);
      }
      notifyListeners();
    }
  }

  Future<void> completeArticle() async {
    if (courseDetailModel == null) return;
    courseDetailModel!.sections[currentSection].items[currentVideoInSection].isComplete = true;
    if (courseDetailModel!.sections[currentSection].items[currentVideoInSection].articleId != null) {
      onCompleteLesson(lessonId: courseDetailModel!.sections[currentSection].items[currentVideoInSection].articleId!, isVideoType: false);
    }
    notifyListeners();
  }

  Future<void> courseClicked({required BuildContext context, required int section, required int sectionItem}) async {
    if (courseDetailModel == null) return;
    
    // Validate indices
    if (section >= courseDetailModel!.sections.length) return;
    if (sectionItem >= courseDetailModel!.sections[section].items.length) return;
    
    // Update current section and item indices
    currentSection = section;
    currentVideoInSection = sectionItem;
    
    final item = courseDetailModel!.sections[currentSection].items[currentVideoInSection];
    
    // Check if item is accessible
    if (!item.isAccessible && item.isVideo) {
      Utils.showSnackbarMessage(message: "This lesson requires purchase. Please buy the course to access it.");
      return;
    }
    
    // Check item type and handle accordingly
    if (item.isVideo) {
      isArticle = false;
      isQuiz = false;
      isVideo = true;
      
      // Properly dispose existing controller
      if (isControllerInitialize) {
        try {
          await controller.pause();
          await controller.dispose();
        } catch (e) {
          print("Error disposing controller: $e");
        }
        isControllerInitialize = false;
        notifyListeners();
      }
      
      retryCount = 0; // Reset retry count
      int? lessonId = item.lessonId;
      
      // Validate video URL
      if (item.lessonVideoUrl.isEmpty) {
        Utils.showSnackbarMessage(message: "Video URL is not available.");
        return;
      }
      
      // Cancel previous position save timer
      _positionSaveTimer?.cancel();
      
      // Fetch saved position before initializing video
      Duration savedPosition = Duration.zero;
      if (lessonId != null) {
        try {
          var positionResponse = await ApiResponse().onGetLessonPosition(lessonId: lessonId);
          if (positionResponse.statusCode == 200) {
            var positionData = json.decode(positionResponse.body);
            if (positionData['position_seconds'] != null && positionData['position_seconds'] > 0) {
              savedPosition = Duration(seconds: positionData['position_seconds']);
              _lastSavedPosition = positionData['position_seconds'];
            }
          }
        } catch (e) {
          print("Error fetching saved position: $e");
          // Continue with zero position if fetch fails
        }
      }
      
      initializeController(
        context: context, 
        filename: item.lessonVideoUrl, 
        lastPosition: savedPosition,
        lessonId: lessonId,
      );
    } else if (item.isQuiz) {
      // Quiz should be handled first before article check
      // Dispose/pause video controller before going to quiz to stop background audio
      if (isControllerInitialize) {
        try {
          controller.pause();
          await controller.dispose();
          await Future.delayed(Duration(milliseconds: 100)); // Ensure disposal completes
          isControllerInitialize = false;
        } catch (e) {
          print("Error disposing controller for quiz: $e");
        }
      }
      isArticle = false;
      isQuiz = true;
      isVideo = false;
      quiz_title = item.itemName;
      notifyListeners();
      gotoQuizScreen(context: context);
    } else if (item.isArticle) {
      // Dispose/pause video controller before going to article to stop background audio
      if (isControllerInitialize) {
        try {
          controller.pause();
          await controller.dispose();
          await Future.delayed(Duration(milliseconds: 100)); // Ensure disposal completes
          isControllerInitialize = false;
        } catch (e) {
          print("Error disposing controller for article: $e");
        }
      }
      // Articles are no longer displayed - skip them and move to next item if possible
      // For now, just mark as complete
      isArticle = false;
      isQuiz = false;
      isVideo = false;
      notifyListeners();
    }
  }

  Future<void> manageController() async {
    if (isControllerInitialize && controller.value.isInitialized) {
      controller.pause();
    } else {
      controller.dispose();
    }
  }

  // Get cached or fetch signed video URL (similar to website implementation)
  Future<Map<String, dynamic>?> _getSignedVideoUrl(int lessonId) async {
    // Check cache first
    if (_videoUrlCache.containsKey(lessonId)) {
      final cachedData = _videoUrlCache[lessonId]!;
      // Check if cache is still valid (60s buffer before expiration)
      if (cachedData['expires_at'] != null) {
        final now = DateTime.now().millisecondsSinceEpoch ~/ 1000;
        final expiresAt = cachedData['expires_at'] as int;
        if (now < (expiresAt - 60)) {
          return cachedData;
        }
      }
      // Cache expired, remove it
      _videoUrlCache.remove(lessonId);
    }
    
    // Fetch new URL
    try {
      final response = await ApiResponse().onGenerateVideoSignedUrl(lessonId: lessonId);
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['success'] == true && data['video_url'] != null) {
          // Calculate expiration time
          final expiresIn = data['expires_in'] ?? 1800; // Default 30 minutes
          final expiresAt = DateTime.now().millisecondsSinceEpoch ~/ 1000 + expiresIn;
          
          final cachedData = {
            'video_url': data['video_url'],
            'last_watched_seconds': data['last_watched_seconds'],
            'expires_at': expiresAt,
          };
          
          // Cache the URL
          _videoUrlCache[lessonId] = cachedData;
          return cachedData;
        }
      }
    } catch (e) {
      print("Error fetching signed URL: $e");
    }
    return null;
  }
  
  // Prefetch next video URL for smoother transitions
  void _prefetchNextVideoUrl() {
    if (courseDetailModel == null || _currentLessonId == null) return;
    
    // Find next video item
    bool found = false;
    for (var i = currentSection; i < courseDetailModel!.sections.length; i++) {
      final startIndex = found ? 0 : currentVideoInSection + 1;
      for (var j = startIndex; j < courseDetailModel!.sections[i].items.length; j++) {
        final item = courseDetailModel!.sections[i].items[j];
        if (item.isVideo && item.lessonId != null && item.isAccessible) {
          // Prefetch URL in background (don't await)
          _getSignedVideoUrl(item.lessonId!);
          return;
        }
      }
      if (!found && i == currentSection) found = true;
    }
  }

  Future<void> initializeController({required BuildContext context, required String filename, required Duration lastPosition, int? lessonId}) async {
    // Prevent multiple simultaneous loads
    if (_isLoadingVideo) return;
    
    _storedContext = context; // Store context for auto-advance
    await mutex.synchronized(() async {
      _isLoadingVideo = true;
      
      // Dispose existing controller first
      if (isControllerInitialize) {
        try {
          _positionSaveTimer?.cancel();
          if (controller.value.isInitialized) {
            controller.removeListener(_videoListener);
            controller.pause();
          }
          await controller.dispose();
          // Wait a bit to ensure disposal completes
          await Future.delayed(Duration(milliseconds: 100));
        } catch (e) {
          print("Error disposing old controller: $e");
        }
        isControllerInitialize = false;
        // Don't notify listeners here - it can cause "setState during build" errors
      }
      
      isVideo = true;
      _currentPosition = Duration.zero;

      try {
        String videoUrl = filename;
        int? lastWatchedSeconds;
        
        // If lessonId is provided, fetch signed URL (with caching)
        if (lessonId != null) {
          final urlData = await _getSignedVideoUrl(lessonId);
          if (urlData != null) {
            videoUrl = urlData['video_url'] as String;
            lastWatchedSeconds = urlData['last_watched_seconds'] as int?;
            
            // Use cached position if available and no explicit position provided
            if (lastPosition == Duration.zero && lastWatchedSeconds != null && lastWatchedSeconds > 0) {
              lastPosition = Duration(seconds: lastWatchedSeconds);
            }
          } else {
            Utils.showSnackbarMessage(message: "Failed to load video URL");
            _isLoadingVideo = false;
            return;
          }
        }

        // CRITICAL FOR iOS: Activate audio session BEFORE creating controller
        // AVPlayer needs audio session to be active when it's created to enable audio
        if (defaultTargetPlatform == TargetPlatform.iOS) {
          await _activateAudioSession();
          await Future.delayed(Duration(milliseconds: 200)); // Give audio session time to activate
        }
        
        // CRITICAL iOS FIX: Do NOT use Range header on iOS
        // iOS AVPlayer requires Content-Length header from server for range requests
        // If server doesn't send Content-Length, Range header causes CoreMediaErrorDomain error -12939
        // Solution: For iOS, use Accept header only (no Range) to avoid range request issues
        // For Android: pass headers with Range request for better performance
        
        if (defaultTargetPlatform == TargetPlatform.iOS) {
          // iOS: Let AVPlayer handle all headers automatically
          // The issue is that AVPlayer tries range requests but server doesn't send Content-Length
          // By omitting httpHeaders, AVPlayer uses its default behavior which might work better
          controller = VideoPlayerController.networkUrl(
            Uri.parse(videoUrl),
            videoPlayerOptions: VideoPlayerOptions(
              mixWithOthers: false, // CRITICAL: Don't mix with others - we want exclusive audio
              allowBackgroundPlayback: false,
            ),
            // Omit httpHeaders for iOS - let AVPlayer handle everything automatically
            // This allows AVPlayer to negotiate with the server directly without our interference
          );
        } else {
          // Android: Use Range header for better performance
          controller = VideoPlayerController.networkUrl(
            Uri.parse(videoUrl),
            videoPlayerOptions: VideoPlayerOptions(
              mixWithOthers: false,
              allowBackgroundPlayback: false,
            ),
            httpHeaders: {
              'Accept': '*/*',
              'Range': 'bytes=0-',
            },
          );
        }
        
        // Initialize and set up listeners
        await controller.initialize();
        
        // CRITICAL: Check if controller was disposed during initialization
        if (!controller.value.isInitialized) {
          _isLoadingVideo = false;
          debugPrint("Controller initialization failed or was disposed");
          return;
        }
        
        duration = controller.value.duration.toString().split('.');
        
        // CRITICAL FOR iOS: Log video properties to diagnose audio issues
        debugPrint("=== VIDEO INITIALIZATION DEBUG ===");
        debugPrint("📹 Video URL (COPY THIS TO TEST IN SAFARI):");
        debugPrint("   $videoUrl");
        debugPrint("📊 Video Properties:");
        debugPrint("   Duration: ${controller.value.duration}");
        debugPrint("   Size: ${controller.value.size}");
        debugPrint("   Aspect Ratio: ${controller.value.aspectRatio}");
        debugPrint("🔊 Audio Properties:");
        debugPrint("   Initial Volume: ${controller.value.volume}");
        debugPrint("   isMute Flag: $isMute");
        debugPrint("   isPlaying: ${controller.value.isPlaying}");
        debugPrint("===================================");
        debugPrint("💡 TO TEST: Copy the Video URL above and paste it in Safari");
        debugPrint("   ✅ If audio works in Safari → Flutter configuration issue");
        debugPrint("   ❌ If audio doesn't work in Safari → Video codec compatibility issue");
        debugPrint("   🔍 Safari requires AAC audio codec - check video encoding");
        debugPrint("===================================");
        
        // CRITICAL FOR iOS: Ensure isMute is false before setting volume
        isMute = false;
        
        // CRITICAL FOR iOS: Activate audio session BEFORE setting volume (iOS requirement)
        // This must be done right after initialization for iOS to play audio
        if (defaultTargetPlatform == TargetPlatform.iOS) {
          await _activateAudioSession();
          await Future.delayed(Duration(milliseconds: 300)); // Give audio session more time to activate
        }
        
        // CRITICAL: Set initial volume to ensure audio plays (iOS requires explicit volume setting)
        // Set volume multiple times to ensure it sticks on iOS
        await _ensureVolume();
        await Future.delayed(Duration(milliseconds: 200));
        await _ensureVolume(); // Set again to ensure it sticks
        await Future.delayed(Duration(milliseconds: 150));
        await _ensureVolume(); // One more time
        
        // CRITICAL FOR iOS: Verify volume was set correctly
        debugPrint("After volume setting - volume: ${controller.value.volume}, isMute: $isMute");
        
        // CRITICAL FOR iOS: One more audio session activation after volume is set
        if (defaultTargetPlatform == TargetPlatform.iOS) {
          await Future.delayed(Duration(milliseconds: 200));
          await _activateAudioSession();
          await _ensureVolume(); // Set volume again after audio session reactivation
          debugPrint("Audio session reactivated after volume setting");
          debugPrint("Final volume check - volume: ${controller.value.volume}, isMute: $isMute");
        }
        
        // Seek to saved position if available
        if (lastPosition.inSeconds > 0 && lastPosition < controller.value.duration) {
          await controller.seekTo(lastPosition);
          _lastSavedPosition = lastPosition.inSeconds;
        }
        
        // Add optimized listener (throttled)
        controller.addListener(_videoListener);
        
        // Set up stream controller
        streamController = StreamController<Duration>.broadcast();
        _isStreamControllerInitialized = true;
        isControllerInitialize = true;
        
        // Start position saving timer
        if (lessonId != null) {
          _currentLessonId = lessonId;
          _startPositionSaveTimer();
        }
        
        // Prefetch next video URL for smoother transitions
        Future.delayed(Duration(seconds: 2), () {
          _prefetchNextVideoUrl();
        });
        
        // CRITICAL FOR iOS: Show controls by default (iOS doesn't have native controls)
        // On iOS, we need to show controls immediately so users can interact
        if (defaultTargetPlatform == TargetPlatform.iOS) {
          isControllerVisible = true;
        } else {
          controllerTimer(); // Android/Web: Use timer to show/hide
        }
        
        // CRITICAL FOR iOS: Auto-play video after initialization
        // iOS AVPlayer sometimes doesn't start automatically, so we need to explicitly play
        // This fixes the "video shows as paused" issue on iPhone
        if (defaultTargetPlatform == TargetPlatform.iOS) {
          await Future.delayed(Duration(milliseconds: 800)); // Give UI and video more time to initialize
          try {
            if (controller.value.isInitialized && !controller.value.isPlaying) {
              debugPrint("🎬 Auto-playing video on iOS...");
              
              // Activate audio session BEFORE playing
              await _activateAudioSession();
              await Future.delayed(Duration(milliseconds: 200));
              
              // Wait a bit for video to buffer before playing (reduces audio lag)
              await Future.delayed(Duration(milliseconds: 500));
              
              // Play video
              await controller.play();
              
              // Verify it's actually playing
              await Future.delayed(Duration(milliseconds: 300));
              if (!controller.value.isPlaying) {
                debugPrint("⚠️ Video didn't start playing, retrying...");
                await controller.play();
                await Future.delayed(Duration(milliseconds: 200));
              }
              
              // Set volume after play
              await _ensureVolume();
              
              // Reactivate audio session and set volume again
              await Future.delayed(Duration(milliseconds: 300));
              await _activateAudioSession();
              await _ensureVolume();
              
              debugPrint("✅ Auto-played video on iOS");
              debugPrint("   isPlaying: ${controller.value.isPlaying}");
              debugPrint("   volume: ${controller.value.volume}");
              
              // Force UI update to hide play button
              WidgetsBinding.instance.addPostFrameCallback((_) {
                try {
                  notifyListeners();
                } catch (e) {
                  debugPrint("Error in notifyListeners (after auto-play): $e");
                }
              });
            }
          } catch (e) {
            debugPrint("❌ Error auto-playing video: $e");
          }
        }
        
        WidgetsBinding.instance.addPostFrameCallback((_) {
          try {
            notifyListeners();
          } catch (e) {
            debugPrint("Error in notifyListeners (initializeController): $e");
          }
        });
        _isLoadingVideo = false;
        
      } catch (error) {
        _isLoadingVideo = false;
        debugPrint("Video initialization error: $error");
        if (retryCount < maxRetries) {
          retryCount++;
          // Retry after short delay
          Future.delayed(Duration(milliseconds: 500), () {
            initializeController(context: context, filename: filename, lastPosition: Duration.zero, lessonId: lessonId);
          });
        } else {
          Utils.showSnackbarMessage(message: "Failed to load video. Please try again.");
          retryCount = 0;
        }
      }
    });
  }
  
  // Optimized video listener with throttling to reduce rebuilds
  void _videoListener() {
    try {
      if (!isControllerInitialize || !controller.value.isInitialized) return;
      
      // Always react to play/pause changes (don't throttle these) so overlays update correctly.
      final isPlayingNow = controller.value.isPlaying;
      if (_lastIsPlaying == null || _lastIsPlaying != isPlayingNow) {
        _lastIsPlaying = isPlayingNow;
        WidgetsBinding.instance.addPostFrameCallback((_) {
          try {
            notifyListeners();
          } catch (e) {
            debugPrint("Error in notifyListeners (isPlaying change): $e");
          }
        });
      }

      // Throttle listener updates
      final now = DateTime.now();
      if (_lastListenerUpdate != null && 
          now.difference(_lastListenerUpdate!) < _listenerUpdateInterval) {
        return;
      }
      _lastListenerUpdate = now;
      
      final position = controller.value.position;
      if (_isStreamControllerInitialized && !streamController.isClosed) {
        streamController.add(position);
      }
      _currentPosition = position;
      
      // Check if video has reached the end (more accurate detection)
      if (controller.value.duration.inMilliseconds > 0 && !isAutoAdvancing) {
        Duration totalDuration = controller.value.duration;
        Duration currentPos = _currentPosition;
        
        // Video ended when position is at or very close to duration (within 200ms)
        // Don't check isPlaying because video might auto-pause at end
        bool videoEnded = currentPos.inMilliseconds >= (totalDuration.inMilliseconds - 200);
        
        if (videoEnded && !isAutoAdvancing) {
                    // Set flag immediately to prevent multiple triggers
                    isAutoAdvancing = true;
                    
                    // Pause the video if still playing
                  if (controller.value.isPlaying) {
                    controller.pause();
                  }
                  
                  // Mark lesson as complete (automatic completion when video finishes)
                  if (courseDetailModel != null && 
                      currentSection < courseDetailModel!.sections.length &&
                      currentVideoInSection < courseDetailModel!.sections[currentSection].items.length &&
                      courseDetailModel!.sections[currentSection].items[currentVideoInSection].isVideo) {
                    final currentItem = courseDetailModel!.sections[currentSection].items[currentVideoInSection];
                    
                    // Mark as complete in UI first (before backend call)
                    if (!currentItem.isComplete) {
                      currentItem.isComplete = true;
                      // Recalculate totalCompletedLesson to include this new completion
                      totalCompletedLesson = 0;
                      for (var section in courseDetailModel!.sections) {
                        for (var item in section.items) {
                          if (item.isComplete && 
                              !item.itemName.toLowerCase().contains('sample')) {
                            totalCompletedLesson++;
                          }
                        }
                      }
                    }
                    
                    // Force immediate UI update to show green checkmark FIRST
                    // Use addPostFrameCallback to safely call notifyListeners
                    WidgetsBinding.instance.addPostFrameCallback((_) {
                      try {
                        notifyListeners();
                      } catch (e) {
                        debugPrint("Error in notifyListeners (video end): $e");
                      }
                    });
                    
                    // Send completion to backend (async, don't wait)
                    if (currentItem.lessonId != null) {
                      onCompleteLesson(lessonId: currentItem.lessonId!, isVideoType: true);
                    }
                    
                    // Show auto-advance overlay if not the last item (with 3-second timer)
                    if (!isLastItem()) {
                      // Use stored context if available
                      BuildContext? ctxToUse = _storedContext;
                      // Start auto-advance immediately (no delay needed)
                      if (ctxToUse != null) {
                        startAutoAdvance(ctxToUse);
                      }
                    } else {
                      // If it's the last item, exit fullscreen if active
                      if (isFullScreen) {
                        isFullScreen = false;
                        SystemChrome.setEnabledSystemUIMode(SystemUiMode.manual, overlays: SystemUiOverlay.values);
                        SystemChrome.setPreferredOrientations([DeviceOrientation.portraitUp]);
                      }
                      // Reset auto-advancing flag since we're not showing overlay
                      isAutoAdvancing = false;
                      // Use addPostFrameCallback to safely call notifyListeners
                      WidgetsBinding.instance.addPostFrameCallback((_) {
                        try {
                          notifyListeners();
                        } catch (e) {
                          debugPrint("Error in notifyListeners (last item): $e");
                        }
                      });
                    }
                  } else {
                    // If not a video item, reset flag
                    isAutoAdvancing = false;
                  }
                }
              }
    } catch (e) {
      debugPrint("Error in _videoListener: $e");
    }
    
    // Only notify listeners for significant updates (throttled)
    // Don't notify on every frame to reduce rebuilds - only on throttled intervals
  }

  Future<void> gotoNextItem({required BuildContext context}) async {
    if (courseDetailModel == null) return;
    
    // Validate indices
    if (currentSection >= courseDetailModel!.sections.length) return;
    if (currentVideoInSection >= courseDetailModel!.sections[currentSection].items.length) return;
    
    final item = courseDetailModel!.sections[currentSection].items[currentVideoInSection];
    
    // Check if item is accessible
    if (!item.isAccessible && item.isVideo) {
      Utils.showSnackbarMessage(message: "This lesson requires purchase. Please buy the course to access it.");
      return;
    }
    
    // Check item type and handle accordingly
    if (item.isVideo) {
      isArticle = false;
      isQuiz = false;
      isVideo = true;
      
      // Properly dispose existing controller
      if (isControllerInitialize) {
        try {
          await controller.pause();
          await controller.dispose();
        } catch (e) {
          print("Error disposing controller: $e");
        }
        isControllerInitialize = false;
        notifyListeners();
      }
      
      retryCount = 0; // Reset retry count
      int? lessonId = item.lessonId;
      
      // Validate video URL
      if (item.lessonVideoUrl.isEmpty) {
        Utils.showSnackbarMessage(message: "Video URL is not available.");
        return;
      }
      
      // Cancel previous position save timer
      _positionSaveTimer?.cancel();
      
      // Fetch saved position before initializing video
      Duration savedPosition = Duration.zero;
      if (lessonId != null) {
        try {
          var positionResponse = await ApiResponse().onGetLessonPosition(lessonId: lessonId);
          if (positionResponse.statusCode == 200) {
            var positionData = json.decode(positionResponse.body);
            if (positionData['position_seconds'] != null && positionData['position_seconds'] > 0) {
              savedPosition = Duration(seconds: positionData['position_seconds']);
              _lastSavedPosition = positionData['position_seconds'];
            }
          }
        } catch (e) {
          print("Error fetching saved position: $e");
          // Continue with zero position if fetch fails
        }
      }
      
      initializeController(
        context: context, 
        filename: item.lessonVideoUrl, 
        lastPosition: savedPosition,
        lessonId: lessonId,
      );
    } else if (item.isQuiz) {
      // Quiz should be handled first before article check
      // Dispose/pause video controller before going to quiz to stop background audio
      if (isControllerInitialize) {
        try {
          controller.pause();
          await controller.dispose();
          await Future.delayed(Duration(milliseconds: 100)); // Ensure disposal completes
          isControllerInitialize = false;
        } catch (e) {
          print("Error disposing controller for quiz: $e");
        }
      }
      isArticle = false;
      isQuiz = true;
      isVideo = false;
      quiz_title = item.itemName;
    } else if (item.isArticle) {
      // Dispose/pause video controller before going to article to stop background audio
      if (isControllerInitialize) {
        try {
          controller.pause();
          await controller.dispose();
          await Future.delayed(Duration(milliseconds: 100)); // Ensure disposal completes
          isControllerInitialize = false;
        } catch (e) {
          print("Error disposing controller for article: $e");
        }
      }
      // Articles are no longer displayed - skip them and move to next item if possible
      isArticle = false;
      isQuiz = false;
      isVideo = false;
    }
    notifyListeners();
  }

  Future<void> controllerTimer() async {
    isControllerVisible = !isControllerVisible;
    WidgetsBinding.instance.addPostFrameCallback((_) {
      try {
        notifyListeners();
      } catch (e) {
        debugPrint("Error in notifyListeners (controllerTimer): $e");
      }
    });
    Timer(const Duration(seconds: 3), () {
      isControllerVisible = false;
      WidgetsBinding.instance.addPostFrameCallback((_) {
        try {
          notifyListeners();
        } catch (e) {
          debugPrint("Error in notifyListeners (controllerTimer timer): $e");
        }
      });
    });
  }

  // Helper method to ensure volume is set correctly (critical for iOS audio)
  Future<void> _ensureVolume() async {
    if (!isControllerInitialize || !controller.value.isInitialized) return;
    try {
      // CRITICAL for iOS: Set volume explicitly multiple times to ensure it sticks
      // CRITICAL: Always ensure isMute is false when we want audio
      if (!isMute) {
        // Set volume multiple times (iOS sometimes ignores the first call)
        await controller.setVolume(1.0); // Full volume (0.0 to 1.0)
        await Future.delayed(Duration(milliseconds: 50));
        await controller.setVolume(1.0); // Set again to ensure it sticks
        await Future.delayed(Duration(milliseconds: 50));
        await controller.setVolume(1.0); // One more time for iOS
        
        // Verify volume was set
        if (controller.value.volume < 0.9) {
          await controller.setVolume(1.0);
          debugPrint("Volume was low, reset to 1.0");
        }
        // CRITICAL: Ensure isMute flag matches actual volume
        if (controller.value.volume > 0.5) {
          isMute = false;
        }
      } else {
        await controller.setVolume(0.0); // Muted
      }
      debugPrint("Volume set to: ${controller.value.volume}, isMute: $isMute");
    } catch (e) {
      debugPrint("Error setting volume: $e");
    }
  }

  // Play video with volume check (CRITICAL for iOS audio)
  Future<void> playVideo() async {
    if (!isControllerInitialize || !controller.value.isInitialized) {
      debugPrint("❌ Cannot play: controller not initialized");
      return;
    }
    try {
      debugPrint("=== PLAY VIDEO DEBUG ===");
      debugPrint("Before play - volume: ${controller.value.volume}, isMute: $isMute, isPlaying: ${controller.value.isPlaying}");
      
      // CRITICAL FOR iOS: Ensure isMute is false
      isMute = false;
      
      // CRITICAL FOR iOS: Activate audio session BEFORE playing (iOS requirement)
      // This must be done every time before playing on iOS
      if (defaultTargetPlatform == TargetPlatform.iOS) {
        await _activateAudioSession();
        await Future.delayed(Duration(milliseconds: 300)); // Give audio session more time to activate
      }
      
      // CRITICAL: Set volume BEFORE playing on iOS (multiple times to ensure it sticks)
      await _ensureVolume();
      await Future.delayed(Duration(milliseconds: 200));
      await _ensureVolume(); // Set again
      await Future.delayed(Duration(milliseconds: 150));
      await _ensureVolume(); // One more time
      
      debugPrint("Volume before play: ${controller.value.volume}, isMute: $isMute");
      
      await controller.play();
      
      debugPrint("After play() call - isPlaying: ${controller.value.isPlaying}, volume: ${controller.value.volume}");
      
      // CRITICAL FOR iOS: Reactivate audio session after playing starts
      if (defaultTargetPlatform == TargetPlatform.iOS) {
        await Future.delayed(Duration(milliseconds: 400));
        await _activateAudioSession();
        await _ensureVolume();
        await Future.delayed(Duration(milliseconds: 300));
        await _ensureVolume(); // Set volume again after audio session reactivation
        debugPrint("After audio reactivation - volume: ${controller.value.volume}");
      }
      
      // CRITICAL: Set volume AFTER playing (iOS sometimes resets it during play)
      await Future.delayed(Duration(milliseconds: 600));
      await _ensureVolume();
      debugPrint("After 600ms delay - volume: ${controller.value.volume}");
      
      // One more check after a longer delay
      await Future.delayed(Duration(milliseconds: 600));
      await _ensureVolume();
      debugPrint("After 1200ms delay - volume: ${controller.value.volume}");
      
      debugPrint("=== FINAL STATE ===");
      debugPrint("Video playing: ${controller.value.isPlaying}");
      debugPrint("Volume: ${controller.value.volume}");
      debugPrint("isMute: $isMute");
      debugPrint("Duration: ${controller.value.duration}");
      debugPrint("===================");
    } catch (e) {
      debugPrint("❌ Error playing video: $e");
    }
  }

  Future<void> videoMute() async {
    if (isMute) {
      await controller.setVolume(1.0); // Full volume (0.0 to 1.0 range)
      isMute = false;
      notifyListeners();
    } else {
      await controller.setVolume(0.0);
      isMute = true;
      notifyListeners();
    }
  }

  Future<void> gotoCertificateScreen({required BuildContext context, required String title}) async {
    Navigator.push(context, MaterialPageRoute(builder: (context) => DownloadCertificateScreen(title: title)));
  }

  Future<void> gotoQuizScreen({required BuildContext context}) async {
    // Ensure video is paused and disposed before navigating to quiz to stop background audio
    if (isControllerInitialize) {
      try {
        controller.pause();
        await controller.dispose();
        await Future.delayed(Duration(milliseconds: 200)); // Ensure disposal completes
        isControllerInitialize = false;
        notifyListeners();
      } catch (e) {
        print("Error disposing controller before quiz: $e");
      }
    }
    
    bool isComplete = false;
    if (courseDetailModel != null && courseDetailModel!.sections[currentSection].items[currentVideoInSection].isComplete) {
      isComplete = true;
    }
    Navigator.push(context, MaterialPageRoute(builder: (context) => QuizScreen(isCompleted: isComplete, courseId: courseId, title: quiz_title))).then((isCompleted) async {
      if (isCompleted && courseDetailModel != null) {
        courseDetailModel!.sections[currentSection].items[currentVideoInSection].isComplete = true;
        notifyListeners();
        
        // After quiz completion, navigate to next item (video)
        if (currentVideoInSection < courseDetailModel!.sections[currentSection].items.length - 1) {
          currentVideoInSection++;
        } else if (currentSection < courseDetailModel!.sections.length - 1) {
          currentSection++;
          currentVideoInSection = 0;
        }
        
        // Auto-play next video after quiz completion
        if (courseDetailModel!.sections[currentSection].items[currentVideoInSection].isVideo) {
          await gotoNextItem(context: context);
        } else {
          notifyListeners();
        }
      }
    });
  }

  Future<void> fetchReviewQuestions({required int courseId}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isReviewFetchApiCalling = true;
        notifyListeners();

        print("=== FETCHING REVIEW QUESTIONS ===");
        print("Course ID: $courseId");
        
        var response = await ApiResponse().onFetchReviewQuestions(courseId: courseId);
        
        print("=== REVIEW QUESTIONS API RESPONSE ===");
        print("Status Code: ${response.statusCode}");
        print("Response Body: ${response.body}");
        print("Response Headers: ${response.headers}");
        
        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          print("Parsed JSON: $jsonMap");
          if (jsonMap['success'] == true) {
            reviewQuestionModel = ReviewQuestionModel.fromJson(jsonMap);
            print("Successfully loaded ${reviewQuestionModel?.questions.length ?? 0} review questions");
          } else {
            String errorMsg = jsonMap['error'] ?? jsonMap['message'] ?? 'Unknown error';
            print("Review Questions API returned success=false: $errorMsg");
            Utils.showSnackbarMessage(message: "Failed to load review questions: $errorMsg");
          }
        } else {
          // Handle error response
          try {
            Map<String, dynamic> errorJson = jsonDecode(response.body);
            String errorMsg = errorJson['message'] ?? errorJson['error'] ?? 'Unknown error';
            print("Review Questions API Error (${response.statusCode}): $errorMsg");
            if (response.statusCode == 401) {
              Utils.showSnackbarMessage(message: "Please log in to view review questions");
            } else {
              Utils.showSnackbarMessage(message: "Error: $errorMsg");
            }
          } catch (e) {
            print("Error parsing review response: $e");
            Utils.showSnackbarMessage(message: "Failed to fetch review questions. Status: ${response.statusCode}");
          }
        }
        
        isReviewFetchApiCalling = false;
        notifyListeners();
      } catch (e, stackTrace) {
        isReviewFetchApiCalling = false;
        notifyListeners();
        debugPrint("=== ERROR FETCHING REVIEW QUESTIONS === ");
        debugPrint("Error: $e");
        debugPrint("Stack Trace: $stackTrace");
        Utils.showSnackbarMessage(message: "Failed to fetch review questions: $e");
      }
    }
  }

  Future<bool> submitReviewAnswers({required int courseId, required List<Map<String, dynamic>> answers}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isReviewSubmitApiCalling = true;
        notifyListeners();

        var response = await ApiResponse().onSubmitReviewAnswers(courseId: courseId, answers: answers);
        
        isReviewSubmitApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          return true;
        } else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          Utils.showSnackbarMessage(message: decoded['error'] ?? decoded['message'] ?? "Failed to submit review");
          return false;
        }
      } catch (e) {
        isReviewSubmitApiCalling = false;
        notifyListeners();
        Utils.showSnackbarMessage(message: e.toString());
        return false;
      }
    } else {
      Utils.showSnackbarMessage(message: CommonString.no_internet);
      return false;
    }
  }

  Future<void> checkCourseCompletion({required int courseId}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isCompletionCheckApiCalling = true;
        courseCompletionModel = null;
        notifyListeners();

        var response = await ApiResponse().onCheckCourseCompletion(courseId: courseId);
        
        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          courseCompletionModel = CourseCompletionModel.fromJson(jsonMap);
        }
        
        isCompletionCheckApiCalling = false;
        notifyListeners();
      } catch (e) {
        isCompletionCheckApiCalling = false;
        notifyListeners();
        print("Error checking completion: $e");
      }
    }
  }

  bool isLastItem() {
    if (courseDetailModel == null) return true;
    bool isLastSec = currentSection == courseDetailModel!.sections.length - 1;
    bool isLastVideo = currentVideoInSection == courseDetailModel!.sections[currentSection].items.length - 1;
    return isLastSec && isLastVideo;
  }
  
  // Start timer to save position every 10 seconds
  void _startPositionSaveTimer() {
    _positionSaveTimer?.cancel();
    _positionSaveTimer = Timer.periodic(Duration(seconds: 10), (timer) {
      if (isControllerInitialize && 
          controller.value.isInitialized && 
          _currentLessonId != null &&
          controller.value.position.inSeconds > 5) { // Only save if watched at least 5 seconds
        int currentPos = controller.value.position.inSeconds;
        // Only save if position changed significantly (at least 5 seconds difference)
        if ((currentPos - _lastSavedPosition).abs() >= 5) {
          _savePosition(_currentLessonId!, currentPos);
          _lastSavedPosition = currentPos;
        }
      }
    });
  }
  
  // Save position to backend
  Future<void> _savePosition(int lessonId, int positionSeconds) async {
    try {
      await ApiResponse().onSaveLessonPosition(
        lessonId: lessonId,
        positionSeconds: positionSeconds,
      );
    } catch (e) {
      print("Error saving position: $e");
      // Silent failure - don't interrupt user experience
    }
  }

  void startAutoAdvance(BuildContext context) {
    isAutoAdvancing = true;
    autoAdvanceSeconds = 3;
    autoAdvanceTimer?.cancel();
    autoAdvanceTimer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (autoAdvanceSeconds > 1) {
        autoAdvanceSeconds--;
        WidgetsBinding.instance.addPostFrameCallback((_) {
          try {
            notifyListeners();
          } catch (e) {
            debugPrint("Error in notifyListeners (autoAdvance timer): $e");
          }
        });
      } else {
        cancelAutoAdvance(); // Just to clean up timer
        skipToNextItem(context);
      }
    });
    WidgetsBinding.instance.addPostFrameCallback((_) {
      try {
        notifyListeners();
      } catch (e) {
        debugPrint("Error in notifyListeners (startAutoAdvance): $e");
      }
    });
  }

  void cancelAutoAdvance() {
    isAutoAdvancing = false;
    autoAdvanceTimer?.cancel();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      try {
        notifyListeners();
      } catch (e) {
        debugPrint("Error in notifyListeners (cancelAutoAdvance): $e");
      }
    });
  }

  void skipToNextItem(BuildContext context) {
    cancelAutoAdvance();
    if (courseDetailModel == null) return;
    
    // Exit fullscreen when moving to next item
    if (isFullScreen) {
      isFullScreen = false;
      SystemChrome.setEnabledSystemUIMode(SystemUiMode.manual, overlays: SystemUiOverlay.values);
      SystemChrome.setPreferredOrientations([DeviceOrientation.portraitUp]);
    }
    
    if (currentVideoInSection < courseDetailModel!.sections[currentSection].items.length - 1) {
      currentVideoInSection++;
    } else if (currentSection < courseDetailModel!.sections.length - 1) {
      currentSection++;
      currentVideoInSection = 0;
    }
    
    isControllerInitialize = false;
    gotoNextItem(context: context);
  }
  
  // Custom seek method that preserves playback state during scrubbing
  Future<void> seekToPosition(Duration position) async {
    if (!isControllerInitialize) return;
    
    // Store current playing state before seeking
    bool wasPlaying = controller.value.isPlaying;
    
    // Seek to position
    await controller.seekTo(position);
    
    // Resume playing if it was playing before seeking
    if (wasPlaying) {
      // Small delay to ensure seek completes
      Future.delayed(Duration(milliseconds: 100), () async {
        if (isControllerInitialize && !controller.value.isPlaying) {
          await playVideo();
        }
      });
    }
    
    notifyListeners();
  }
}
