<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\ArticleResource;
use App\Http\Resources\Api\ArticleCollection;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BlogPostApiController extends BaseApiController
{
    /**
     * Get all published blog posts
     */
    public function index(Request $request): JsonResponse
    {
        $query = Article::published();

        $this->applyFilters($query, $request, [
            'category' => 'category',
        ]);

        $this->applySearch($query, $request, ['title', 'excerpt', 'content']);
        $this->applySort($query, $request, ['published_at', 'created_at', 'views_count'], 'published_at', 'desc');

        $perPage = $this->getPerPage($request);
        $posts = $query->with('author:id,nom,prenom,email')->paginate($perPage);

        return $this->paginated($posts->load('author'));
    }

    /**
     * Get single blog post by slug
     */
    public function show(string $slug): JsonResponse
    {
        $post = Article::where('slug', $slug)->published()->with('author:id,nom,prenom,email')->firstOrFail();
        
        $post->increment('views_count');
        $post->refresh();

        return $this->success(new ArticleResource($post));
    }

    /**
     * Get blog post by ID
     */
    public function getById(int $id): JsonResponse
    {
        $post = Article::where('id', $id)->published()->with('author:id,nom,prenom,email')->firstOrFail();
        $post->increment('views_count');
        $post->refresh();

        return $this->success(new ArticleResource($post));
    }

    /**
     * Search blog posts
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255'
        ]);

        $posts = Article::published()
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('excerpt', 'like', "%{$request->q}%")
                  ->orWhere('tags', 'like', "%{$request->q}%");
            })
            ->with('author:id,nom,prenom,email')
            ->latest('published_at')
            ->limit($this->getLimit($request))
            ->get();

        return $this->success(ArticleResource::collection($posts));
    }

    /**
     * Get posts by category
     */
    public function getByCategory(string $category, Request $request): JsonResponse
    {
        $perPage = $this->getPerPage($request);
        $posts = Article::published()
            ->where('category', $category)
            ->with('author:id,nom,prenom,email')
            ->latest('published_at')
            ->paginate($perPage);

        return $this->paginated($posts->load('author'));
    }

    /**
     * Get trending posts
     */
    public function trending(Request $request): JsonResponse
    {
        $limit = $this->getLimit($request);
        $posts = Article::published()
            ->with('author:id,nom,prenom,email')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();

        return $this->success(ArticleResource::collection($posts));
    }

    /**
     * Get all categories
     */
    public function categories(): JsonResponse
    {
        $categories = Article::published()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return $this->success($categories);
    }
}