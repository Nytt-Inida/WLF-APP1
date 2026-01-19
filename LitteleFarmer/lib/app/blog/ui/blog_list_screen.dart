import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:intl/intl.dart';
import 'package:little_farmer/app/blog/provider/blog_provider.dart';
import 'package:little_farmer/app/blog/ui/blog_detail_screen.dart';
import 'package:little_farmer/utils/common_color.dart';
import 'package:little_farmer/utils/constant.dart';
import 'package:provider/provider.dart';

class BlogListScreen extends StatefulWidget {
  const BlogListScreen({super.key});

  @override
  State<BlogListScreen> createState() => _BlogListScreenState();
}

class _BlogListScreenState extends State<BlogListScreen> {
  final ScrollController _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<BlogProvider>().fetchBlogs(refresh: true);
    });

    _scrollController.addListener(() {
      if (_scrollController.position.pixels ==
          _scrollController.position.maxScrollExtent) {
        context.read<BlogProvider>().fetchBlogs();
      }
    });
  }

  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }

  String _formatDate(String? dateStr) {
    if (dateStr == null) return "Recently";
    try {
      DateTime date = DateTime.parse(dateStr);
      return DateFormat('d MMM, yyyy').format(date);
    } catch (e) {
      return dateStr;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: CommonColor.bg_main,
      extendBodyBehindAppBar: false,
      body: SafeArea(
        child: Column(
          children: [
            Container(
              margin: EdgeInsets.all(10.h),
              child: Row(
                children: [
                  Expanded(
                    child: Text(
                      "Our Blogs",
                      textAlign: TextAlign.center,
                      style: TextStyle(
                          color: CommonColor.black,
                          fontSize: 18.sp,
                          fontWeight: FontWeight.w900,
                          fontFamily: Constant.manrope),
                    ),
                  ),
                ],
              ),
            ),
            Expanded(
              child: Consumer<BlogProvider>(
                builder: (context, provider, child) {
                  return _buildBlogList(provider);
                },
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildBlogList(BlogProvider provider) {
    if (provider.isLoading && provider.blogs.isEmpty) {
      return Center(child: CircularProgressIndicator(color: CommonColor.bg_button));
    }

    if (provider.blogs.isEmpty) {
                    return Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          if (provider.error != null)
                            Padding(
                              padding: EdgeInsets.all(20.w),
                              child: Text(
                                "Error: ${provider.error}",
                                style: TextStyle(
                                  fontFamily: Constant.manrope,
                                  color: Colors.red,
                                  fontSize: 14.sp,
                                ),
                                textAlign: TextAlign.center,
                              ),
                            ),
                          Text(
                            provider.error != null 
                                ? "Failed to load blogs. Please check your connection."
                                : "No blogs found.",
                            style: TextStyle(
                              fontFamily: Constant.manrope,
                              fontSize: 16.sp,
                            ),
                          ),
                          SizedBox(height: 20.h),
                          ElevatedButton(
                            onPressed: () {
                              provider.fetchBlogs(refresh: true, tagSlug: provider.selectedTagSlug);
                            },
                            child: Text("Retry"),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: CommonColor.bg_button,
                            ),
                          ),
                        ],
                      ),
                    );
    }

    return ListView.separated(
                    controller: _scrollController,
                    padding: EdgeInsets.fromLTRB(15.w, 15.h, 15.w, 100.h), // Add bottom padding for navbar
                    itemCount: provider.blogs.length + (provider.isLoadMore ? 1 : 0),
                    separatorBuilder: (context, index) => SizedBox(height: 15.h),
                    itemBuilder: (context, index) {
                      if (index >= provider.blogs.length) {
                        return Padding(
                          padding: EdgeInsets.all(20.h),
                          child: Center(
                            child: CircularProgressIndicator(color: CommonColor.bg_button),
                          ),
                        );
                      }

                      final blog = provider.blogs[index];
                      return GestureDetector(
                        onTap: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => BlogDetailScreen(blogId: blog.id),
                            ),
                          );
                        },
                        child: Container(
                          decoration: BoxDecoration(
                            color: CommonColor.white,
                            borderRadius: BorderRadius.circular(15.r),
                            boxShadow: [
                              BoxShadow(
                                color: Colors.black.withOpacity(0.05),
                                blurRadius: 10,
                                offset: Offset(0, 5),
                              ),
                            ],
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              ClipRRect(
                                borderRadius: BorderRadius.vertical(top: Radius.circular(15.r)),
                                child: CachedNetworkImage(
                                  imageUrl: blog.featuredImage ?? "",
                                  height: 180.h,
                                  width: double.infinity,
                                  fit: BoxFit.cover,
                                  placeholder: (context, url) => Container(
                                    color: Colors.grey[200],
                                    child: Center(
                                        child: Icon(Icons.image,
                                            color: Colors.grey)),
                                  ),
                                  errorWidget: (context, url, error) => Container(
                                    color: Colors.grey[200],
                                    child: Center(
                                        child: Icon(Icons.broken_image,
                                            color: Colors.grey)),
                                  ),
                                ),
                              ),
                              Padding(
                                padding: EdgeInsets.all(15.w),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    SizedBox(height: 10.h),
                                    Text(
                                      blog.title,
                                      style: TextStyle(
                                        color: CommonColor.black,
                                        fontSize: 16.sp,
                                        fontWeight: FontWeight.bold,
                                        fontFamily: Constant.manrope,
                                      ),
                                      maxLines: 2,
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                    SizedBox(height: 8.h),
                                    Row(
                                      children: [
                                        Icon(Icons.calendar_today_outlined, size: 14.sp, color: Colors.grey),
                                        SizedBox(width: 5.w),
                                        Text(
                                          _formatDate(blog.publishedAt),
                                          style: TextStyle(
                                            color: Colors.grey,
                                            fontSize: 12.sp,
                                            fontFamily: Constant.manrope,
                                          ),
                                        ),
                                        Spacer(),
                                        Icon(Icons.remove_red_eye_outlined, size: 14.sp, color: Colors.grey),
                                        SizedBox(width: 5.w),
                                        Text(
                                          "${blog.views ?? 0} views",
                                          style: TextStyle(
                                            color: Colors.grey,
                                            fontSize: 12.sp,
                                            fontFamily: Constant.manrope,
                                          ),
                                        ),
                                      ],
                                    )
                                  ],
                                ),
                              ),
                            ],
                          ),
                        ),
                      );
                    },
                  );
  }
}
