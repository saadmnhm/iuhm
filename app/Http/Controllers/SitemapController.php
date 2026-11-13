<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\Article;
use App\Models\Project;
use Illuminate\Http\Response;

/**
 * Dynamic robots.txt + XML sitemap for search engines.
 */
class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            $this->url(url('/'), now(), 'daily', '1.0'),
            $this->url(route('articles.index'), now(), 'daily', '0.9'),
            $this->url(route('actualite.index'), now(), 'daily', '0.9'),
            $this->url(route('projects.index'), now(), 'weekly', '0.9'),
            $this->url(route('services.index'), now(), 'monthly', '0.7'),
            $this->url(route('resources.index'), now(), 'weekly', '0.7'),
            $this->url(route('history.index'), now(), 'monthly', '0.6'),
            $this->url(route('contact.index'), now(), 'yearly', '0.5'),
        ];

        foreach (Article::published()->orderByDesc('updated_at')->get(['slug', 'updated_at']) as $article) {
            $urls[] = $this->url(route('articles.show', $article->slug), $article->updated_at, 'weekly', '0.8');
        }

        foreach (Actualite::published()->orderByDesc('updated_at')->get(['slug', 'updated_at']) as $actualite) {
            $urls[] = $this->url(route('actualite.show', $actualite->slug), $actualite->updated_at, 'weekly', '0.8');
        }

        foreach (Project::published()->orderByDesc('updated_at')->get(['slug', 'updated_at']) as $project) {
            $urls[] = $this->url(route('projects.show', $project->slug), $project->updated_at, 'monthly', '0.7');
        }

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /user',
            'Disallow: /login',
            'Disallow: /register',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /**
     * @return array{loc: string, lastmod: string|null, changefreq: string, priority: string}
     */
    private function url(string $loc, ?\DateTimeInterface $lastmod = null, string $changefreq = 'weekly', string $priority = '0.5'): array
    {
        return [
            'loc' => $loc,
            'lastmod' => $lastmod?->format('Y-m-d\TH:i:sP'),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }
}
