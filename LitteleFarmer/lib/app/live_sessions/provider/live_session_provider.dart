import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:little_farmer/app/live_sessions/model/live_session.dart';
import 'package:little_farmer/network/api_response.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:little_farmer/utils/net_util.dart';
import 'package:little_farmer/utils/utils.dart';
import 'package:little_farmer/utils/common_string.dart';

class LiveSessionProvider extends ChangeNotifier {
  bool isBooking = false;

  final List<LiveSession> sessions = [
    LiveSession(
      title: "AI in Agriculture for Kids | Smart Farming Live Course",
      description: "Welcome to our exclusive \"AI in Agriculture\" course, where you'll dive into the world of technology-driven farming! Our live sessions are led by industry experts and offer hands-on experience in AI applications for agriculture. Join us to learn how AI can optimize crop production, automate farm operations, and create a sustainable agricultural environment. Open to learners aged 10 and above.",
      imageUrl: "https://welittlefarmers.com/assets/img/agriculture_ai_banner.png",
      courseName: "AI in Agriculture for Kids | Smart Farming Live Course",
      ageGroup: "[Age 10 to 15]",
      classesCount: "Live online classes + replays",
      price: "", // Hidden as per request
      highlights: [
        "Live online classes + on-demand replays (beginner-friendly)",
        "Step-by-step guides on sensor logging & Image AI basics",
        "Downloadable datasets, activity sheets, and starter notebooks",
        "Full lifetime access to course materials and updates",
        "Access on mobile, tablet, or desktop devices",
        "Certificate of Completion (AI in Agriculture)"
      ],
      aboutCourse: "The \"AI in Agriculture course\" introduces children to how artificial intelligence is transforming modern farming. Learners discover how AI makes agriculture smarter, more efficient, and eco-friendly, from crop management to resource optimization.\n\nThrough live expert sessions, interactive lessons, and hands-on demonstrations, students learn how AI helps monitor soil health, predict weather, improve crop growth, automate farming tasks, and support precision farming.\n\nOpen to learners aged 10 and above, this course builds a strong foundation in AI-driven agriculture and sustainable farming practices. By the end, students gain practical knowledge and future-ready skills to understand how technology is shaping the future of farming.",
      whatYouWillLearn: [
        "Understand how AI and machine learning support modern farming.",
        "Work with sensor data (temperature, humidity, soil moisture, light) to spot patterns.",
        "Use Image AI (leaf images) for basic plant health checks and pest/disease flags.",
        "Learn simple data concepts: datasets, labels, training vs. testing, accuracy.",
        "Build mini dashboards to visualize sensor readings and plant images."
      ],
      courseHighlights: [
        "Live online sessions with interactive Q&A and coding walk-throughs (no-code & beginner-friendly code).",
        "Hands-on projects: sensor logs + image sets for plant health exploration.",
        "Ready-to-use datasets & worksheets for practice at home or school.",
        "Starter notebooks (block-based or Python) for Image AI experiments.",
        "Certificate of Completion for the AI in Agriculture course."
      ],
      courseIncludes: [
        CourseInclude(
          iconUrl: "https://welittlefarmers.com/assets/img/icon/video.svg",
          text: "Live online classes + on-demand replays (beginner-friendly)"
        ),
        CourseInclude(
          iconUrl: "https://welittlefarmers.com/assets/img/icon/newspaper.svg",
          text: "Step-by-step guides on sensor logging & Image AI basics"
        ),
        CourseInclude(
          iconUrl: "https://welittlefarmers.com/assets/img/icon/download.svg",
          text: "Downloadable datasets, activity sheets, and starter notebooks"
        ),
        CourseInclude(
          iconUrl: "https://welittlefarmers.com/assets/img/icon/infinity.svg",
          text: "Full lifetime access to course materials and updates"
        ),
        CourseInclude(
          iconUrl: "https://welittlefarmers.com/assets/img/icon/mobile.svg",
          text: "Access on mobile, tablet, or desktop devices"
        ),
        CourseInclude(
          iconUrl: "https://welittlefarmers.com/assets/img/icon/certificate-line.svg",
          text: "Certificate of Completion (AI in Agriculture)"
        ),
      ],
      instructors: [
        Instructor(
          name: "Praveen P",
          role: "Data Scientist",
          description: "Bringing cutting-edge AI solutions to agriculture."
        ),
        Instructor(
          name: "Subarna V",
          role: "Agriculturist",
          description: "Focusing on sustainable and technology-integrated farming."
        ),
        Instructor(
          name: "Mensilla M",
          role: "Food Scientist",
          description: "Bridging the gap between farm produce and food technology."
        ),
      ],
      relatedSkills: [
        "AI in Agriculture",
        "Smart Farming",
        "Sensor Data",
        "Image AI",
        "Computer Vision",
        "Data Visualization",
        "STEM for Kids"
      ]
    ),
    LiveSession(
      title: "Robotics in Farming for Kids | Live Online Course",
      description: "Welcome to our \"Robotics in Agriculture\" course, designed to introduce you to the latest robotic technologies transforming modern farming. Learn how robots can enhance efficiency, automate complex tasks, and support sustainable agricultural practices. Open to students aged 10 and above, this course offers hands-on sessions led by experts in robotics and agriculture.",
      imageUrl: "https://welittlefarmers.com/assets/img/robotics_banner.png",
      courseName: "Robotics in Farming for Kids | Live Online Course",
      ageGroup: "[Age 5 to 15]",
      classesCount: "Live and recorded classes",
      price: "", // Hidden as per request
      highlights: [
        "Live and recorded classes with real-world robotics demonstrations",
        "Illustrated tutorials on robotics concepts and agricultural automation",
        "Downloadable worksheets, build guides, and simulation tools",
        "Full lifetime access to course content and updates",
        "Accessible on mobile, tablet, and desktop devices",
        "Certificate of Completion downloadable as a PDF"
      ],
      aboutCourse: "The \"Robotics in Agriculture course\" introduces students to how robotics and automation are transforming modern farming. Designed for learners aged 10 and above, the course offers hands-on sessions guided by experts in robotics and agriculture.\n\nThrough interactive lessons and practical demonstrations, students learn how farm robots use sensors, mechanical design, and programming to plant seeds, water crops, and assist in harvesting. The course also covers smart farming technologies and agri-bots used in real agricultural tasks.\n\nBy the end of the course, learners gain practical experience by building simple robotic simulations and small farm-robot prototypes, helping them understand how robotics is shaping the future of agriculture.",
      whatYouWillLearn: [
        "Understand how robots work in farming — from soil preparation to harvesting.",
        "Explore robot components like motors, sensors, controllers, and actuators.",
        "Learn how automation and coding improve precision in irrigation and crop care.",
        "Discover real-world agri-bots used for seeding, weeding, and monitoring crops.",
        "Build simple robotic simulations and small-scale farm robot prototypes at home."
      ],
      courseHighlights: [
        "Live interactive sessions with step-by-step guidance and demonstrations.",
        "Hands-on activities to simulate robot behavior in agriculture.",
        "STEM-based lessons connecting robotics, coding, and farming technology.",
        "Downloadable worksheets and build guides for home practice.",
        "Certificate of Completion for Robotics in Agriculture."
      ],
      courseIncludes: [
        CourseInclude(
          iconUrl: "https://welittlefarmers.com/assets/img/icon/video.svg",
          text: "Live and recorded classes with real-world robotics demonstrations"
        ),
        CourseInclude(
          iconUrl: "https://welittlefarmers.com/assets/img/icon/newspaper.svg",
          text: "Illustrated tutorials on robotics concepts and agricultural automation"
        ),
        CourseInclude(
          iconUrl: "https://welittlefarmers.com/assets/img/icon/download.svg",
          text: "Downloadable worksheets, build guides, and simulation tools"
        ),
        CourseInclude(
          iconUrl: "https://welittlefarmers.com/assets/img/icon/infinity.svg",
          text: "Full lifetime access to course content and updates"
        ),
        CourseInclude(
          iconUrl: "https://welittlefarmers.com/assets/img/icon/mobile.svg",
          text: "Accessible on mobile, tablet, and desktop devices"
        ),
        CourseInclude(
          iconUrl: "https://welittlefarmers.com/assets/img/icon/certificate-line.svg",
          text: "Certificate of Completion downloadable as a PDF"
        ),
      ],
      instructors: [
        Instructor(
          name: "Praveen P",
          role: "Robotics Expert",
          description: "Specializing in AI-driven robotics for agricultural applications."
        ),
        Instructor(
          name: "Subarna V",
          role: "Agriculturist",
          description: "Integrating robotics into traditional farming for increased productivity."
        ),
        Instructor(
          name: "Mensilla M",
          role: "Food Scientist",
          description: "Exploring the role of robotics in post-harvest processing and quality control."
        ),
      ],
      relatedSkills: [
        "Robotics",
        "Automation",
        "STEM for Kids",
        "Farming Technology",
        "Programming Basics",
        "Sensors & Motors",
        "AgriBots"
      ]
    ),
  ];

