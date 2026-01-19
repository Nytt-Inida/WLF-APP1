import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/utils.dart';

class PayPalPaymentScreen extends StatefulWidget {
  final int courseId;
  final String title;
  final String price; // $29
  final String? couponCode;

  const PayPalPaymentScreen({
    Key? key,
    required this.courseId,
    required this.title,
    required this.price,
    this.couponCode,
  }) : super(key: key);

  @override
  State<PayPalPaymentScreen> createState() => _PayPalPaymentScreenState();
}

class _PayPalPaymentScreenState extends State<PayPalPaymentScreen> {
  late final WebViewController _controller;
  bool isLoading = true;
  String? orderId;
  String? approveLink;
  bool _isWebViewInitialized = false;

  @override
  void initState() {
    super.initState();
    _createPayPalOrder();
  }

  /// Step 1: Call Backend to Create Order and Get Approval Link
  Future<void> _createPayPalOrder() async {
    try {
      final response = await ApiResponse().onCreatePayPalOrder(
        courseId: widget.courseId,
        couponCode: widget.couponCode,
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> responseData = jsonDecode(response.body);
        
        if (responseData['success'] == true) {
          setState(() {
            orderId = responseData['order_id'];
            approveLink = responseData['approve_link'];
            isLoading = false;
          });

          if (approveLink != null) {
            _initializeWebView(approveLink!);
          }
        } else {
          Utils.showSnackbarMessage(message: responseData['message'] ?? "Failed to initiate payment");
          Navigator.pop(context);
        }
      } else {
         Utils.showSnackbarMessage(message: "Failed to initiate payment. Server error.");
         Navigator.pop(context);
      }
    } catch (e) {
      Utils.showSnackbarMessage(message: "Error initiating payment: $e");
      Navigator.pop(context);
    }
  }

  /// Step 2: Initialize WebView with Approval Link
  void _initializeWebView(String url) {
     _controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(const Color(0x00000000))
      ..setUserAgent("Mozilla/5.0 (Linux; Android 10; Android SDK built for x86) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36")
      ..setNavigationDelegate(
        NavigationDelegate(
          onPageStarted: (String url) {
             // Optional: Show loading indicator
          },
          onNavigationRequest: (NavigationRequest request) {
            String url = request.url;
            // Check for success/cancel URLs from PayPal
            if (url.contains('return') || url.contains('status=COMPLETED') || url.contains('paypal/return')) {
               _verifyPayment();
               return NavigationDecision.prevent; // Prevent loading the return URL
            } else if (url.contains('cancel') || url.contains('paypal/cancel')) {
              Navigator.pop(context);
              Utils.showSnackbarMessage(message: "Payment Cancelled");
              return NavigationDecision.prevent;
            }
            return NavigationDecision.navigate;
          },
          onPageFinished: (String url) {
              // Loader logic handled by state
          },
          onWebResourceError: (WebResourceError error) {
            print("WebView Error: ${error.description}");
          },
        ),
      )
      ..loadRequest(Uri.parse(url));
      
      // Clear cache to avoid stuck sessions
      _controller.clearCache();
      
      setState(() {
        _isWebViewInitialized = true;
      });
  }
  
  /// Step 3: Verify Payment on Backend
  Future<void> _verifyPayment() async {
    if (orderId == null) return;
    
    // Show blocking loader
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (_) => Center(child: CircularProgressIndicator()),
    );

    try {
      final response = await ApiResponse().onVerifyPayPalPayment(
        courseId: widget.courseId,
        orderId: orderId!,
        couponCode: widget.couponCode,
      );
      
      // Close loader
      Navigator.pop(context); 

       final Map<String, dynamic> responseData = jsonDecode(response.body);
       if (response.statusCode == 200) {
           if (responseData['success'] == true) {
              Utils.showSnackbarMessage(message: "Payment Successful!");
              Navigator.pop(context, true); 
           } else {
              Utils.showSnackbarMessage(message: responseData['message'] ?? "Payment Verification Failed");
           }
       } else {
           // Show actual server error message
           Utils.showSnackbarMessage(message: responseData['message'] ?? "Server validation failed (Code: ${response.statusCode})");
       }
        } catch (e) {
      Navigator.pop(context); // Close loader if still open
      Utils.showSnackbarMessage(message: "Verification Error: $e");
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("PayPal Secure Payment", style: TextStyle(fontFamily: Constant.manrope, color: Colors.black)),
        backgroundColor: Colors.white,
        elevation: 0,
        iconTheme: IconThemeData(color: Colors.black),
      ),
      body: isLoading || !_isWebViewInitialized
          ? Center(child: CircularProgressIndicator(color: CommonColor.bg_button))
          : WebViewWidget(controller: _controller),
    );
  }
}
