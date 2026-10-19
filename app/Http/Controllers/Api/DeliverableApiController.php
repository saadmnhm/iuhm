<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\PublicationResource;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DeliverableApiController extends BaseApiController
{
    /**
     * Get all published deliverables
     */
    public function index(Request $request): JsonResponse
    {
        $query = Publication::published();

        $this->applyFilters($query, $request, [
            'category' => 'category',
            'status' => 'status',
        ]);

        $this->applySearch($query, $request, ['title', 'description']);
        $this->applySort($query, $request, ['published_at', 'created_at', 'downloads_count'], 'published_at', 'desc');

        $perPage = $this->getPerPage($request);
        $deliverables = $query->with('author:id,nom,prenom,email')->paginate($perPage);

        return $this->paginated($deliverables->load('author'));
    }

    /**
     * Get single deliverable by slug
     */
    public function show(string $slug): JsonResponse
    {
        $deliverable = Publication::where('slug', $slug)->published()->with('author:id,nom,prenom,email')->firstOrFail();
        $deliverable->increment('downloads_count');
        $deliverable->refresh();

        return $this->success(new PublicationResource($deliverable));
    }

    /**
     * Get deliverable by ID
     */
    public function getById(int $id): JsonResponse
    {
        $deliverable = Publication::where('id', $id)->published()->with('author:id,nom,prenom,email')->firstOrFail();
        $deliverable->increment('downloads_count');
        $deliverable->refresh();

        return $this->success(new PublicationResource($deliverable));
    }

    /**
     * Search deliverables
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255'
        ]);

        $deliverables = Publication::published()
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%");
            })
            ->with('author:id,nom,prenom,email')
            ->latest('published_at')
            ->limit($this->getLimit($request))
            ->get();

        return $this->success(PublicationResource::collection($deliverables));
    }

    /**
     * Get deliverables by category
     */
    public function getByCategory(string $category, Request $request): JsonResponse
    {
        $perPage = $this->getPerPage($request);
        $deliverables = Publication::published()
            ->where('category', $category)
            ->with('author:id,nom,prenom,email')
            ->latest('published_at')
            ->paginate($perPage);

        return $this->paginated($deliverables->load('author'));
    }

    /**
     * Get deliverables by status
     */
    public function getByStatus(string $status, Request $request): JsonResponse
    {
        $perPage = $this->getPerPage($request);
        $deliverables = Publication::published()
            ->where('status', $status)
            ->with('author:id,nom,prenom,email')
            ->latest('published_at')
            ->paginate($perPage);

        return $this->paginated($deliverables->load('author'));
    }

    /**
     * Get most downloaded deliverables
     */
    public function popular(Request $request): JsonResponse
    {
        $limit = $this->getLimit($request);
        $deliverables = Publication::published()
            ->with('author:id,nom,prenom,email')
            ->orderBy('downloads_count', 'desc')
            ->limit($limit)
            ->get();

        return $this->success(PublicationResource::collection($deliverables));
    }

    /**
     * Get all categories
     */
    public function categories(): JsonResponse
    {
        $categories = Publication::published()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return $this->success($categories);
    }

    /**
     * Get all statuses
     */
    public function statuses(): JsonResponse
    {
        $statuses = Publication::published()
            ->distinct()
            ->pluck('status')
            ->filter()
            ->values();

        return $this->success($statuses);
    }
}