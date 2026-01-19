import 'package:flutter/material.dart';

class MainHomeProvider extends ChangeNotifier {
  late int currentPage;
  late TabController tabController;

  Future<void> changePage(int newPage) async {
    currentPage = newPage;
    // Use addPostFrameCallback to safely call notifyListeners
    WidgetsBinding.instance.addPostFrameCallback((_) {
      try {
        notifyListeners();
      } catch (e) {
        debugPrint("Error in notifyListeners (changePage): $e");
      }
    });
  }
}
