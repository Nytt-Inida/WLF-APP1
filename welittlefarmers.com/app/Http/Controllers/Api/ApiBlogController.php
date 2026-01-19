<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class ApiBlogController extends Controller
{
    /**
     * Get list of published blogs
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10); // Default 10 per page

        $blogs = Blog::published()
                    ->with('tags')
                    ->orderBy('published_at', 'desc')
                    ->paginate($limit);

        // Transform image URLs to be full absolute paths
        $blogs->getCollection()->transform(function ($blog) {
            $blog->featured_image_url = $this->getImageUrl($blog->featured_image);
            return $blog;
        });

        return response()->json([
            'success' => true,
            'data' => $blogs
        ]);
    }

    /**
     * Get single blog details
     */
    public function show($id)
    {
        $blog = Blog::published()
                    ->with('tags')
                    ->find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found'
            ], 404);
        }

        // Increment views
        $blog->incrementViews();

        $blog->featured_image_url = $this->getImageUrl($blog->featured_image);

        // Get related blogs
        $related = Blog::published()
                       ->where('id', '!=', $blog->id)
                       ->limit(3)
                       ->get()
                       ->map(function ($b) {
                           $b->featured_image_url = $this->getImageUrl($b->featured_image);
                           return $b;
                       });

        return response()->json([
            'success' => true,
            'data' => $blog,
            'related' => $related
        ]);
    }

    /**
     * Filter blogs by tag
     */
    public function byTag($tagSlug)
    {
        $tag = BlogTag::where('slug', $tagSlug)->first();

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found'
            ], 404);
        }

        $limit = request()->input('limit', 10);
        $blogs = $tag->blogs()
                     ->published()
                     ->orderBy('published_at', 'desc')
                     ->paginate($limit);
        
        $blogs->getCollection()->transform(function ($blog) {
            $blog->featured_image_url = $this->getImageUrl($blog->featured_image);
            return $blog;
        });

        return response()->json([
            'success' => true,
            'data' => $blogs,
            'tag' => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ]
        ]);
    }

    /**
     * Get all blog tags with count
     */
    public function getAllTags()
    {
        $tags = BlogTag::withCount('blogs')
                     ->having('blogs_count', '>', 0)
                     ->orderBy('name')
                     ->get()
                     ->map(function ($tag) {
                         return [
                             'id' => $tag->id,
                             'name' => $tag->name,
                             'slug' => $tag->slug,
                             'blogs_count' => $tag->blogs_count,
                         ];
                     });

        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }

    /**
     * Helper to get full image URL
     */
    private function getImageUrl($path)
    {
        if (!$path) return null;
        if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
        return asset('storage/' . $path);
    }
}
