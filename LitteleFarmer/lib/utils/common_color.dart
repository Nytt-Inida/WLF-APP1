import 'package:flutter/material.dart';

class CommonColor {
  // -- Website Theme (Warm & Friendly) --
  // Backgrounds
  static const Color bg_main = Color(0xFFFFFDF8); // Website Cream Background
  static const Color white = Color(0xFFFFFFFF);
  
  // Brand Colors
  static const Color primary = Color(0xFFFCB525); // Website Orange (Buttons/Highlights)
  static const Color secondary = Color(0xFF4CAF50); // Leaf Green (Accents/WhatsApp)
  
  // Text Colors
  static const Color text_primary = Color(0xFF1A1A1A); // Almost Black (High Contrast)
  static const Color text_secondary = Color(0xFF4A4A4A); // Dark Grey (Body)
  static const Color text_grey = Color(0xFF9E9E9E); // Helpers

  // UI Elements
  static const Color bg_button = primary; // Orange Buttons
  static const Color et_border_grey = Color(0xFFE0E0E0);
  static const Color grey_light = Color(0xFFF5F5F5);
  static const Color rippleColor = Color(0x1FFCB525); // Orange Ripple

  // Legacy/Functional Colors
  static const Color black = text_primary;
  static const Color grey = text_secondary;
  static const Color red = Color(0xFFE57373);
  static const Color green = secondary;
  static const Color transparent = Colors.transparent;
  static const Color transparentBlack = Color.fromRGBO(15, 15, 15, 0.427);
}