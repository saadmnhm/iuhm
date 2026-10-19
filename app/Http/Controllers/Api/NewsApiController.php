<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\ActualiteResource;
use App\Models\Actualite;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NewsApiController extends BaseApiController
{
    /**
     * Get all published news
     */
    public function index(Request $request): JsonResponse
    {
        $query = Actualite::published();

        $this->applyFilters($query, $request, [
            'category' => 'category',
        ]);

        $this->applySearch($query, $request, ['title', 'excerpt']);
        $this->applySort($query, $request, ['published_at', 'created_at', 'views_count'], 'published_at', 'desc');

        $perPage = $this->getPerPage($request);
        $news = $query->with('author:id,nom,prenom,email')->paginate($perPage);

        return $this->paginated($news->load('author'));
    }

    /**
     * Get single news by slug
     */
    public function show(string $slug): JsonResponse
    {
        $news = Actualite::where('slug', $slug)->published()->with('author:id,nom,prenom,email')->firstOrFail();
        $news->increment('views_count');
        $news->refresh();

        return $this->success(new ActualiteResource($news));
    }

    /**
     * Get news by ID
     */
    public function getById(int $id): JsonResponse
    {
        $news = Actualite::where('id', $id)->published()->with('author:id,nom,prenom,email')->firstOrFail();
        $news->increment('views_count');
        $news->refresh();

        return $this->success(new ActualiteResource($news));
    }

    /**
     * Search news
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255'
        ]);

        $news = Actualite::published()
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('excerpt', 'like', "%{$request->q}%")
                  ->orWhere('tags', 'like', "%{$request->q}%");
            })
            ->with('author:id,nom,prenom,email')
            ->latest('published_at')
            ->limit($this->getLimit($request))
            ->get();

        return $this->success(ActualiteResource::collection($news));
    }

    /**
     * Get news by category
     */
    public function getByCategory(string $category, Request $request): JsonResponse
    {
        $perPage = $this->getPerPage($request);
        $news = Actualite::published()
            ->where('category', $category)
            ->with('author:id,nom,prenom,email')
            ->latest('published_at')
            ->paginate($perPage);

        return $this->paginated($news->load('author'));
    }

    /**
     * Get latest news
     */
    public function latest(Request $request): JsonResponse
    {
        $limit = $this->getLimit($request);
        $news = Actualite::published()
            ->with('author:id,nom,prenom,email')
            ->latest('published_at')
            ->limit($limit)
            ->get();

        return $this->success(ActualiteResource::collection($news));
    }

    /**
     * Get all categories
     */
    public function categories(): JsonResponse
    {
        $categories = Actualite::published()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return $this->success($categories);
    }
}