import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:little_farmer/app/blog/model/blog_model.dart';
import 'package:little_farmer/network/apis.dart';

class BlogProvider extends ChangeNotifier {
  List<BlogModel> blogs = [];
  List<BlogModel> relatedBlogs = [];
  List<BlogModel> recentBlogs = []; // For home screen
  List<BlogTag> allTags = [];
  bool isLoading = false;
  bool isLoadMore = false;
  bool isRecentBlogsLoading = false;
  bool isTagsLoading = false;
  String? error;
  int currentPage = 1;
  bool hasNextPage = true;
  String? selectedTagSlug; // Currently selected tag filter
  String? selectedTagName; // Name of selected tag for display

  // Fetch all blog tags
  Future<void> fetchAllTags() async {
    isTagsLoading = true;
    error = null;
    WidgetsBinding.instance.addPostFrameCallback((_) {
      try {
        notifyListeners();
      } catch (e) {
        debugPrint("Error in notifyListeners (fetchAllTags): $e");
      }
    });

    try {
      final response = await http.get(
        Uri.parse(Apis.fetchAllBlogTagsUrl),
        headers: {"Accept": "application/json"},
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        
        if (data['success'] == true && data['data'] != null) {
          allTags = (data['data'] as List)
              .map((e) => BlogTag.fromJson(e))
              .toList();
        }
      }
    } catch (e) {
      print("Error fetching blog tags: $e");
    } finally {
      isTagsLoading = false;
      WidgetsBinding.instance.addPostFrameCallback((_) {
        try {
          notifyListeners();
        } catch (e) {
          debugPrint("Error in notifyListeners (fetchAllTags finally): $e");
        }
      });
    }
  }

  // Fetch blogs (pagination supported)
  Future<void> fetchBlogs({bool refresh = false, String? tagSlug}) async {
    if (refresh) {
      currentPage = 1;
      blogs.clear();
      hasNextPage = true;
      selectedTagSlug = tagSlug;
      
      // Update selected tag name
      if (tagSlug != null) {
        final tag = allTags.firstWhere(
          (t) => t.slug == tagSlug,
          orElse: () => BlogTag(id: 0, name: '', slug: tagSlug, blogsCount: 0),
        );
        selectedTagName = tag.name;
      } else {
        selectedTagName = null;
      }
    }

    if (!hasNextPage || (isLoading && !refresh)) return;

    if (currentPage == 1) {
      isLoading = true;
    } else {
      isLoadMore = true;
    }
    WidgetsBinding.instance.addPostFrameCallback((_) {
      try {
        notifyListeners();
      } catch (e) {
        debugPrint("Error in notifyListeners (fetchBlogs): $e");
      }
    });

    try {
      String url;
      if (selectedTagSlug != null) {
        // Fetch blogs by tag
        url = "${Apis.fetchBlogByTagUrl}$selectedTagSlug?page=$currentPage&limit=10";
      } else {
        // Fetch all blogs
        url = "${Apis.fetchBlogUrl}?page=$currentPage&limit=10";
      }
      
      final response = await http.get(
        Uri.parse(url),
        headers: {"Accept": "application/json"},
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        
        // Debug: Print response structure
        print("Blog API Response: ${data}");
        
        // Handle Laravel Pagination Structure
        if (data['success'] == true && data['data'] != null) {
          // Check if data is paginated or a direct array
          if (data['data'] is Map && data['data']['data'] != null) {
            // Paginated response
            final List<dynamic> blogData = data['data']['data']; 
            print("Found ${blogData.length} blogs in paginated response");
            final newBlogs = blogData.map((e) => BlogModel.fromJson(e)).toList();
            blogs.addAll(newBlogs);
            
            // Check if there are more pages (for tag filtering, also check current page vs last page)
            if (data['data']['next_page_url'] != null) {
              currentPage++;
            } else if (data['data']['current_page'] != null && data['data']['last_page'] != null) {
              // Handle Laravel pagination metadata
              int currentPageNum = data['data']['current_page'] ?? 1;
              int lastPageNum = data['data']['last_page'] ?? 1;
              if (currentPageNum < lastPageNum) {
                currentPage++;
              } else {
                hasNextPage = false;
              }
            } else {
              hasNextPage = false;
            }
          } else if (data['data'] is List) {
            // Direct array response (not paginated)
            final List<dynamic> blogData = data['data']; 
            print("Found ${blogData.length} blogs in array response");
            final newBlogs = blogData.map((e) => BlogModel.fromJson(e)).toList();
            blogs.addAll(newBlogs);
            hasNextPage = false;
          } else {
            error = "Unexpected response format";
            print("Error: Unexpected response format - ${data['data']}");
          }
        } else {
          error = "Invalid response: ${data['message'] ?? 'Unknown error'}";
          print("Error: ${error}");
        }
      } else {
        final errorData = json.decode(response.body);
        error = "Failed to load blogs: ${errorData['message'] ?? 'HTTP ${response.statusCode}'}";
        print("HTTP Error ${response.statusCode}: ${error}");
      }
    } catch (e) {
      error = e.toString();
    } finally {
      isLoading = false;
      isLoadMore = false;
      WidgetsBinding.instance.addPostFrameCallback((_) {
        try {
          notifyListeners();
        } catch (e) {
          debugPrint("Error in notifyListeners (fetchBlogs finally): $e");
        }
      });
    }
  }

