<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Blog listing page
    public function index()
    {
        $blogs = Blog::published()
                    ->with('tags')
                    ->orderBy('published_at', 'desc')
                    ->paginate(9); // 9 blogs per page (3 columns x 3 rows)
        
        $allTags = BlogTag::withCount('blogs')
                         ->having('blogs_count', '>', 0)
                         ->get();
        
        return view('blogs.index', compact('blogs', 'allTags'));
    }

    // Single blog detail page
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
                   ->published()
                   ->with('tags')
                   ->firstOrFail();
        
        // Increment view count
        $blog->incrementViews();
        
        // Get recent blogs excluding current
        $recentBlogs = Blog::published()
                          ->where('id', '!=', $blog->id)
                          ->orderBy('published_at', 'desc')
                          ->limit(5)
                          ->get();
        
        $allTags = BlogTag::all();
        
        return view('blogs.show', compact('blog', 'recentBlogs', 'allTags'));
    }

    // Blogs by tag
    public function byTag($slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();
        
        $blogs = $tag->blogs()
                    ->published()
                    ->orderBy('published_at', 'desc')
                    ->paginate(9);
        
        $allTags = BlogTag::all();
        
        return view('blogs.index', compact('blogs', 'allTags', 'tag'));
    }
}