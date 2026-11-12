<?php

namespace App\Livewire\Admin\Statistics;

use App\Models\Actualite;
use App\Models\Article;
use App\Models\Contact;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\Project;
use App\Models\Publication;
use Illuminate\Support\Str;
use Livewire\Component;

class WebsiteAnalytics extends Component
{
    /** Number of months shown in the publications chart. */
    public int $months = 12;

    public function updatedMonths(): void
    {
        // No extra work needed: the chart is rebuilt on render.
    }

    public function categoryLabel(?string $category): string
    {
        if (blank($category)) {
            return 'Non classé';
        }

        $key = 'ui.category_'.$category;
        $translated = __($key);

        return $translated === $key ? Str::headline($category) : $translated;
    }

    public function render()
    {
        $months = max(3, min(24, $this->months));

        // ── KPI cards ────────────────────────────────────────────────────────
        $articleViews = (int) Article::sum('views_count');
        $newsViews = (int) Actualite::sum('views_count');
        $projectViews = (int) Project::sum('views_count');

        $kpi = [
            'articles' => [
                'total' => Article::count(),
                'published' => Article::published()->count(),
                'views' => $articleViews,
            ],
            'actualites' => [
                'total' => Actualite::count(),
                'published' => Actualite::published()->count(),
                'views' => $newsViews,
            ],
            'projets' => [
                'total' => Project::count(),
                'published' => Project::published()->count(),
                'views' => $projectViews,
            ],
            'pages' => [
                'total' => Page::count(),
                'published' => Page::published()->count(),
            ],
            'livrables' => [
                'total' => Publication::count(),
                'published' => Publication::published()->count(),
                'downloads' => (int) Publication::sum('downloads_count'),
            ],
            'newsletter' => [
                'total' => Newsletter::count(),
                'active' => Newsletter::where('is_active', true)->count(),
            ],
            'contacts' => [
                'total' => Contact::count(),
                'unread' => Contact::where('is_read', false)->count(),
            ],
        ];

        // ── Publications per month ───────────────────────────────────────────
        $monthKeys = [];
        $monthLabels = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKeys[] = $date->format('Y-m');
            $monthLabels[] = $date->translatedFormat('M');
        }

        $countByMonth = function ($models) use ($monthKeys) {
            $counts = array_fill_keys($monthKeys, 0);

            foreach ($models as $model) {
                $date = $model->published_at ?? $model->created_at;

                if (! $date) {
                    continue;
                }

                $key = $date->format('Y-m');

                if (array_key_exists($key, $counts)) {
                    $counts[$key]++;
                }
            }

            return array_values($counts);
        };

        $articleSeries = $countByMonth(Article::published()->get(['published_at']));
        $newsSeries = $countByMonth(Actualite::published()->get(['published_at']));
        $projectSeries = $countByMonth(Project::published()->get(['published_at', 'created_at']));

        $monthlyTotals = array_map(
            fn ($a, $n, $p) => $a + $n + $p,
            $articleSeries,
            $newsSeries,
            $projectSeries
        );

        $monthlyMax = max(1, max($monthlyTotals));
        $periodTotal = array_sum($monthlyTotals);
        $previousMonths = max(1, (int) round($months / 2));
        $previousTotal = array_sum(array_slice($monthlyTotals, 0, count($monthlyTotals) - $previousMonths));
        $currentTotal = array_sum(array_slice($monthlyTotals, -$previousMonths));
        $growth = $previousTotal > 0
            ? round((($currentTotal - $previousTotal) / $previousTotal) * 100)
            : ($currentTotal > 0 ? 100 : 0);

        // ── Categories ───────────────────────────────────────────────────────
        $categoryCounts = Article::published()->whereNotNull('category')->pluck('category')
            ->merge(Actualite::published()->whereNotNull('category')->pluck('category'))
            ->countBy()
            ->sortDesc()
            ->take(8);

        $categoryLabels = $categoryCounts->keys()->map(fn ($category) => $this->categoryLabel($category))->all();
        $categoryValues = $categoryCounts->values()->all();
        $categoryMax = max(1, max($categoryValues ?: [1]));

        // ── Project statuses ─────────────────────────────────────────────────
        $statusCounts = Project::query()->get(['status'])->countBy('status');