  // Fetch single blog detail by ID
  Future<BlogModel?> fetchBlogDetail(int id) async {
    isLoading = true;
    error = null;
    WidgetsBinding.instance.addPostFrameCallback((_) {
      try {
        notifyListeners();
      } catch (e) {
        debugPrint("Error in notifyListeners (fetchBlogDetail): $e");
      }
    });

    try {
      final response = await http.get(
        Uri.parse("${Apis.fetchBlogUrl}/$id"),
        headers: {"Accept": "application/json"},
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        
        // Populate related blogs if available
        if (data['related'] != null) {
          relatedBlogs = (data['related'] as List)
              .map((e) => BlogModel.fromJson(e))
              .toList();
        }

        return BlogModel.fromJson(data['data']);
      } else {
        error = "Failed to load blog detail";
      }
    } catch (e) {
      error = e.toString();
    } finally {
      isLoading = false;
      WidgetsBinding.instance.addPostFrameCallback((_) {
        try {
          notifyListeners();
        } catch (e) {
          debugPrint("Error in notifyListeners (fetchBlogDetail finally): $e");
        }
      });
    }
    return null;
  }

  // Fetch recent blogs for home screen (limited to 3-5)
  Future<void> fetchRecentBlogs({int limit = 3}) async {
    isRecentBlogsLoading = true;
    error = null;
    WidgetsBinding.instance.addPostFrameCallback((_) {
      try {
        notifyListeners();
      } catch (e) {
        debugPrint("Error in notifyListeners (fetchRecentBlogs): $e");
      }
    });

    try {
      final response = await http.get(
        Uri.parse("${Apis.fetchBlogUrl}?page=1&limit=$limit"),
        headers: {"Accept": "application/json"},
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print("Recent Blogs API Response: ${data}");
        
        if (data['success'] == true && data['data'] != null) {
          List<dynamic> blogData = [];
          
          // Check if data is paginated or a direct array
          if (data['data'] is Map && data['data']['data'] != null) {
            blogData = data['data']['data'];
          } else if (data['data'] is List) {
            blogData = data['data'];
          }
          
          print("Found ${blogData.length} recent blogs");
          recentBlogs = blogData.map((e) => BlogModel.fromJson(e)).toList();
        } else {
          error = "Invalid response: ${data['message'] ?? 'Unknown error'}";
          recentBlogs.clear();
        }
      } else {
        final errorData = json.decode(response.body);
        error = "Failed to load recent blogs: ${errorData['message'] ?? 'HTTP ${response.statusCode}'}";
        recentBlogs.clear();
      }
    } catch (e) {
      error = e.toString();
      recentBlogs.clear();
    } finally {
      isRecentBlogsLoading = false;
      WidgetsBinding.instance.addPostFrameCallback((_) {
        try {
          notifyListeners();
        } catch (e) {
          debugPrint("Error in notifyListeners (fetchRecentBlogs finally): $e");
        }
      });
    }
  }
}
