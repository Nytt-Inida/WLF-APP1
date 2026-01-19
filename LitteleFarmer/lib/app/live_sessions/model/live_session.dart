class LiveSession {
  final String title;
  final String description;
  final String imageUrl; // Full URL
  final String courseName; // For API
  final String ageGroup;
  final String classesCount;
  final String price; // Optional if free/included
  final List<String> highlights;
  
  // Additional fields for detailed course information
  final String? aboutCourse; // About the Course section text
  final List<String>? whatYouWillLearn; // What Your Child Will Learn bullet points
  final List<String>? courseHighlights; // Course Highlights bullet points
  final List<CourseInclude>? courseIncludes; // Course Includes items
  final List<Instructor>? instructors; // Instructor information
  final List<String>? relatedSkills; // Related Skills tags

  LiveSession({
    required this.title,
    required this.description,
    required this.imageUrl,
    required this.courseName,
    required this.ageGroup,
    required this.classesCount,
    required this.highlights,
    this.price = "Contact for Price", // Default or specific
    this.aboutCourse,
    this.whatYouWillLearn,
    this.courseHighlights,
    this.courseIncludes,
    this.instructors,
    this.relatedSkills,
  });
}

class CourseInclude {
  final String iconUrl;
  final String text;

  CourseInclude({
    required this.iconUrl,
    required this.text,
  });
}

class Instructor {
  final String name;
  final String role;
  final String description;

  Instructor({
    required this.name,
    required this.role,
    required this.description,
  });
}
