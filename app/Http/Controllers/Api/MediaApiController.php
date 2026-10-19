<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class MediaApiController extends BaseApiController
{
    /**
     * Get all public media with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $query = Media::where('is_public', true);

        $this->applyFilters($query, $request, [
            'category' => 'category',
            'type' => 'file_type',
        ]);

        $this->applySearch($query, $request, ['title', 'description', 'tags']);
        $this->applySort($query, $request, ['created_at', 'usage_count', 'file_size'], 'created_at', 'desc');

        $perPage = $this->getPerPage($request);
        $media = $query->paginate($perPage);

        return $this->paginated($media);
    }

    /**
     * Get media by ID
     */
    public function show(int $id): JsonResponse
    {
        $media = Media::where('is_public', true)->findOrFail($id);
        $media->increment('usage_count');
        $media->refresh();

        return $this->success(new MediaResource($media));
    }

    /**
     * Get media by ID (alias for show)
     */
    public function getById(int $id): JsonResponse
    {
        return $this->show($id);
    }

    /**
     * Search media
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'required|string|min:2|max:255',
        ]);

        $perPage = $this->getPerPage($request);
        $media = Media::where('is_public', true)
            ->search($validated['q'])
            ->latest()
            ->paginate($perPage);

        return $this->paginated($media);
    }

    /**
     * Get media by category
     */
    public function getByCategory(string $category, Request $request): JsonResponse
    {
        $perPage = $this->getPerPage($request);
        $media = Media::where('is_public', true)
            ->byCategory($category)
            ->latest()
            ->paginate($perPage);

        return $this->paginated($media);
    }

    /**
     * Get media by type (mime type)
     */
    public function getByType(string $type, Request $request): JsonResponse
    {
        $perPage = $this->getPerPage($request);
        $media = Media::where('is_public', true)
            ->byType($type)
            ->latest()
            ->paginate($perPage);

        return $this->paginated($media);
    }

    /**
     * Get most used media
     */
    public function mostUsed(Request $request): JsonResponse
    {
        $limit = $this->getLimit($request);
        $media = Media::where('is_public', true)
            ->orderBy('usage_count', 'desc')
            ->limit($limit)
            ->get();

        return $this->success(MediaResource::collection($media));
    }

    /**
     * Get latest media
     */
    public function latest(Request $request): JsonResponse
    {
        $limit = $this->getLimit($request);
        $media = Media::where('is_public', true)
            ->latest()
            ->limit($limit)
            ->get();

        return $this->success(MediaResource::collection($media));
    }

    /**
     * Get all available categories
     */
    public function categories(): JsonResponse
    {
        $categories = Media::where('is_public', true)
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return $this->success($categories);
    }

    /**
     * Get all available types
     */
    public function types(): JsonResponse
    {
        $types = Media::where('is_public', true)
            ->distinct()
            ->pluck('file_type')
            ->filter()
            ->values();

        return $this->success($types);
    }

    /**
     * Get media statistics
     */
    public function stats(): JsonResponse
    {
        return $this->success([
            'total' => Media::count(),
            'public' => Media::where('is_public', true)->count(),
            'private' => Media::where('is_public', false)->count(),
            'by_category' => Media::where('is_public', true)
                ->selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category'),
            'by_type' => Media::where('is_public', true)
                ->selectRaw('file_type, COUNT(*) as count')
                ->groupBy('file_type')
                ->pluck('count', 'file_type'),
            'most_used' => Media::where('is_public', true)
                ->orderBy('usage_count', 'desc')
                ->limit(5)
                ->pluck('title', 'id'),
        ]);
    }

    /**
     * Download media file
     */
    public function download(int $id): JsonResponse
    {
        $media = Media::where('is_public', true)->findOrFail($id);

        if (!$media->file_path || !file_exists(public_path($media->file_path))) {
            return $this->error('File not found', 404);
        }

        $media->increment('usage_count');

        return response()->download(public_path($media->file_path), $media->file_name);
    }
}