        $statusRows = [
            ['key' => 'planned', 'label' => __('ui.project_status_planned'), 'color' => 'bg-amber-100 text-amber-700', 'bar' => 'bg-amber-400'],
            ['key' => 'in_progress', 'label' => __('ui.project_status_in_progress'), 'color' => 'bg-blue-100 text-blue-700', 'bar' => 'bg-blue-500'],
            ['key' => 'completed', 'label' => __('ui.project_status_completed'), 'color' => 'bg-emerald-100 text-emerald-700', 'bar' => 'bg-emerald-500'],
            ['key' => 'archived', 'label' => __('ui.project_status_archived'), 'color' => 'bg-slate-100 text-slate-700', 'bar' => 'bg-slate-400'],
        ];

        $statusTotal = max(1, (int) $statusCounts->sum());
        foreach ($statusRows as $index => $row) {
            $statusRows[$index]['count'] = (int) ($statusCounts[$row['key']] ?? 0);
            $statusRows[$index]['percent'] = round(($statusRows[$index]['count'] / $statusTotal) * 100);
        }

        // ── Content distribution (published) ────────────────────────────────
        $distribution = [
            ['label' => __('ui.nav_articles'), 'value' => $kpi['articles']['published'], 'color' => '#214f95'],
            ['label' => __('ui.nav_news'), 'value' => $kpi['actualites']['published'], 'color' => '#23803B'],
            ['label' => __('ui.projects_page_label'), 'value' => $kpi['projets']['published'], 'color' => '#82E682'],
        ];
        $distributionTotal = max(1, array_sum(array_column($distribution, 'value')));

        // ── Top content by views ─────────────────────────────────────────────
        $topContent = collect();

        Article::published()->orderByDesc('views_count')->limit(5)->get()
            ->each(fn ($item) => $topContent->push([
                'type' => 'Article',
                'title' => $item->getLocalized('title'),
                'views' => (int) $item->views_count,
                'date' => $item->published_at,
                'url' => route('articles.show', $item->slug),
                'badge' => 'bg-blue-50 text-blue-700 border-blue-100',
            ]));

        Actualite::published()->orderByDesc('views_count')->limit(5)->get()
            ->each(fn ($item) => $topContent->push([
                'type' => 'Actualité',
                'title' => $item->getLocalized('title'),
                'views' => (int) $item->views_count,
                'date' => $item->published_at,
                'url' => route('actualite.show', $item->slug),
                'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            ]));

        Project::published()->orderByDesc('views_count')->limit(5)->get()
            ->each(fn ($item) => $topContent->push([
                'type' => 'Projet',
                'title' => $item->getLocalized('title'),
                'views' => (int) $item->views_count,
                'date' => $item->published_at ?? $item->created_at,
                'url' => route('projects.show', $item->slug),
                'badge' => 'bg-violet-50 text-violet-700 border-violet-100',
            ]));

        $topContent = $topContent->sortByDesc('views')->take(8)->values();

        // ── Recent publications ─────────────────────────────────────────────
        $recentContent = collect();

        Article::published()->latest('published_at')->limit(4)->get()
            ->each(fn ($item) => $recentContent->push([
                'type' => 'Article',
                'title' => $item->getLocalized('title'),
                'date' => $item->published_at,
                'url' => route('articles.show', $item->slug),
            ]));

        Actualite::published()->latest('published_at')->limit(4)->get()
            ->each(fn ($item) => $recentContent->push([
                'type' => 'Actualité',
                'title' => $item->getLocalized('title'),
                'date' => $item->published_at,
                'url' => route('actualite.show', $item->slug),
            ]));

        Project::published()->latest('published_at')->limit(4)->get()
            ->each(fn ($item) => $recentContent->push([
                'type' => 'Projet',
                'title' => $item->getLocalized('title'),
                'date' => $item->published_at ?? $item->created_at,
                'url' => route('projects.show', $item->slug),
            ]));

        $recentContent = $recentContent->sortByDesc('date')->take(8)->values();

        return view('livewire.admin.statistics.website-analytics', compact(
            'kpi',
            'monthLabels',
            'articleSeries',
            'newsSeries',
            'projectSeries',
            'monthlyTotals',
            'monthlyMax',
            'periodTotal',
            'growth',
            'categoryLabels',
            'categoryValues',
            'categoryMax',
            'statusRows',
            'statusTotal',
            'distribution',
            'distributionTotal',
            'topContent',
            'recentContent'
        ))->layout('layouts.admin', ['header' => 'Statistiques du site']);
    }
}
