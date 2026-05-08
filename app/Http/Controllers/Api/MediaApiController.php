<?php

namespace App\Http\Controllers\Api;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MediaApiController
{
    /**
     * Get all public media with pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $category = $request->query('category');
        $type = $request->query('type');
        $search = $request->query('search');

        $query = Media::where('is_public', true);

        if ($category) {
            $query->byCategory($category);
        }

        if ($type) {
            $query->byType($type);
        }

        if ($search) {
            $query->search($search);
        }

        $media = $query->latest()->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $media->items(),
            'pagination' => [
                'total' => $media->total(),
                'per_page' => $media->perPage(),
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
            ],
        ]);
    }

    /**
     * Get media by ID
     */
    public function show($id)
    {
        $media = Media::where('is_public', true)->findOrFail($id);

        // Increment usage count
        $media->increment('usage_count');

        return response()->json([
            'status' => 'success',
            'data' => $media,
        ]);
    }

    /**
     * Get media by ID (including file URL)
     */
    public function getById($id)
    {
        $media = Media::where('is_public', true)->findOrFail($id);

        $media->increment('usage_count');

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $media->id,
                'title' => $media->title,
                'title_ar' => $media->title_ar,
                'description' => $media->description,
                'description_ar' => $media->description_ar,
                'category' => $media->category,
                'tags' => $media->tags,
                'file_url' => asset($media->file_path),
                'file_size' => $media->file_size_formatted,
                'file_type' => $media->file_type,
                'usage_count' => $media->usage_count,
                'created_at' => $media->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Search media
     */
    public function search(Request $request)
    {
        $search = $request->query('q');
        $perPage = $request->query('per_page', 15);

        if (!$search) {
            return response()->json([
                'status' => 'error',
                'message' => 'Search query required',
            ], 400);
        }

        $media = Media::where('is_public', true)
                      ->search($search)
                      ->latest()
                      ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $media->items(),
            'pagination' => [
                'total' => $media->total(),
                'per_page' => $media->perPage(),
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
            ],
        ]);
    }

    /**
     * Get media by category
     */
    public function getByCategory($category, Request $request)
    {
        $perPage = $request->query('per_page', 15);

        $media = Media::where('is_public', true)
                      ->byCategory($category)
                      ->latest()
                      ->paginate($perPage);

        if ($media->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No media found in this category',
                'data' => [],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $media->items(),
            'pagination' => [
                'total' => $media->total(),
                'per_page' => $media->perPage(),
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
            ],
        ]);
    }

    /**
     * Get media by type (mime type)
     */
    public function getByType($type, Request $request)
    {
        $perPage = $request->query('per_page', 15);

        $media = Media::where('is_public', true)
                      ->byType($type)
                      ->latest()
                      ->paginate($perPage);

        if ($media->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No media found of this type',
                'data' => [],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $media->items(),
            'pagination' => [
                'total' => $media->total(),
                'per_page' => $media->perPage(),
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
            ],
        ]);
    }

    /**
     * Get most used media
     */
    public function mostUsed(Request $request)
    {
        $limit = $request->query('limit', 10);
        $perPage = $request->query('per_page', 15);

        $media = Media::where('is_public', true)
                      ->orderBy('usage_count', 'desc')
                      ->limit($limit)
                      ->get();

        return response()->json([
            'status' => 'success',
            'data' => $media,
        ]);
    }

    /**
     * Get latest media
     */
    public function latest(Request $request)
    {
        $limit = $request->query('limit', 10);

        $media = Media::where('is_public', true)
                      ->latest()
                      ->limit($limit)
                      ->get();

        return response()->json([
            'status' => 'success',
            'data' => $media,
        ]);
    }

    /**
     * Get all available categories
     */
    public function categories()
    {
        $categories = Media::where('is_public', true)
                           ->distinct()
                           ->pluck('category')
                           ->filter()
                           ->values();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    /**
     * Get media statistics
     */
    public function stats()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total' => Media::count(),
                'public' => Media::where('is_public', true)->count(),
                'private' => Media::where('is_public', false)->count(),
                'by_category' => Media::where('is_public', true)
                                      ->selectRaw('category, COUNT(*) as count')
                                      ->groupBy('category')
                                      ->pluck('count', 'category'),
                'most_used' => Media::where('is_public', true)
                                    ->orderBy('usage_count', 'desc')
                                    ->limit(5)
                                    ->pluck('title', 'id'),
            ],
        ]);
    }

    /**
     * Download media file
     */
    public function download($id)
    {
        $media = Media::where('is_public', true)->findOrFail($id);

        if (!$media->file_path || !file_exists(public_path($media->file_path))) {
            return response()->json([
                'status' => 'error',
                'message' => 'File not found',
            ], 404);
        }

        $media->increment('usage_count');

        return response()->download(public_path($media->file_path), $media->file_name);
    }
}
