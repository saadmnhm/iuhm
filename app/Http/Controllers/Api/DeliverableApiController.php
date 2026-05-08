<?php

namespace App\Http\Controllers\Api;

use App\Models\Deliverable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DeliverableApiController
{
    /**
     * Get all published deliverables
     */
    public function index(Request $request): JsonResponse
    {
        $query = Deliverable::published();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $per_page = $request->get('per_page', 15);
        $deliverables = $query->with('author:id,name,email')->latest('published_at')->paginate($per_page);

        return response()->json([
            'status' => 'success',
            'data' => $deliverables->items(),
            'pagination' => [
                'total' => $deliverables->total(),
                'per_page' => $deliverables->perPage(),
                'current_page' => $deliverables->currentPage(),
                'last_page' => $deliverables->lastPage(),
            ]
        ]);
    }

    /**
     * Get single deliverable by slug
     */
    public function show(string $slug): JsonResponse
    {
        $deliverable = Deliverable::where('slug', $slug)->published()->with('author:id,name,email')->firstOrFail();
        $deliverable->increment('downloads_count');

        return response()->json([
            'status' => 'success',
            'data' => $deliverable
        ]);
    }

    /**
     * Get deliverable by ID
     */
    public function getById(int $id): JsonResponse
    {
        $deliverable = Deliverable::where('id', $id)->published()->with('author:id,name,email')->firstOrFail();
        $deliverable->increment('downloads_count');

        return response()->json([
            'status' => 'success',
            'data' => $deliverable
        ]);
    }

    /**
     * Search deliverables
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255'
        ]);

        $deliverables = Deliverable::published()
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%");
            })
            ->with('author:id,name,email')
            ->latest('published_at')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'count' => $deliverables->count(),
            'data' => $deliverables
        ]);
    }

    /**
     * Get deliverables by category
     */
    public function getByCategory(string $category, Request $request): JsonResponse
    {
        $per_page = $request->get('per_page', 15);
        $deliverables = Deliverable::published()
            ->where('category', $category)
            ->with('author:id,name,email')
            ->latest('published_at')
            ->paginate($per_page);

        return response()->json([
            'status' => 'success',
            'data' => $deliverables->items(),
            'pagination' => [
                'total' => $deliverables->total(),
                'per_page' => $deliverables->perPage(),
                'current_page' => $deliverables->currentPage(),
                'last_page' => $deliverables->lastPage(),
            ]
        ]);
    }

    /**
     * Get deliverables by status
     */
    public function getByStatus(string $status, Request $request): JsonResponse
    {
        $per_page = $request->get('per_page', 15);
        $deliverables = Deliverable::published()
            ->where('status', $status)
            ->with('author:id,name,email')
            ->latest('published_at')
            ->paginate($per_page);

        return response()->json([
            'status' => 'success',
            'data' => $deliverables->items(),
            'pagination' => [
                'total' => $deliverables->total(),
                'per_page' => $deliverables->perPage(),
                'current_page' => $deliverables->currentPage(),
                'last_page' => $deliverables->lastPage(),
            ]
        ]);
    }

    /**
     * Get most downloaded deliverables
     */
    public function popular(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $deliverables = Deliverable::published()
            ->with('author:id,name,email')
            ->orderBy('downloads_count', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'count' => $deliverables->count(),
            'data' => $deliverables
        ]);
    }
}
