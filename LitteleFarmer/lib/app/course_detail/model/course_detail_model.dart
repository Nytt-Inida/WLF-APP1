import 'package:little_farmer/utils/constant.dart';

class CourseDetailModel {
  final int courseId;
  final String title;
  final String thumbnail;
  final String price;
  final String priceUsd; // USD price for international users
  int isFavorite;
  final bool isPurchase;
  final int paymentStatus;
  final int? pendingCourseId;
  final List<CourseDetail> courseDetails;
  final CourseRatings? ratings;
  final List<CourseReview> reviews;
  final List<RelatedCourse> relatedCourses;
  final List<CourseTag> tags;
  final List<CourseInstructor> instructors;
  final String ageGroup;
  final int numberOfClasses;

  CourseDetailModel({
    required this.courseId,
    required this.title,
    required this.thumbnail,
    required this.price,
    this.priceUsd = '29', // Default USD price
    required this.isFavorite,
    required this.isPurchase,
    this.paymentStatus = 0,
    this.pendingCourseId,
    required this.courseDetails,
    this.ratings,
    this.reviews = const [],
    this.relatedCourses = const [],
    this.tags = const [],
    this.instructors = const [],
    this.ageGroup = '',
    this.numberOfClasses = 0,
  });

  factory CourseDetailModel.fromJson(Map<String, dynamic> json) {
    // Handle price conversion (can be int or string)
    String priceStr = '0';
    if (json[Constant.price] != null) {
      if (json[Constant.price] is int) {
        priceStr = json[Constant.price].toString();
      } else if (json[Constant.price] is String) {
        priceStr = json[Constant.price];
      } else {
        priceStr = json[Constant.price].toString();
      }
    }

    // Handle priceUsd conversion (USD price for international users)
    String priceUsdStr = '29'; // Default USD price
    if (json['price_usd'] != null) {
      if (json['price_usd'] is int) {
        priceUsdStr = json['price_usd'].toString();
      } else if (json['price_usd'] is String) {
        priceUsdStr = json['price_usd'];
      } else {
        priceUsdStr = json['price_usd'].toString();
      }
    }

    // Handle courseId conversion
    int courseIdValue = 0;
    if (json[Constant.courseId] != null) {
      if (json[Constant.courseId] is int) {
        courseIdValue = json[Constant.courseId];
      } else {
        courseIdValue = int.tryParse(json[Constant.courseId].toString()) ?? 0;
      }
    }

    // Handle isFavorite conversion
    int isFavoriteValue = 0;
    if (json[Constant.isFavorite] != null) {
      if (json[Constant.isFavorite] is int) {
        isFavoriteValue = json[Constant.isFavorite];
      } else if (json[Constant.isFavorite] is bool) {
        isFavoriteValue = json[Constant.isFavorite] ? 1 : 0;
      } else {
        isFavoriteValue = int.tryParse(json[Constant.isFavorite].toString()) ?? 0;
      }
    }

    // Handle isPurchase conversion
    bool isPurchaseValue = false;
    if (json[Constant.isPurchase] != null) {
      if (json[Constant.isPurchase] is bool) {
        isPurchaseValue = json[Constant.isPurchase];
      } else if (json[Constant.isPurchase] is int) {
        isPurchaseValue = json[Constant.isPurchase] == 1;
      } else {
        isPurchaseValue = json[Constant.isPurchase].toString() == '1' || json[Constant.isPurchase].toString().toLowerCase() == 'true';
      }
    }

    // Handle paymentStatus
    int paymentStatusValue = 0;
    if (json[Constant.paymentStatus] != null) {
      if (json[Constant.paymentStatus] is int) {
        paymentStatusValue = json[Constant.paymentStatus];
      } else {
        paymentStatusValue = int.tryParse(json[Constant.paymentStatus].toString()) ?? 0;
      }
    }

    // Handle pendingCourseId
    int? pendingCourseIdValue;
    if (json[Constant.pendingCourseId] != null) {
      if (json[Constant.pendingCourseId] is int) {
        pendingCourseIdValue = json[Constant.pendingCourseId];
      } else {
        pendingCourseIdValue = int.tryParse(json[Constant.pendingCourseId].toString());
      }
    }

    // Handle numberOfClasses
    int numberOfClassesValue = 0;
    if (json['number_of_classes'] != null) {
      if (json['number_of_classes'] is int) {
        numberOfClassesValue = json['number_of_classes'];
      } else {
        numberOfClassesValue = int.tryParse(json['number_of_classes'].toString()) ?? 0;
      }
    }

    return CourseDetailModel(
      courseId: courseIdValue,
      title: json[Constant.title]?.toString() ?? '',
      thumbnail: json[Constant.thumbnail]?.toString() ?? '',
      price: priceStr,
      priceUsd: priceUsdStr,
      isFavorite: isFavoriteValue,
      isPurchase: isPurchaseValue,
      paymentStatus: paymentStatusValue,
      pendingCourseId: pendingCourseIdValue,
      courseDetails: (json[Constant.details] as List?)?.map((i) => CourseDetail.fromJson(i)).toList() ?? [],
      ratings: json['ratings'] != null ? CourseRatings.fromJson(json['ratings']) : null,
      reviews: json['reviews'] != null
          ? (json['reviews'] as List).map((i) => CourseReview.fromJson(i)).toList()
          : [],
      relatedCourses: json['related_courses'] != null
          ? (json['related_courses'] as List).map((i) => RelatedCourse.fromJson(i)).toList()
          : [],
      tags: json['tags'] != null
          ? (json['tags'] as List).map((i) => CourseTag.fromJson(i)).toList()
          : [],
      instructors: json['instructors'] != null
          ? (json['instructors'] as List).map((i) => CourseInstructor.fromJson(i)).toList()
          : [],
      ageGroup: json['age_group']?.toString() ?? '',
      numberOfClasses: numberOfClassesValue,
    );
  }
}