  DateTime getNextThirdSaturday() {
    DateTime now = DateTime.now();
    int year = now.year;
    int month = now.month;

    DateTime thirdSaturday = _findThirdSaturday(year, month);

    if (now.isAfter(thirdSaturday)) {
      month++;
      if (month > 12) {
        month = 1;
        year++;
      }
      thirdSaturday = _findThirdSaturday(year, month);
    }
    return thirdSaturday;
  }

  DateTime _findThirdSaturday(int year, int month) {
    DateTime date = DateTime(year, month, 1);
    int saturdayCount = 0;
    while (date.month == month) {
      if (date.weekday == DateTime.saturday) {
        saturdayCount++;
        if (saturdayCount == 3) {
          return date;
        }
      }
      date = date.add(const Duration(days: 1));
    }
    return date; // Should not reach here
  }

  Future<void> bookSession({
    required String name,
    required String email,
    required String school,
    required String age,
    required String date,
    required String courseName,
    required VoidCallback onSuccess,
  }) async {
    if (await NetUtils.checkNetworkStatus()) {
      try {
        isBooking = true;
        notifyListeners();

        var response = await ApiResponse().onBookLiveSession(
          name: name,
          email: email,
          school: school,
          age: age,
          date: date,
          courseName: courseName.trim(), // Ensure trimmed
        );

        isBooking = false;
        notifyListeners();

        if (response.statusCode == Constant.response_200) {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded['success'] == true) {
            Utils.showAlertDialog(
              message: decoded['message'], 
              title: "Success",
              onOk: onSuccess
            );
          } else {
            Utils.showAlertDialog(message: decoded['message'] ?? CommonString.something_went_wrong, title: "Error");
          }
        } else if (response.statusCode == 409) {
             Map<String, dynamic> decoded = jsonDecode(response.body);
             Utils.showAlertDialog(message: decoded['message'] ?? "You have already enrolled for this course.", title: "Notice");
        } 
        else {
          Map<String, dynamic> decoded = jsonDecode(response.body);
          if (decoded[Constant.message] != null && decoded[Constant.message].isNotEmpty) {
            Utils.showAlertDialog(message: decoded[Constant.message], title: "Error");
          } else {
            Utils.showAlertDialog(message: CommonString.something_went_wrong, title: "Error");
          }
        }
      } catch (e) {
        isBooking = false;
        Utils.showAlertDialog(message: "An error occurred: ${e.toString()}", title: "Error");
        notifyListeners();
      }
    } else {
      Utils.showAlertDialog(message: CommonString.no_internet, title: "No Internet");
    }
  }
}
