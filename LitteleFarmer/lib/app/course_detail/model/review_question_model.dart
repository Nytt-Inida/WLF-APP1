class ReviewQuestionModel {
  bool success;
  List<ReviewQuestion> questions;
  ReviewStatus status;

  ReviewQuestionModel({
    required this.success,
    required this.questions,
    required this.status,
  });

  factory ReviewQuestionModel.fromJson(Map<String, dynamic> json) {
    return ReviewQuestionModel(
      success: json['success'] ?? false,
      questions: (json['questions'] as List?)
              ?.map((e) => ReviewQuestion.fromJson(e))
              .toList() ??
          [],
      status: ReviewStatus.fromJson(json['status'] ?? {}),
    );
  }
}

class ReviewQuestion {
  int id;
  String question;
  List<String> options;
  List<int>? savedOptions;
  bool isAnswered;

  ReviewQuestion({
    required this.id,
    required this.question,
    required this.options,
    this.savedOptions,
    required this.isAnswered,
  });

  factory ReviewQuestion.fromJson(Map<String, dynamic> json) {
    return ReviewQuestion(
      id: json['id'],
      question: json['question'],
      options: (json['options'] as List?)?.map((e) => e.toString()).toList() ?? [],
      savedOptions: (json['saved_options'] as List?)?.map((e) => e as int).toList(),
      isAnswered: json['is_answered'] ?? false,
    );
  }
}

class ReviewStatus {
  int total;
  int answered;
  bool completed;

  ReviewStatus({
    required this.total,
    required this.answered,
    required this.completed,
  });

  factory ReviewStatus.fromJson(Map<String, dynamic> json) {
    return ReviewStatus(
      total: json['total'] ?? 0,
      answered: json['answered'] ?? 0,
      completed: json['completed'] ?? false,
    );
  }
}
