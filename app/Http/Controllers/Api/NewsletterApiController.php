<?php

namespace App\Http\Controllers\Api;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NewsletterApiController
{
    /**
     * Get all published newsletters
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->get('per_page', 15);
        $newsletters = Newsletter::published()
            ->with('author:id,name,email')
            ->latest('published_at')
            ->paginate($per_page);

        return response()->json([
            'status' => 'success',
            'data' => $newsletters->items(),
            'pagination' => [
                'total' => $newsletters->total(),
                'per_page' => $newsletters->perPage(),
                'current_page' => $newsletters->currentPage(),
                'last_page' => $newsletters->lastPage(),
            ]
        ]);
    }

    /**
     * Get single newsletter by slug
     */
    public function show(string $slug): JsonResponse
    {
        $newsletter = Newsletter::where('slug', $slug)->published()->with('author:id,name,email')->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $newsletter
        ]);
    }

    /**
     * Get newsletter by ID
     */
    public function getById(int $id): JsonResponse
    {
        $newsletter = Newsletter::where('id', $id)->published()->with('author:id,name,email')->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $newsletter
        ]);
    }

    /**
     * Get newsletter by issue number
     */
    public function getByIssue(int $issue): JsonResponse
    {
        $newsletter = Newsletter::where('issue_number', $issue)->published()->with('author:id,name,email')->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $newsletter
        ]);
    }

    /**
     * Search newsletters
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255'
        ]);

        $newsletters = Newsletter::published()
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('content', 'like', "%{$request->q}%");
            })
            ->with('author:id,name,email')
            ->latest('published_at')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'count' => $newsletters->count(),
            'data' => $newsletters
        ]);
    }

    /**
     * Get latest newsletters
     */
    public function latest(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 5);
        $newsletters = Newsletter::published()
            ->with('author:id,name,email')
            ->latest('published_at')
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'count' => $newsletters->count(),
            'data' => $newsletters
        ]);
    }

    /**
     * Get sent newsletters
     */
    public function sent(Request $request): JsonResponse
    {
        $per_page = $request->get('per_page', 15);
        $newsletters = Newsletter::published()
            ->whereNotNull('sent_at')
            ->with('author:id,name,email')
            ->latest('sent_at')
            ->paginate($per_page);

        return response()->json([
            'status' => 'success',
            'data' => $newsletters->items(),
            'pagination' => [
                'total' => $newsletters->total(),
                'per_page' => $newsletters->perPage(),
                'current_page' => $newsletters->currentPage(),
                'last_page' => $newsletters->lastPage(),
            ]
        ]);
    }

    /**
     * Get newsletter statistics
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total' => Newsletter::count(),
                'published' => Newsletter::published()->count(),
                'sent' => Newsletter::whereNotNull('sent_at')->count(),
                'draft' => Newsletter::where('is_published', false)->count(),
                'total_recipients' => Newsletter::sum('recipients_count') ?? 0,
            ]
        ]);
    }
}
