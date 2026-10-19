<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\PageResource;
use App\Http\Resources\Api\PageCollection;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PageApiController extends BaseApiController
{
    /**
     * Get all published pages
     */
    public function index(Request $request): JsonResponse
    {
        $query = Page::published();

        $this->applySearch($query, $request, ['title', 'slug', 'excerpt']);
        $this->applySort($query, $request, ['sort_order', 'published_at', 'created_at'], 'sort_order', 'asc');

        $perPage = $this->getPerPage($request);
        $pages = $query->with('sections')->paginate($perPage);

        return $this->paginated($pages->load('sections'));
    }

    /**
     * Get single page by slug
     */
    public function show(string $slug): JsonResponse
    {
        $page = Page::where('slug', $slug)->published()->with('sections')->firstOrFail();

        return $this->success(new PageResource($page));
    }

    /**
     * Get page by ID
     */
    public function getById(int $id): JsonResponse
    {
        $page = Page::where('id', $id)->published()->with('sections')->firstOrFail();

        return $this->success(new PageResource($page));
    }

    /**
     * Search pages
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255'
        ]);

        $pages = Page::published()
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('slug', 'like', "%{$request->q}%")
                  ->orWhere('excerpt', 'like', "%{$request->q}%");
            })
            ->with('sections')
            ->latest('published_at')
            ->limit($this->getLimit($request))
            ->get();

        return $this->success(PageResource::collection($pages));
    }
}