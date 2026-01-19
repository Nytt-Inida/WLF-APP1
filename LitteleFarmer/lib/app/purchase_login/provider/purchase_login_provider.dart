import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';

class PurchaseLoginProvider extends ChangeNotifier {
  Future<void> launchArticleUrl({required BuildContext context}) async {
    final Uri url = Uri.parse("https://welittlefarmers.com/login");
    if (!await launchUrl(url)) {
      throw Exception('Could not launch $url');
    }
  }
}
