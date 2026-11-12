<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $category = trim((string) $request->query('category', ''));

        $query = Article::published()->latest('published_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_ar', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $posts = $query->paginate(9)->withQueryString();
        $featuredPost = $posts->getCollection()->first();
        $categories = Article::published()->distinct()->pluck('category')->filter()->values();

        return view('livewire.site.articles.index', [
            'posts' => $posts,
            'featuredPost' => $featuredPost,
            'categories' => $categories,
            'search' => $search,
            'pageSlug' => $category,
        ]);
    }

    public function show(string $slug): View
    {
        $post = Article::with('author')->where('slug', $slug)->published()->firstOrFail();
        $post->increment('views_count');
        $post->refresh()->load('author');

        $relatedPosts = $post->relatedArticles(3);

        return view('livewire.site.actualite.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'type' => 'article',
        ]);
    }
}
