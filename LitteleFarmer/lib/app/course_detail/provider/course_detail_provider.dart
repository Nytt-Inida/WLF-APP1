import 'dart:async';
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter/foundation.dart' show defaultTargetPlatform, TargetPlatform;
import 'package:little_farmer/app/course_detail/model/course_completion_model.dart';
import 'package:little_farmer/app/course_detail/model/course_detail_model.dart';
import 'package:little_farmer/app/course_detail/model/review_question_model.dart';
import 'package:little_farmer/app/course_verify_done/ui/course_verify_done_screen.dart';
import 'package:little_farmer/app/purchase_course_detail/model/purchase_course_detail_model.dart';
import 'package:little_farmer/app/purchase_course_detail/ui/purchase_course_detail_screen.dart';
import 'package:little_farmer/app/purchase_login/ui/purchase_login_screen.dart';
import 'package:little_farmer/app/splash/provider/splash_provider.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:provider/provider.dart';
import 'package:synchronized/synchronized.dart';
import 'package:video_player/video_player.dart';

class CourseDetailProvider extends ChangeNotifier {
  static const MethodChannel _audioChannel = MethodChannel('little_farmer/audio');
  
  VideoPlayerController? controller;
  
  // Activate audio session before playing (CRITICAL for iOS)
  Future<void> activateAudioSession() async {
    if (defaultTargetPlatform == TargetPlatform.iOS) {
      try {
        await _audioChannel.invokeMethod('activateAudioSession');
        debugPrint("Audio session activated successfully");
      } catch (e) {
        debugPrint("Error activating audio session: $e");
      }
    }
  }
  bool isControllerInitialize = false;
  List<String> duration = [];
  Duration _currentPosition = Duration.zero;
  Duration get currentPosition => _currentPosition;
  late StreamController<Duration> streamController;
  bool isMute = false;
  bool isCourseFetchApiCalling = false;
  bool isCourseVerifyApiCalling = false;
  bool isReviewFetchApiCalling = false;
  bool isReviewSubmitApiCalling = false;
  bool isCompletionCheckApiCalling = false;
  CourseDetail? courseDetail;
  CourseDetailModel? courseDetailModel;
  PurchaseCourseDetailModel? purchaseCourseDetailModel; // For sections/lessons
  ReviewQuestionModel? reviewQuestionModel;
  CourseCompletionModel? courseCompletionModel;
  bool isFavoriteApiCalling = false;
  Lock mutex = Lock();
  int retryCount = 0;
  int maxRetries = 3;
  int currentSection = 0;
  int currentVideoInSection = 0;
  bool _isStreamControllerInitialized = false;
  String? detectedCountryCode;

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

  @override
  void dispose() {
    _cleanupController();
    super.dispose();
  }

  Future<void> resetProvider() async {
    _cleanupController();
    _currentPosition = Duration.zero;
    isControllerInitialize = false;
    isCourseFetchApiCalling = false;
    isCourseVerifyApiCalling = false;
    duration = [];
    isMute = false;
    _isFullScreen = false;
    _isControllerVisible = false;
    isFavoriteApiCalling = false;
    purchaseCourseDetailModel = null;
    currentSection = 0;
    currentVideoInSection = 0;
    courseDetail = null;
    courseDetailModel = null;
    reviewQuestionModel = null;
    courseCompletionModel = null;
    retryCount = 0;
  }

  void _cleanupController() {
    if (controller != null) {
      if (controller!.value.isPlaying) {
        controller!.pause();
      }
      controller!.dispose();
      controller = null;
    }
    
    if (_isStreamControllerInitialized) {
      try {
        if (!streamController.isClosed) {
           streamController.close();
        }
        _isStreamControllerInitialized = false;
      } catch (e) {
        debugPrint("Error closing stream controller: $e");
      }
    }
  }

