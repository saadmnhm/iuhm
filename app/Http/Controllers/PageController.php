<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\Article;
use App\Models\Page;
use App\Models\Project;
use App\Models\SeoRedirect;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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
        $projects = Project::published()->ordered()->paginate(9)->withQueryString();
        $page = Page::where('slug', 'projects')->published()->first();

        return view('livewire.site.projects.index', compact('projects', 'page'));
    }

    public function projectShow(string $slug): View|RedirectResponse
    {
        $project = Project::published()->where('slug', $slug)->first();

        if (! $project) {
            $redirect = SeoRedirect::targetFor('project', $slug);

            if ($redirect && $redirect->new_slug !== $slug) {
                return redirect()->to(route('projects.show', $redirect->new_slug), $redirect->status_code);
            }

            abort(404);
        }

        $project->increment('views_count');
        $project->refresh();

        $relatedProjects = $project->relatedProjects(3);

        return view('livewire.site.projects.show', compact('project', 'relatedProjects'));
    }
}
