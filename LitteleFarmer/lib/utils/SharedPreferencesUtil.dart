import 'package:shared_preferences/shared_preferences.dart';

class SharedPreferencesKey {
  static String isLogin = "isLogin";
  static String token = "token";
  static String email = "email";
  static String name = "name";
  static String id = "id";
  static String schoolName = "schoolName";
  static String country = "country";
  static String referralCode = "referralCode";
  static String age = "age";
  static String profilePhoto = "profilePhoto";
}

class SharedPreferencesUtil {
  static late SharedPreferences pref;

  static Future<void> init() async {
    pref = await SharedPreferences.getInstance();
  }

  static void setInstance(SharedPreferences prefs) {
    pref = prefs;
  }

  static Future<void> clearPreference() async {
    pref.clear();
  }

  static Future<void> setString(String key, String value) async {
    await pref.setString(key, value);
  }

  static String getString(String key) {
    return pref.getString(key) ?? "";
  }

  static Future<void> setInteger(String key, int value) async {
    await pref.setInt(key, value);
  }

  static int getInteger(String key) {
    return pref.getInt(key) ?? 0;
  }

  static Future<void> setBoolean(String key, bool value) async {
    await pref.setBool(key, value);
  }

  static bool getBoolean(String key) {
    return pref.getBool(key) ?? false;
  }
}
