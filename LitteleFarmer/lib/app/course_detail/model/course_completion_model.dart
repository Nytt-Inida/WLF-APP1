class CourseCompletionModel {
  bool completed;
  bool lessonsCompleted;
  bool quizzesCompleted;
  bool reviewCompleted;
  bool reviewRequired;
  CompletionStats lessonStats;
  CompletionStats quizStats;
  CompletionStats reviewStats;

  CourseCompletionModel({
    required this.completed,
    required this.lessonsCompleted,
    required this.quizzesCompleted,
    required this.reviewCompleted,
    required this.reviewRequired,
    required this.lessonStats,
    required this.quizStats,
    required this.reviewStats,
  });

  factory CourseCompletionModel.fromJson(Map<String, dynamic> json) {
    return CourseCompletionModel(
      completed: json['completed'] ?? false,
      lessonsCompleted: json['lessons_completed'] ?? false,
      quizzesCompleted: json['quizzes_completed'] ?? false,
      reviewCompleted: json['review_completed'] ?? false,
      reviewRequired: json['review_required'] ?? false,
      lessonStats: CompletionStats.fromJson(json['lesson_stats'] ?? {}),
      quizStats: CompletionStats.fromJson(json['quiz_stats'] ?? {}),
      reviewStats: CompletionStats.fromJson(json['review_stats'] ?? {}),
    );
  }
}

class CompletionStats {
  int totalCount;
  int completedCount;

  CompletionStats({
    required this.totalCount,
    required this.completedCount,
  });

  factory CompletionStats.fromJson(Map<String, dynamic> json) {
    return CompletionStats(
      totalCount: json['total_count'] ?? 0,
      completedCount: json['completed_count'] ?? 0,
    );
  }
}
