class PaymentConfig {
  // Security Note: PayPal credentials are handled by the Backend (API).
  // The App only receives an approval URL. No keys are needed here.

  // Symbols
  static const String symbolInr = "₹";
  static const String symbolUsd = "\$";

  // Global Country State (IP Detected)
  static String? detectedCountryCode;

  // TESTING ONLY: Set this to true to force International Flow (PayPal) from India
  static const bool forceInternational = false;

  // Helper method to check if user is from India
  static bool isIndianUser(String country) {
    if (forceInternational) return false; // Force International for testing
    if (country.isEmpty) return false;
    String c = country.trim().toLowerCase();
    return c == 'india' || c == 'in';
  }
}
