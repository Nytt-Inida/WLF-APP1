import 'package:flutter/material.dart';
import 'package:little_farmer/app/main_home/ui/main_home_screen.dart';

class CourseVerifyDoneProvider extends ChangeNotifier {
  Future<void> gotoMainScreen({required BuildContext context}) async {
    Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => const MainHomeScreen()));
  }
}
