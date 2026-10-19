<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\Article;
use App\Models\Page;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $page = Page::published()->where('slug', 'home')->first();

        if (! $page) {
            $page = Page::published()->ordered()->first();
        }

        $latestNews = Actualite::published()->latest('published_at')->limit(3)->get();
        $trendingPosts = Article::published()->orderByDesc('views_count')->limit(3)->get();

        return view('livewire.site.home.home', compact('page', 'latestNews', 'trendingPosts'));
    }

    public function contact(): View
    {
        $mapEmbed = 'https://www.openstreetmap.org/export/embed.html?bbox=-7.573990%2C33.585155%C-7.553990%2C33.605155&layer=mapnik&marker=33.595155%2C-7.563990';

        return view('livewire.site.contact.contact', compact('mapEmbed'));
    }

    public function history(): View
    {
        $page = Page::where('slug', 'history')->published()->first();

        return view('livewire.site.history.history', compact('page'));
    }

    public function projects(): View
    {
        $projects = Project::published()->ordered()->paginate(12);
        $page = Page::where('slug', 'projects')->published()->first();

        return view('livewire.site.projects.index', compact('projects', 'page'));
    }

    public function projectShow(string $slug): View
    {
        $project = Project::published()->where('slug', $slug)->firstOrFail();

        return view('livewire.site.projects.show', compact('project'));
    }
}