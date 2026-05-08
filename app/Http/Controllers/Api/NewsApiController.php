<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NewsApiController
{
    /**
     * Get all published news
     */
    public function index(Request $request): JsonResponse
    {
        $query = News::published();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $per_page = $request->get('per_page', 15);
        $news = $query->with('author:id,name,email')->latest('published_at')->paginate($per_page);

        return response()->json([
            'status' => 'success',
            'data' => $news->items(),
            'pagination' => [
                'total' => $news->total(),
                'per_page' => $news->perPage(),
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
            ]
        ]);
    }

    /**
     * Get single news by slug
     */
    public function show(string $slug): JsonResponse
    {
        $news = News::where('slug', $slug)->published()->with('author:id,name,email')->firstOrFail();
        $news->increment('views_count');

        return response()->json([
            'status' => 'success',
            'data' => $news
        ]);
    }

    /**
     * Get news by ID
     */
    public function getById(int $id): JsonResponse
    {
        $news = News::where('id', $id)->published()->with('author:id,name,email')->firstOrFail();
        $news->increment('views_count');

        return response()->json([
            'status' => 'success',
            'data' => $news
        ]);
    }

    /**
     * Search news
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255'
        ]);

        $news = News::published()
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
            'count' => $news->count(),
            'data' => $news
        ]);
    }

    /**
     * Get news by category
     */
    public function getByCategory(string $category, Request $request): JsonResponse
    {
        $per_page = $request->get('per_page', 15);
        $news = News::published()
            ->where('category', $category)
            ->with('author:id,name,email')
            ->latest('published_at')
            ->paginate($per_page);

        return response()->json([
            'status' => 'success',
            'data' => $news->items(),
            'pagination' => [
                'total' => $news->total(),
                'per_page' => $news->perPage(),
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
            ]
        ]);
    }

    /**
     * Get latest news
     */
    public function latest(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $news = News::published()
            ->with('author:id,name,email')
            ->latest('published_at')
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'count' => $news->count(),
            'data' => $news
        ]);
    }
}
