<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\HistoryTimelineResource;
use App\Models\HistoryTimeline;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HistoryTimelineApiController extends BaseApiController
{
    /**
     * Get all published history timeline entries
     */
    public function index(Request $request): JsonResponse
    {
        $query = HistoryTimeline::published();

        $this->applySearch($query, $request, ['title', 'description']);
        $this->applySort($query, $request, ['date', 'sort_order', 'created_at'], 'date', 'desc');

        $perPage = $this->getPerPage($request);
        $timelines = $query->paginate($perPage);

        return $this->paginated($timelines);
    }

    /**
     * Get single history timeline entry by ID
     */
    public function getById(int $id): JsonResponse
    {
        $timeline = HistoryTimeline::where('id', $id)->published()->firstOrFail();

        return $this->success(new HistoryTimelineResource($timeline));
    }

    /**
     * Search history timeline
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max=255'
        ]);

        $timelines = HistoryTimeline::published()
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%");
            })
            ->latest('date')
            ->limit($this->getLimit($request))
            ->get();

        return $this->success(HistoryTimelineResource::collection($timelines));
    }
}