class WebsiteImages {
  static const String baseUrl = 'https://welittlefarmers.com';
  static const String assetsBaseUrl = '$baseUrl/assets/img';

  // About Us Page Images
  static String aboutImage03 = '$assetsBaseUrl/about/03.jpg';
  static String aboutImage04 = '$assetsBaseUrl/about/04.jpg';
  static String aboutImage02 = '$assetsBaseUrl/about/02.png';

  // Instructor Images
  static String instructorPraveen = '$assetsBaseUrl/instructor/praveen.jpg';
  static String instructorSubarna = '$assetsBaseUrl/instructor/subarna.jpg';
  static String instructorMensi = '$assetsBaseUrl/instructor/mensi.jpg';
  static String instructorMohaimin = '$assetsBaseUrl/instructor/mohaimin.jpg';
  static String instructorYash = '$assetsBaseUrl/instructor/yash.jpg';
  static String instructorMohsin = '$assetsBaseUrl/instructor/mohsin.jpg';
  static String instructorAravind = '$assetsBaseUrl/instructor/aravind.jpg';

  // Home Page Images
  static String slider01 = '$assetsBaseUrl/slider/01.png';
  static String slider02 = '$assetsBaseUrl/slider/02.png';
  static String slider03 = '$assetsBaseUrl/slider/03.png';
  static String agricultureAiBanner = '$assetsBaseUrl/agriculture_ai_banner.png';
  static String roboticsBanner = '$assetsBaseUrl/robotics_banner.png';
  static String chose01 = '$assetsBaseUrl/chose/01.jpg';
  static String chose02 = '$assetsBaseUrl/chose/02.jpg';
  static String chose03 = '$assetsBaseUrl/chose/03.jpg';
  static String chose04 = '$assetsBaseUrl/chose/04.jpg';
  static String chose05 = '$assetsBaseUrl/chose/05.png';
  
  // Home Page Icons
  static String iconNotepad = '$assetsBaseUrl/icon/notepad.svg';
  static String iconPuzzle = '$assetsBaseUrl/icon/puzzle.svg';
  static String iconManager = '$assetsBaseUrl/icon/manager.svg';
  static String iconShieldCheck = '$assetsBaseUrl/icon/shield-check.svg';
  static String iconCatalog = '$assetsBaseUrl/icon/catalog.svg';

  // Course Images
  static String fullCourseThumbnail = '$assetsBaseUrl/course/full-course-thumbnail.jpg';
  static String aiCourseThumbnail = '$assetsBaseUrl/course/ai-course-thumbnail.jpg';
  static String roboticsCourseThumbnail = '$assetsBaseUrl/course/robotics-course-thumbnail.jpg';

  // Blog Images
  static String defaultBlogImage = '$assetsBaseUrl/blog/default-blog.jpg';

  // Other Common Images
  static String profileImage = '$assetsBaseUrl/profile.png';
  static String paymentQr = '$assetsBaseUrl/payment-qr.jpg';
  static String certificateBackground = '$assetsBaseUrl/certificate_background.png';
  static String contactImage01 = '$assetsBaseUrl/contact/01.jpg';
  static String contactImage02 = '$assetsBaseUrl/contact/02.jpg';
  
  // WhatsApp URL
  static String whatsappUrl = 'https://wa.me/971543202013';
  
  // Logo
  static String headerLogo = '$baseUrl/assets/img/logo/header_logo_one.svg';

  // Helper method to get instructor image by name
  static String? getInstructorImage(String name) {
    switch (name.toLowerCase()) {
      case 'praveen p':
      case 'praveen':
        return instructorPraveen;
      case 'subarna v':
      case 'subarna':
        return instructorSubarna;
      case 'mensilla m':
      case 'mensi':
        return instructorMensi;
      case 'mohaimin kader':
      case 'mohaimin':
        return instructorMohaimin;
      case 'yash chhalotre':
      case 'yash':
        return instructorYash;
      case 'mohsin kader':
      case 'mohsin':
        return instructorMohsin;
      case 'aravind a s':
      case 'aravind':
        return instructorAravind;
      default:
        return null;
    }
  }
}