class CourseRatings {
  final double average;
  final int total;
  final Map<int, int> distribution;

  CourseRatings({
    required this.average,
    required this.total,
    required this.distribution,
  });

  factory CourseRatings.fromJson(Map<String, dynamic> json) {
    // Handle average conversion
    double averageValue = 0.0;
    if (json['average'] != null) {
      if (json['average'] is double) {
        averageValue = json['average'];
      } else if (json['average'] is int) {
        averageValue = (json['average'] as int).toDouble();
      } else {
        averageValue = double.tryParse(json['average'].toString()) ?? 0.0;
      }
    }

    // Handle total conversion
    int totalValue = 0;
    if (json['total'] != null) {
      if (json['total'] is int) {
        totalValue = json['total'];
      } else {
        totalValue = int.tryParse(json['total'].toString()) ?? 0;
      }
    }

    // Handle distribution conversion
    Map<int, int> distributionMap = {};
    if (json['distribution'] != null && json['distribution'] is Map) {
      (json['distribution'] as Map).forEach((key, value) {
        int keyInt = key is int ? key : (int.tryParse(key.toString()) ?? 0);
        int valueInt = value is int ? value : (int.tryParse(value.toString()) ?? 0);
        distributionMap[keyInt] = valueInt;
      });
    }

    return CourseRatings(
      average: averageValue,
      total: totalValue,
      distribution: distributionMap,
    );
  }
}

class CourseReview {
  final int id;
  final String userName;
  final int rating;
  final String message;
  final String createdAt;

  CourseReview({
    required this.id,
    required this.userName,
    required this.rating,
    required this.message,
    required this.createdAt,
  });

  factory CourseReview.fromJson(Map<String, dynamic> json) {
    // Handle id conversion
    int idValue = 0;
    if (json['id'] != null) {
      if (json['id'] is int) {
        idValue = json['id'];
      } else {
        idValue = int.tryParse(json['id'].toString()) ?? 0;
      }
    }

    // Handle rating conversion
    int ratingValue = 0;
    if (json['rating'] != null) {
      if (json['rating'] is int) {
        ratingValue = json['rating'];
      } else {
        ratingValue = int.tryParse(json['rating'].toString()) ?? 0;
      }
    }

    return CourseReview(
      id: idValue,
      userName: json['user_name']?.toString() ?? 'Anonymous',
      rating: ratingValue,
      message: json['message']?.toString() ?? '',
      createdAt: json['created_at']?.toString() ?? '',
    );
  }
}

class RelatedCourse {
  final int id;
  final String title;
  final String thumbnail;
  final String price;
  final String ageGroup;
  final int isFavorite;

  RelatedCourse({
    required this.id,
    required this.title,
    required this.thumbnail,
    required this.price,
    required this.ageGroup,
    required this.isFavorite,
  });

