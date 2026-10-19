<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectApiController extends BaseApiController
{
    /**
     * Get all published projects
     */
    public function index(Request $request): JsonResponse
    {
        $query = Project::published();

        $this->applyFilters($query, $request, [
            'status' => 'status',
        ]);

        $this->applySearch($query, $request, ['title', 'description', 'location', 'partner']);
        $this->applySort($query, $request, ['sort_order', 'start_date', 'created_at'], 'sort_order', 'asc');

        $perPage = $this->getPerPage($request);
        $projects = $query->paginate($perPage);

        return $this->paginated($projects);
    }

    /**
     * Get single project by slug
     */
    public function show(string $slug): JsonResponse
    {
        $project = Project::where('slug', $slug)->published()->firstOrFail();

        return $this->success(new ProjectResource($project));
    }

    /**
     * Get project by ID
     */
    public function getById(int $id): JsonResponse
    {
        $project = Project::where('id', $id)->published()->firstOrFail();

        return $this->success(new ProjectResource($project));
    }

    /**
     * Search projects
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max=255'
        ]);

        $projects = Project::published()
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%")
                  ->orWhere('location', 'like', "%{$request->q}%")
                  ->orWhere('partner', 'like', "%{$request->q}%");
            })
            ->latest('start_date')
            ->limit($this->getLimit($request))
            ->get();

        return $this->success(ProjectResource::collection($projects));
    }

    /**
     * Get projects by status
     */
    public function getByStatus(string $status, Request $request): JsonResponse
    {
        $perPage = $this->getPerPage($request);
        $projects = Project::published()
            ->where('status', $status)
            ->latest('start_date')
            ->paginate($perPage);

        return $this->paginated($projects);
    }

    /**
     * Get featured projects
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = $this->getLimit($request);
        $projects = Project::published()
            ->featured()
            ->ordered()
            ->limit($limit)
            ->get();

        return $this->success(ProjectResource::collection($projects));
    }

    /**
     * Get all statuses
     */
    public function statuses(): JsonResponse
    {
        $statuses = Project::published()
            ->distinct()
            ->pluck('status')
            ->filter()
            ->values();

        return $this->success($statuses);
    }
}