  Future<void> fetchCourseDetail({required BuildContext context, required int courseId}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isCourseFetchApiCalling = true;
        notifyListeners();

        // Determine Country from IP (Parallel fetch)
        if (detectedCountryCode == null) {
          ApiResponse().fetchUserCountryFromIP().then((code) {
             if (code != null) {
               detectedCountryCode = code;
               notifyListeners();
             }
          });
        }

        // First fetch basic course details
        var courseDetailResponse = await ApiResponse().onFetchCourseDetail(courseId: courseId);
        
        if (courseDetailResponse.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(courseDetailResponse.body);
          courseDetailModel = CourseDetailModel.fromJson(jsonMap);

          courseDetail = courseDetailModel?.courseDetails[0];
          
          // Now fetch sections/lessons for preview
          var sectionsResponse = await ApiResponse().onFetchPurchaseCourseDetail(courseId: courseId);
          if (sectionsResponse.statusCode == Constant.response_200) {
            Map<String, dynamic> sectionsJson = jsonDecode(sectionsResponse.body);
            purchaseCourseDetailModel = PurchaseCourseDetailModel.fromJson(sectionsJson);
            
            // Find first accessible lesson and play it
            if (purchaseCourseDetailModel != null && purchaseCourseDetailModel!.sections.isNotEmpty) {
              bool foundFirstLesson = false;
              for (var i = 0; i < purchaseCourseDetailModel!.sections.length; i++) {
                for (var j = 0; j < purchaseCourseDetailModel!.sections[i].items.length; j++) {
                  final item = purchaseCourseDetailModel!.sections[i].items[j];
                  if (item.isVideo && item.isAccessible) {
                    currentSection = i;
                    currentVideoInSection = j;
                    foundFirstLesson = true;
                    // Play first accessible lesson
                    int? lessonId = item.lessonId;
                    initializeController(
                      context: context,
                      filename: item.lessonVideoUrl,
                      lastPosition: Duration.zero,
                      lessonId: lessonId,
                    );
                    break;
                  }
                }
                if (foundFirstLesson) break;
              }
            }
          }
          
          isCourseFetchApiCalling = false;
          notifyListeners();
        } else {
          Map<String, dynamic> decoded = jsonDecode(courseDetailResponse.body);
          isCourseFetchApiCalling = false;
          notifyListeners();
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showSnackbarMessage(message: decoded[Constant.message]);
          } else {
            Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
          }
        }
      } catch (e) {
        isCourseFetchApiCalling = false;
        Utils.showSnackbarMessage(message: e.toString());
        notifyListeners();
      }
    } else {
      isCourseFetchApiCalling = false;
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  Future<void> addRemoveCourseInFavorite({required int courseId, required bool isFavorite}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isFavoriteApiCalling = true;
        notifyListeners();

        var response = await ApiResponse().onAddCourseInFavorite(courseId: courseId, isFavorite: isFavorite);
        isFavoriteApiCalling = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          if (courseDetailModel?.isFavorite == 0) {
            courseDetailModel?.isFavorite = 1;
          } else {
            courseDetailModel?.isFavorite = 0;
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
        isFavoriteApiCalling = false;
        notifyListeners();
        Utils.showSnackbarMessage(message: e.toString());
      }
    } else {
      isFavoriteApiCalling = false;
      notifyListeners();
      Utils.showSnackbarMessage(message: CommonString.no_internet);
    }
  }

  Future<void> initializeController({required BuildContext context, required String filename, required Duration lastPosition, int? lessonId}) async {
    await mutex.synchronized(() async {
      try {
        isControllerInitialize = false;
        _currentPosition = Duration.zero;
        
        String videoUrl = filename;
        
        // If lessonId is provided, fetch signed URL (required for security)
        if (lessonId != null) {
          try {
            var response = await ApiResponse().onGenerateVideoSignedUrl(lessonId: lessonId);
            if (response.statusCode == 200) {
              final data = json.decode(response.body);
              if (data['success'] == true && data['video_url'] != null) {
                videoUrl = data['video_url'];
                // Use last watched position if available
                if (data['last_watched_seconds'] != null && lastPosition == Duration.zero) {
                  lastPosition = Duration(seconds: data['last_watched_seconds']);
                }
              } else {
                Utils.showSnackbarMessage(message: data['message'] ?? "Failed to load video URL");
                return;
              }
            } else {
              final errorData = json.decode(response.body);
              Utils.showSnackbarMessage(message: errorData['message'] ?? "Failed to load video");
              return;
            }
          } catch (e) {
            print("Error fetching signed URL: $e");
            Utils.showSnackbarMessage(message: "Failed to load video. Please try again.");
            return;
          }
        }
        
        // Dispose existing controller if any
        controller?.dispose();
        
        // CRITICAL FOR iOS: Activate audio session BEFORE creating controller
        // AVPlayer needs audio session to be active when it's created to enable audio
        if (defaultTargetPlatform == TargetPlatform.iOS) {
          await activateAudioSession();
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
              mixWithOthers: true,
              allowBackgroundPlayback: false,
            ),
            httpHeaders: {
              'Accept': '*/*',
              'Range': 'bytes=0-',
            },
          );
        }
        
        // Initialize and set up listeners (for both iOS and Android)
        if (controller == null) return;
        controller!.initialize().then((_) async {
          if (controller == null) return;
          duration = controller!.value.duration.toString().split('.');
          
          // CRITICAL FOR iOS: Ensure isMute is false before setting volume
          isMute = false;
          
          // CRITICAL FOR iOS: Activate audio session after initialization
          if (defaultTargetPlatform == TargetPlatform.iOS) {
            await activateAudioSession();
            await Future.delayed(Duration(milliseconds: 200)); // Give audio session time to activate
          }
          
          // CRITICAL: Set initial volume to ensure audio plays (iOS requires explicit volume setting)
          // Set volume multiple times to ensure it sticks on iOS
          await controller!.setVolume(1.0); // Full volume (0.0 to 1.0)
          await Future.delayed(Duration(milliseconds: 100));
          await controller!.setVolume(1.0); // Set again to ensure it sticks
          await Future.delayed(Duration(milliseconds: 100));
          await controller!.setVolume(1.0); // One more time
          
          // Verify volume was set
          if (controller!.value.volume < 0.9) {
            await controller!.setVolume(1.0);
            debugPrint("Volume was low, reset to 1.0");
          }
          
          // CRITICAL FOR iOS: One more audio session activation after volume is set
          if (defaultTargetPlatform == TargetPlatform.iOS) {
            await Future.delayed(Duration(milliseconds: 100));
            await activateAudioSession();
            await controller!.setVolume(1.0); // Set volume again after audio session reactivation
          }
          
          debugPrint("Volume set to: ${controller!.value.volume}, isMute: $isMute");
          
          controller!.seekTo(lastPosition);
          controllerTimer();
          // Don't auto-play - wait for user to click play button
          // controller!.play();
          WidgetsBinding.instance.addPostFrameCallback((_) {
            try {
              notifyListeners();
            } catch (e) {
              debugPrint("Error in notifyListeners (initializeController): $e");
            }
          });

          streamController = StreamController<Duration>();
          _isStreamControllerInitialized = true;
          isControllerInitialize = true;
        }).catchError((error) {
          debugPrint("Video initialization error: $error");
          // Don't crash the app - just show error message
          try {
            if (retryCount < maxRetries) {
              retryCount++;
              Future.delayed(Duration(seconds: 1), () {
                initializeController(context: context, filename: filename, lastPosition: Duration.zero, lessonId: lessonId);
              });
            } else {
              isControllerInitialize = false;
              try {
                Utils.showSnackbarMessage(message: "Failed to load video. Please try again.");
              } catch (e) {
                debugPrint("Error showing snackbar: $e");
              }
            }
          } catch (e) {
            debugPrint("Error handling video error: $e");
          }
        });
        
        // Add listener after controller is created (for both iOS and Android)
        if (controller != null) {
          controller!.addListener(() {
            try {
              if (isControllerInitialize && controller != null && controller!.value.isInitialized) {
                if (_isStreamControllerInitialized && !streamController.isClosed) {
                  streamController.add(controller!.value.position);
                }
                _currentPosition = controller!.value.position;
                if (_currentPosition == controller!.value.duration) {
                  _isFullScreen = false;
                  if (!streamController.isClosed) {
                    streamController.close();
                  }
                  SystemChrome.setEnabledSystemUIMode(SystemUiMode.manual, overlays: SystemUiOverlay.values);
                  SystemChrome.setPreferredOrientations([DeviceOrientation.portraitUp]);
                }
                // Use SchedulerBinding to safely call notifyListeners
                WidgetsBinding.instance.addPostFrameCallback((_) {
                  try {
                    notifyListeners();
                  } catch (e) {
                    debugPrint("Error in notifyListeners: $e");
                  }
                });
              }
            } catch (e) {
              debugPrint("Error in video listener: $e");
            }
          });
        }
      } catch (e) {
        print("Video initialization error: $e");
        if (retryCount < maxRetries) {
          retryCount++;
          initializeController(context: context, filename: filename, lastPosition: Duration.zero, lessonId: lessonId);
        } else {
          Utils.showSnackbarMessage(message: "Failed to load video. Please try again.");
        }
      }
    });
  }

  Future<void> controllerTimer() async {
    isControllerVisible = !isControllerVisible;
    notifyListeners();
    Timer(const Duration(seconds: 3), () {
      isControllerVisible = false;
      notifyListeners();
    });
  }

  Future<void> videoMute() async {
    if (controller == null) return;
    
    if (isMute) {
      await controller!.setVolume(1.0); // Full volume (0.0 to 1.0 range)
      isMute = false;
    } else {
      await controller!.setVolume(0.0);
      isMute = true;
    }
    WidgetsBinding.instance.addPostFrameCallback((_) {
      try {
        notifyListeners();
      } catch (e) {
        debugPrint("Error in notifyListeners (videoMute): $e");
      }
    });
  }

  Future<void> onCourseVerifyApiCall({required BuildContext context, required int courseId}) async {
    bool isPurchase = Provider.of<SplashProvider>(context, listen: false).isPurchase;
    if (isPurchase) {
      Navigator.push(context, MaterialPageRoute(builder: (context) => PurchaseLoginScreen()));
    } else {
      if (await NetUtils.checkNetworkStatus()) {
        try {
          isCourseVerifyApiCalling = true;
          notifyListeners();

          var response = await ApiResponse().onCourseVerify(courseId: courseId);
          isCourseVerifyApiCalling = false;
          notifyListeners();

          if (response.statusCode == Constant.response_200) {
            resetProvider();
            Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => CourseVerifyDoneScreen()));
          } else {
            Map<String, dynamic> decoded = jsonDecode(response.body);
            if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
              Utils.showSnackbarMessage(message: decoded[Constant.message]);
            } else {
              Utils.showSnackbarMessage(message: CommonString.something_went_wrong);
            }
          }
        } catch (e) {
          isCourseVerifyApiCalling = false;
          Utils.showSnackbarMessage(message: e.toString());
          notifyListeners();
        }
      } else {
        isCourseVerifyApiCalling = false;
        notifyListeners();
        Utils.showSnackbarMessage(message: CommonString.no_internet);
      }
    }
  }

  Future<void> gotoPurchaseCourseDetailScreen({required BuildContext context}) async {
    if (courseDetailModel == null) return;
    Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => PurchaseCourseDetailScreen(courseId: courseDetailModel!.courseId, title: courseDetailModel!.title)));
  }

  Future<void> fetchReviewQuestions({required int courseId}) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isReviewFetchApiCalling = true;
        notifyListeners();

        var response = await ApiResponse().onFetchReviewQuestions(courseId: courseId);
        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> jsonMap = jsonDecode(response.body);
          reviewQuestionModel = ReviewQuestionModel.fromJson(jsonMap);
        }
        
        isReviewFetchApiCalling = false;
        notifyListeners();
      } catch (e) {
        isReviewFetchApiCalling = false;
        notifyListeners();
        print("Error fetching reviews: $e");
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
        courseCompletionModel = null; // Reset previous state
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
        debugPrint("Error checking completion: $e");
      }
    }
  }

}
