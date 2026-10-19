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
        $page = (int) $request->query('page', 1);

        $query = Article::published()->latest('published_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_ar', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('excerpt_ar', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('content_ar', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $posts = $query->paginate(9, ['*'], 'page', $page);
        $categories = Article::published()->distinct()->pluck('category')->filter()->values();

        return view('livewire.site.articles.index', [
            'posts' => $posts,
            'categories' => $categories,
            'search' => $search,
            'pageSlug' => $category,
        ]);
    }

    public function show(string $slug): View
    {
        $post = Article::where('slug', $slug)->published()->firstOrFail();
        $post->increment('views_count');
        $post->refresh();

        $relatedPosts = Article::published()->where('id', '!=', $post->id)->latest('published_at')->limit(3)->get();

        return view('livewire.site.actualite.show', compact('post', 'relatedPosts'));
    }
}