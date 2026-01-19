class BlogModel {
  final int id;
  final String title;
  final String slug;
  final String? excerpt;
  final String? content;
  final String? featuredImage;
  final String? author;
  final String? publishedAt;
  final int? views;
  final int? readingTime;
  final List<BlogTag>? tags;

  BlogModel({
    required this.id,
    required this.title,
    required this.slug,
    this.excerpt,
    this.content,
    this.featuredImage,
    this.author,
    this.publishedAt,
    this.views,
    this.readingTime,
    this.tags,
  });

  factory BlogModel.fromJson(Map<String, dynamic> json) {
    // Handle published_at - it might be a string or DateTime object
    String? publishedAtStr;
    if (json['published_at'] != null) {
      if (json['published_at'] is String) {
        publishedAtStr = json['published_at'];
      } else {
        // If it's a DateTime object, convert to string
        try {
          publishedAtStr = json['published_at'].toString();
        } catch (e) {
          publishedAtStr = null;
        }
      }
    }
    
    // Handle featured_image_url or featured_image
    String? imageUrl = json['featured_image_url'] ?? json['featured_image'];
    
    return BlogModel(
      id: json['id'] is int ? json['id'] : int.parse(json['id'].toString()),
      title: json['title']?.toString() ?? '',
      slug: json['slug']?.toString() ?? '',
      excerpt: json['excerpt']?.toString(),
      content: json['content']?.toString(),
      featuredImage: imageUrl?.toString(),
      author: json['author']?.toString(),
      publishedAt: publishedAtStr,
      views: json['views'] != null ? (json['views'] is int ? json['views'] : int.tryParse(json['views'].toString())) : null,
      readingTime: json['reading_time'] != null ? (json['reading_time'] is int ? json['reading_time'] : int.tryParse(json['reading_time'].toString())) : null,
      tags: json['tags'] != null
          ? (json['tags'] as List).map((i) => BlogTag.fromJson(i)).toList()
          : [],
    );
  }
}

class BlogTag {
  final int id;
  final String name;
  final String slug;
  final int blogsCount;

  BlogTag({
    required this.id,
    required this.name,
    required this.slug,
    this.blogsCount = 0,
  });

  factory BlogTag.fromJson(Map<String, dynamic> json) {
    return BlogTag(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      blogsCount: json['blogs_count'] ?? 0,
    );
  }
}
