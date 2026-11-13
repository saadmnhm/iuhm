<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\Publication;
use App\Models\SeoRedirect;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ActualiteController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $category = trim((string) $request->query('category', ''));

        $query = Actualite::published()->latest('published_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_ar', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('excerpt_ar', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $posts = $query->paginate(9)->withQueryString();
        $featuredPosts = Actualite::published()->latest('published_at')->limit(6)->get();
        $deliverables = Publication::published()->orderByDesc('downloads_count')->limit(4)->get();
        $categories = Actualite::published()->distinct()->pluck('category')->filter()->values();

        return view('livewire.site.actualite.index', [
            'posts' => $posts,
            'featuredPosts' => $featuredPosts,
            'deliverables' => $deliverables,
            'categories' => $categories,
            'search' => $search,
            'pageSlug' => $category,
        ]);
    }

    public function show(string $slug): View|RedirectResponse
    {
        $post = Actualite::with('author')->where('slug', $slug)->published()->first();

        if (! $post) {
            $redirect = SeoRedirect::targetFor('actualite', $slug);

            if ($redirect && $redirect->new_slug !== $slug) {
                return redirect()->to(route('actualite.show', $redirect->new_slug), $redirect->status_code);
            }

            abort(404);
        }

        $post->increment('views_count');
        $post->refresh()->load('author');

        $relatedPosts = $post->relatedActualites(3);

        return view('livewire.site.actualite.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'type' => 'actualite',
        ]);
    }
}
