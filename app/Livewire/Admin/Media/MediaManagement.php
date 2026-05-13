<?php

namespace App\Livewire\Admin\Media;

use App\Models\Article;
use App\Models\Actualite;
use App\Models\Newsletter;
use App\Models\Publication;
use App\Models\Media;
use Livewire\Component;

class MediaManagement extends Component
{
    public function render()
    {
        $stats = [
            'totalBlog'            => Article::count(),
            'publishedBlog'        => Article::where('is_published', true)->count(),
            'draftBlog'            => Article::where('is_published', false)->count(),
            'totalNews'            => Actualite::count(),
            'publishedNews'        => Actualite::where('is_published', true)->count(),
            'totalNewsletters'     => Newsletter::count(),
            'totalDeliverables'    => Publication::count(),
            'totalMedia'           => Media::count(),
        ];

        $stats_card = [
            'totalarticles' => [
                'label' => 'Articles blog',
                'icon' => 'ri-article-line',
                'data_icon' => $stats['publishedBlog'],
                'data' => $stats['totalBlog'],
            ],
            'actualites' => [
                'label' => 'Actualités',
                'icon' => 'ri-newspaper-line',
                'color' => 'text-blue-600',
                'data' => $stats['totalNews'],
            ],
            'livrables' => [
                'label' => 'Livrables',
                'icon' => 'ri-file-list-3-line',
                'data_icon' => $stats['totalDeliverables'],
                'data' => $stats['totalDeliverables'],
            ],
            'medias' => [
                'label' => 'Médias',
                'icon' => 'ri-image-line',
                'data_icon' => $stats['totalMedia'],
                'data' => $stats['totalMedia'],
            ],

        ];


        $recentBlog = Article::with('author')
            ->where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        $recentNews = Actualite::with('author')
            ->where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        $recentNewsletters = Newsletter::latest()->take(4)->get();

        return view('livewire.admin.media.media-management', compact(
            'stats', 'stats_card', 'recentBlog', 'recentNews', 'recentNewsletters'
        ))->layout('layouts.admin', ['header' => 'Tableau de Bord Contenu']);
    }
}
