<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\NavigationItemResource;
use App\Models\NavigationItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NavigationApiController extends BaseApiController
{
    /**
     * Get all navigation items (root level with children)
     */
    public function index(Request $request): JsonResponse
    {
        $navigation = NavigationItem::active()
            ->root()
            ->with(['children.page', 'page'])
            ->ordered()
            ->get();

        return $this->success(NavigationItemResource::collection($navigation));
    }

    /**
     * Get single navigation item by ID
     */
    public function getById(int $id): JsonResponse
    {
        $item = NavigationItem::where('id', $id)
            ->where('is_active', true)
            ->where('is_visible', true)
            ->with(['children.page', 'page'])
            ->firstOrFail();

        return $this->success(new NavigationItemResource($item));
    }
}