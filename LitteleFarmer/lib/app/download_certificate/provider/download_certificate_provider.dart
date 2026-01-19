import 'dart:io';

import 'package:device_info_plus/device_info_plus.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:little_farmer/utils/SharedPreferencesUtil.dart';
import 'package:little_farmer/utils/common_string.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:path_provider/path_provider.dart';
import 'package:http/http.dart' as http;
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:external_path/external_path.dart';
import 'package:share_plus/share_plus.dart';

class DownloadCertificateProvider extends ChangeNotifier {
  bool isPdfSaved = false;
  final FlutterLocalNotificationsPlugin _flutterLocalNotificationsPlugin = FlutterLocalNotificationsPlugin();
  static const MethodChannel _channel = MethodChannel('littlefarmer.kids.course/download');
  
  Future<void> _initializeNotifications() async {
    const AndroidInitializationSettings initializationSettingsAndroid =
        AndroidInitializationSettings('@mipmap/ic_launcher');
    
    const InitializationSettings initializationSettings = InitializationSettings(
      android: initializationSettingsAndroid,
    );
    
    await _flutterLocalNotificationsPlugin.initialize(
      initializationSettings,
      onDidReceiveNotificationResponse: (NotificationResponse response) {},
    );
  }
  
  Future<void> _showDownloadNotification(String fileName, String filePath) async {
    await _initializeNotifications();
    
    const AndroidNotificationDetails androidPlatformChannelSpecifics =
        AndroidNotificationDetails(
      'download_channel',
      'Certificate Downloads',
      channelDescription: 'Notifications for certificate downloads',
      importance: Importance.high,
      priority: Priority.high,
      showWhen: true,
    );
    
    const NotificationDetails platformChannelSpecifics =
        NotificationDetails(android: androidPlatformChannelSpecifics);
    
    // Update notification message based on Android version
    String notificationMessage;
    if (Platform.isAndroid) {
      final deviceInfo = await DeviceInfoPlugin().androidInfo;
      if (deviceInfo.version.sdkInt >= 29) {
        // Android 10+ - file should be in Downloads folder
        notificationMessage = 'Certificate saved to Downloads folder: $fileName';
      } else {
        notificationMessage = 'Certificate saved to Download folder: $fileName';
      }
    } else {
      notificationMessage = 'Certificate saved successfully: $fileName';
    }
    
    await _flutterLocalNotificationsPlugin.show(
      0,
      'Certificate Downloaded',
      notificationMessage,
      platformChannelSpecifics,
      payload: filePath,
    );
  }
  
