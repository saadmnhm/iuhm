<?php

namespace App\Http\Controllers\Api;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BlogPostApiController
{
    /**
     * Get all published blog posts
     */
    public function index(Request $request): JsonResponse
    {
        $query = BlogPost::published();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $per_page = $request->get('per_page', 15);
        $posts = $query->with('author:id,name,email')->latest('published_at')->paginate($per_page);

        return response()->json([
            'status' => 'success',
            'data' => $posts->items(),
            'pagination' => [
                'total' => $posts->total(),
                'per_page' => $posts->perPage(),
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
            ]
        ]);
    }

    /**
     * Get single blog post by slug
     */
    public function show(string $slug): JsonResponse
    {
        $post = BlogPost::where('slug', $slug)->published()->with('author:id,name,email')->firstOrFail();
        
        // Increment view count
        $post->increment('views_count');

        return response()->json([
            'status' => 'success',
            'data' => $post
        ]);
    }

    /**
     * Get blog post by ID
     */
    public function getById(int $id): JsonResponse
    {
        $post = BlogPost::where('id', $id)->published()->with('author:id,name,email')->firstOrFail();
        $post->increment('views_count');

        return response()->json([
            'status' => 'success',
            'data' => $post
        ]);
    }

    /**
     * Search blog posts
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255'
        ]);

        $posts = BlogPost::published()
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('excerpt', 'like', "%{$request->q}%")
                  ->orWhere('tags', 'like', "%{$request->q}%");
            })
            ->with('author:id,name,email')
            ->latest('published_at')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'count' => $posts->count(),
            'data' => $posts
        ]);
    }

    /**
     * Get posts by category
     */
    public function getByCategory(string $category, Request $request): JsonResponse
    {
        $per_page = $request->get('per_page', 15);
        $posts = BlogPost::published()
            ->where('category', $category)
            ->with('author:id,name,email')
            ->latest('published_at')
            ->paginate($per_page);

        return response()->json([
            'status' => 'success',
            'data' => $posts->items(),
            'pagination' => [
                'total' => $posts->total(),
                'per_page' => $posts->perPage(),
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
            ]
        ]);
    }

    /**
     * Get trending posts
     */
    public function trending(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $posts = BlogPost::published()
            ->with('author:id,name,email')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'count' => $posts->count(),
            'data' => $posts
        ]);
    }
}