  factory RelatedCourse.fromJson(Map<String, dynamic> json) {
    // Handle id conversion
    int idValue = 0;
    if (json['id'] != null) {
      if (json['id'] is int) {
        idValue = json['id'];
      } else {
        idValue = int.tryParse(json['id'].toString()) ?? 0;
      }
    }

    // Handle price conversion
    String priceStr = '0';
    if (json['price'] != null) {
      if (json['price'] is int) {
        priceStr = json['price'].toString();
      } else if (json['price'] is String) {
        priceStr = json['price'];
      } else {
        priceStr = json['price'].toString();
      }
    }

    // Handle isFavorite conversion
    int isFavoriteValue = 0;
    if (json['isFavourite'] != null) {
      if (json['isFavourite'] is int) {
        isFavoriteValue = json['isFavourite'];
      } else if (json['isFavourite'] is bool) {
        isFavoriteValue = json['isFavourite'] ? 1 : 0;
      } else {
        isFavoriteValue = int.tryParse(json['isFavourite'].toString()) ?? 0;
      }
    }

    return RelatedCourse(
      id: idValue,
      title: json['title']?.toString() ?? '',
      thumbnail: json['thumbnail']?.toString() ?? '',
      price: priceStr,
      ageGroup: json['age_group']?.toString() ?? '',
      isFavorite: isFavoriteValue,
    );
  }
}

class CourseTag {
  final String name;
  final String slug;

  CourseTag({
    required this.name,
    required this.slug,
  });

  factory CourseTag.fromJson(Map<String, dynamic> json) {
    return CourseTag(
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
    );
  }
}

class CourseInstructor {
  final String name;
  final String role;
  final String description;

  CourseInstructor({
    required this.name,
    required this.role,
    required this.description,
  });

  factory CourseInstructor.fromJson(Map<String, dynamic> json) {
    return CourseInstructor(
      name: json['name'] ?? '',
      role: json['role'] ?? '',
      description: json['description'] ?? '',
    );
  }
}

class CourseDetail {
  final int id;
  final String title;
  final String chapterTitle;
  final String description;
  final String videoUrl;
  final List<CourseLesson> courseLessons;

  CourseDetail({
    required this.id,
    required this.title,
    required this.chapterTitle,
    required this.description,
    required this.videoUrl,
    required this.courseLessons,
  });

  factory CourseDetail.fromJson(Map<String, dynamic> json) {
    // Handle id conversion
    int idValue = 0;
    if (json[Constant.id] != null) {
      if (json[Constant.id] is int) {
        idValue = json[Constant.id];
      } else {
        idValue = int.tryParse(json[Constant.id].toString()) ?? 0;
      }
    }

    return CourseDetail(
      id: idValue,
      title: json[Constant.title]?.toString() ?? '',
      chapterTitle: json[Constant.chapterTitle]?.toString() ?? '',
      description: json[Constant.description]?.toString() ?? '',
      videoUrl: json[Constant.videoUrl]?.toString() ?? '',
      courseLessons: (json[Constant.lessons] as List?)?.map((i) => CourseLesson.fromJson(i)).toList() ?? [],
    );
  }
}

class CourseLesson {
  final int id;
  final int courseId;
  final int courseDetailId;
  final String title;
  final String duration;

  CourseLesson({
    required this.id,
    required this.courseId,
    required this.courseDetailId,
    required this.title,
    required this.duration,
  });

  factory CourseLesson.fromJson(Map<String, dynamic> json) {
    // Handle id conversions
    int idValue = 0;
    if (json[Constant.id] != null) {
      if (json[Constant.id] is int) {
        idValue = json[Constant.id];
      } else {
        idValue = int.tryParse(json[Constant.id].toString()) ?? 0;
      }
    }

    int courseIdValue = 0;
    if (json[Constant.courseId] != null) {
      if (json[Constant.courseId] is int) {
        courseIdValue = json[Constant.courseId];
      } else {
        courseIdValue = int.tryParse(json[Constant.courseId].toString()) ?? 0;
      }
    }

    int courseDetailIdValue = 0;
    if (json[Constant.courseDetailId] != null) {
      if (json[Constant.courseDetailId] is int) {
        courseDetailIdValue = json[Constant.courseDetailId];
      } else {
        courseDetailIdValue = int.tryParse(json[Constant.courseDetailId].toString()) ?? 0;
      }
    }

    return CourseLesson(
      id: idValue,
      courseId: courseIdValue,
      courseDetailId: courseDetailIdValue,
      title: json[Constant.title]?.toString() ?? '',
      duration: json[Constant.duration]?.toString() ?? '',
    );
  }
}