  Future<void> savePdf({required String title}) async {
    if (Platform.isAndroid) {
      final deviceInfo = await DeviceInfoPlugin().androidInfo;
      bool isPermissionGranted = false;
      if (deviceInfo.version.sdkInt > 32) {
        isPermissionGranted = await Permission.manageExternalStorage.request().isGranted;
      } else {
        isPermissionGranted = await Permission.storage.request().isGranted;
      }

      if (isPermissionGranted) {
        try {
          isPdfSaved = true;
          notifyListeners();

          final pdf = pw.Document();
          
          // Download certificate background image from backend (matching website)
          final certificateImageUrl = 'https://welittlefarmers.com/assets/img/certificate_background.png';
          print("Downloading certificate background from: $certificateImageUrl");
          
          final imageResponse = await http.get(Uri.parse(certificateImageUrl));
          if (imageResponse.statusCode != 200) {
            throw Exception('Failed to download certificate background image: ${imageResponse.statusCode}');
          }
          
          final imageBytes = imageResponse.bodyBytes;
          final image = pw.MemoryImage(imageBytes);
          
          // Load GreatVibes font for the name (matching website and preview)
          final fontData = await rootBundle.load("fonts/GreatVibes-Regular.ttf");
          final ttf = pw.Font.ttf(fontData);
          
          // Get user's first name (matching website design)
          final userName = SharedPreferencesUtil.getString(SharedPreferencesKey.name);
          if (userName.isEmpty) {
            throw Exception('User name is not available');
          }
          final firstName = userName.trim().split(' ').first;
          if (firstName.isEmpty) {
            throw Exception('Could not extract first name from user name');
          }
          final displayName = firstName[0].toUpperCase() + (firstName.length > 1 ? firstName.substring(1).toLowerCase() : '');
          
          // A4 Landscape dimensions (matching website)
          const contentWidth = 11.69 * 72; // A4 width in points (landscape)
          const contentHeight = 8.27 * 72; // A4 height in points (landscape)
          
          pdf.addPage(
            pw.Page(
              pageFormat: const PdfPageFormat(contentWidth, contentHeight),
              build: (pw.Context context) {
                return pw.Stack(
                  children: [
                    // Background image - full page
                    pw.Positioned.fill(
                      child: pw.Image(image, fit: pw.BoxFit.cover),
                    ),
                    // User's first name - centered, large elegant font (matching website and preview)
                    // Preview uses Positioned.fill with Center for perfect centering - name is above "I am a farmer" text
                    // Website uses top: 200px, but in PDF points and accounting for the certificate layout
                    // A4 Landscape height: 8.27 * 72 = 595.44 points, center is ~298 points
                    // Position name in upper center area, above "I am a farmer" text (reduce top to move up)
                    pw.Positioned(
                      top: 250, // Adjusted to position name correctly above "I am a farmer" text, matching preview center
                      left: 50, // Matching website left: 50px margin
                      right: 50, // Matching website right: 50px margin
                      child: pw.Center(
                        child: pw.Text(
                          displayName,
                          style: pw.TextStyle(
                            font: ttf, // Use GreatVibes font (matching website and preview)
                            fontSize: 74, // Matching website font size (74pt)
                            color: PdfColor.fromHex('#383630'), // Matching website color #383630
                            fontWeight: pw.FontWeight.normal,
                            fontStyle: pw.FontStyle.normal,
                            letterSpacing: 1.0, // Matching preview
                          ),
                          textAlign: pw.TextAlign.center,
                        ),
                      ),
                    ),
                  ],
                );
              },
            ),
          );

          // Use proper Android Download directory - save to device's Downloads folder
          Directory? downloadDir;
          if (Platform.isAndroid) {
            final deviceInfo = await DeviceInfoPlugin().androidInfo;
            try {
              // For Android 10+ (API 29+), use external_path which handles scoped storage via MediaStore
              if (deviceInfo.version.sdkInt >= 29) {
                // Try Download (singular) first - this is more reliable on Android 10+
                try {
                  final downloadPath = await ExternalPath.getExternalStoragePublicDirectory(
                    'Download'
                  );
                  downloadDir = Directory(downloadPath);
                  print("Attempting to save to Download folder (Android 10+): $downloadPath");
                  
                  // The external_path package handles MediaStore integration automatically
                  // We just need to ensure the directory path is correct
                  if (!await downloadDir.exists()) {
                    // Try to create, but this might fail on Android 10+ due to scoped storage
                    try {
                      await downloadDir.create(recursive: true);
                      print("Created Download directory: $downloadPath");
                    } catch (createError) {
                      // Directory might already exist via MediaStore, continue
                      print("Directory creation note: $createError (may already exist via MediaStore)");
                    }
                  }
                  
                  // Verify we can write by attempting to create a test file
                  final testFile = File("${downloadDir.path}/.test_write");
                  try {
                    await testFile.writeAsString('test');
                    await testFile.delete();
                    print("Write access verified for: $downloadPath");
                  } catch (writeError) {
                    print("Write test failed, but continuing (MediaStore may handle it): $writeError");
                    // Continue anyway - external_path should handle MediaStore
                  }
                } catch (e) {
                  print("Download directory failed: $e");
                  // Fallback: use external storage directory
                  final externalDir = await getExternalStorageDirectory();
                  if (externalDir != null) {
                    downloadDir = externalDir;
                    print("Using external storage directory as fallback: ${externalDir.path}");
                  } else {
                    throw Exception('Could not access any storage directory');
                  }
                }
              } else {
                // For Android 9 and below, use traditional path
                final externalDir = await getExternalStorageDirectory();
                if (externalDir != null) {
                  String storageRoot = externalDir.path;
                  if (storageRoot.contains('/Android')) {
                    storageRoot = storageRoot.split('/Android')[0];
                  }
                  final downloadsPath = '$storageRoot/Download';
                  downloadDir = Directory(downloadsPath);
                  if (!await downloadDir.exists()) {
                    await downloadDir.create(recursive: true);
                  }
                  print("Using traditional Download path (Android <10): $downloadsPath");
                } else {
                  downloadDir = await getApplicationDocumentsDirectory();
                }
              }
            } catch (e) {
              print("Error accessing Download directory: $e");
              // Final fallback to app documents
              downloadDir = await getApplicationDocumentsDirectory();
              print("Using app documents directory as final fallback: ${downloadDir.path}");
            }
          } else {
            // iOS: Use app documents directory
            downloadDir = await getApplicationDocumentsDirectory();
          }
          
          print("Final download directory: ${downloadDir.path}");
          
          // Sanitize filename (remove invalid characters)
          final sanitizedTitle = title.replaceAll(RegExp(r'[<>:"/\\|?*]'), '_');
          final fileName = "$sanitizedTitle ${Constant.certificateFileName}";
          final file = File("${downloadDir.path}/$fileName");
          
          if (await file.exists()) {
            await file.delete();
          }
          
          final pdfBytes = await pdf.save();
          
          // For Android, use platform channel to save directly to Downloads via MediaStore
          if (Platform.isAndroid) {
            try {
              // Save to temp location first
              final tempDir = await getTemporaryDirectory();
              final tempFile = File("${tempDir.path}/$fileName");
              await tempFile.writeAsBytes(pdfBytes);
              
              print("PDF saved to temp location: ${tempFile.path}");
              
              // Use platform channel to save to Downloads folder via MediaStore
              final result = await _channel.invokeMethod<String>('saveFileToDownloads', {
                'filePath': tempFile.path,
                'fileName': fileName,
              });
              
              if (result != null && result.isNotEmpty) {
                print("Certificate saved to Downloads via MediaStore: $result");
                await _showDownloadNotification(fileName, 'Downloads folder');
                Utils.showSnackbarMessage(message: "Certificate saved to Downloads folder");
                
                // Clean up temp file
                try {
                  if (await tempFile.exists()) {
                    await tempFile.delete();
                  }
                } catch (e) {
                  print("Error cleaning temp file: $e");
                }
              } else {
                throw Exception('Failed to save via MediaStore');
              }
            } catch (platformError) {
              print("Platform channel error: $platformError");
              // Fallback to direct save
              try {
                await file.writeAsBytes(pdfBytes);
                print("Certificate saved using fallback method: ${file.path}");
                await _showDownloadNotification(fileName, file.path);
                
                final isInDownloads = downloadDir.path.contains('Download');
                final message = isInDownloads 
                    ? "Certificate saved to Downloads folder"
                    : "${CommonString.pdf_save_successfully} ${file.path}";
                Utils.showSnackbarMessage(message: message);
              } catch (fallbackError) {
                print("Fallback save also failed: $fallbackError");
                // Last resort: use share_plus
                final tempDir = await getTemporaryDirectory();
                final tempFile = File("${tempDir.path}/$fileName");
                await tempFile.writeAsBytes(pdfBytes);
                await Share.shareXFiles(
                  [XFile(tempFile.path, mimeType: 'application/pdf', name: fileName)],
                  text: 'Save certificate to Downloads',
                  subject: fileName,
                );
                Utils.showSnackbarMessage(message: "Please save the certificate from the share dialog");
              }
            }
          } else {
            // iOS: Direct save
            await file.writeAsBytes(pdfBytes);
            print("Certificate saved successfully to: ${file.path}");
            await _showDownloadNotification(fileName, file.path);
            Utils.showSnackbarMessage(message: CommonString.pdf_save_successfully);
          }
          
          isPdfSaved = false;
          notifyListeners();
        } catch (e, stackTrace) {
          print("Error saving certificate: $e");
          print("Stack trace: $stackTrace");
          Utils.showSnackbarMessage(message: "${CommonString.failed_save_pdf}: $e");
          isPdfSaved = false;
          notifyListeners();
        }
      } else {
        if (deviceInfo.version.sdkInt > 32) {
          Utils.showSnackbarMessage(message: CommonString.allow_permission);
          await Permission.manageExternalStorage.request();
          final permissionStatus = await Permission.manageExternalStorage.status;
          if (permissionStatus.isPermanentlyDenied) {
            await openAppSettings();
          }
        } else {
          await Permission.storage.request();
          Utils.showSnackbarMessage(message: CommonString.allow_permission);
          final permissionStatus = await Permission.storage.status;
          if (permissionStatus.isPermanentlyDenied) {
            await openAppSettings();
          }
        }
      }
    } else {
      bool isPermissionGranted = await Permission.storage.request().isGranted;
      if (isPermissionGranted) {
        try {
          isPdfSaved = true;
          notifyListeners();

          final pdf = pw.Document();
          
          // Download certificate background image from backend (matching website)
          final certificateImageUrl = 'https://welittlefarmers.com/assets/img/certificate_background.png';
          print("Downloading certificate background from: $certificateImageUrl");
          
          final imageResponse = await http.get(Uri.parse(certificateImageUrl));
          if (imageResponse.statusCode != 200) {
            throw Exception('Failed to download certificate background image: ${imageResponse.statusCode}');
          }
          
          final imageBytes = imageResponse.bodyBytes;
          final image = pw.MemoryImage(imageBytes);
          
          // Load GreatVibes font for the name (matching website and preview)
          final fontData = await rootBundle.load("fonts/GreatVibes-Regular.ttf");
          final ttf = pw.Font.ttf(fontData);
          
          // Get user's first name (matching website design)
          final userName = SharedPreferencesUtil.getString(SharedPreferencesKey.name);
          if (userName.isEmpty) {
            throw Exception('User name is not available');
          }
          final firstName = userName.trim().split(' ').first;
          if (firstName.isEmpty) {
            throw Exception('Could not extract first name from user name');
          }
          final displayName = firstName[0].toUpperCase() + (firstName.length > 1 ? firstName.substring(1).toLowerCase() : '');
          
          // A4 Landscape dimensions (matching website)
          const contentWidth = 11.69 * 72; // A4 width in points (landscape)
          const contentHeight = 8.27 * 72; // A4 height in points (landscape)
          
          pdf.addPage(
            pw.Page(
              pageFormat: const PdfPageFormat(contentWidth, contentHeight),
              build: (pw.Context context) {
                return pw.Stack(
                  children: [
                    // Background image - full page
                    pw.Positioned.fill(
                      child: pw.Image(image, fit: pw.BoxFit.cover),
                    ),
                    // User's first name - centered, large elegant font (matching website and preview)
                    // Preview uses Positioned.fill with Center for perfect centering - name is above "I am a farmer" text
                    // Website uses top: 200px, but in PDF points and accounting for the certificate layout
                    // A4 Landscape height: 8.27 * 72 = 595.44 points, center is ~298 points
                    // Position name in upper center area, above "I am a farmer" text (reduce top to move up)
                    pw.Positioned(
                      top: 250, // Adjusted to position name correctly above "I am a farmer" text, matching preview center
                      left: 50, // Matching website left: 50px margin
                      right: 50, // Matching website right: 50px margin
                      child: pw.Center(
                        child: pw.Text(
                          displayName,
                          style: pw.TextStyle(
                            font: ttf, // Use GreatVibes font (matching website and preview)
                            fontSize: 74, // Matching website font size (74pt)
                            color: PdfColor.fromHex('#383630'), // Matching website color #383630
                            fontWeight: pw.FontWeight.normal,
                            fontStyle: pw.FontStyle.normal,
                            letterSpacing: 1.0, // Matching preview
                          ),
                          textAlign: pw.TextAlign.center,
                        ),
                      ),
                    ),
                  ],
                );
              },
            ),
          );

          // Use proper iOS Download directory
          final directory = await getApplicationDocumentsDirectory();
          final downloadsDir = Directory('${directory.path}/Download');
          if (!await downloadsDir.exists()) {
            await downloadsDir.create(recursive: true);
          }

          // Sanitize filename (remove invalid characters)
          final sanitizedTitle = title.replaceAll(RegExp(r'[<>:"/\\|?*]'), '_');
          final fileName = "$sanitizedTitle ${Constant.certificateFileName}";
          final file = File("${downloadsDir.path}/$fileName");
          
          if (await file.exists()) {
            await file.delete();
          }
          
          final pdfBytes = await pdf.save();
          await file.writeAsBytes(pdfBytes);
          
          print("Certificate saved successfully to: ${file.path}");
          
          // Show download notification
          await _showDownloadNotification(fileName, file.path);
          
          Utils.showSnackbarMessage(message: "PDF saved successfully in ${downloadsDir.path}");
          isPdfSaved = false;
          notifyListeners();
        } catch (e, stackTrace) {
          print("Error saving certificate: $e");
          print("Stack trace: $stackTrace");
          Utils.showSnackbarMessage(message: "${CommonString.failed_save_pdf}: $e");
          isPdfSaved = false;
          notifyListeners();
        }
      } else {
        Utils.showSnackbarMessage(message: CommonString.allow_permission);
        await Permission.storage.request();
        if (await Permission.storage.status.isDenied) {
          await openAppSettings();
        }
      }
    }
  }
}
