<?php

namespace App\Livewire\Admin\Media;

use App\Models\BlogPost;
use App\Models\News;
use App\Models\Newsletter;
use App\Models\Deliverable;
use App\Models\Media;
use Livewire\Component;

class MediaManagement extends Component
{
    public function render()
    {
        $stats = [
            'totalBlog'            => BlogPost::count(),
            'publishedBlog'        => BlogPost::where('is_published', true)->count(),
            'draftBlog'            => BlogPost::where('is_published', false)->count(),
            'totalNews'            => News::count(),
            'publishedNews'        => News::where('is_published', true)->count(),
            'totalNewsletters'     => Newsletter::count(),
            'publishedNewsletters' => Newsletter::where('is_published', true)->count(),
            'totalDeliverables'    => Deliverable::count(),
            'totalMedia'           => Media::count(),
        ];

        $recentBlog = BlogPost::with('author')
            ->where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        $recentNews = News::with('author')
            ->where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        $recentNewsletters = Newsletter::latest()->take(4)->get();

        return view('livewire.admin.media.media-management', compact(
            'stats', 'recentBlog', 'recentNews', 'recentNewsletters'
        ))->layout('layouts.admin', ['header' => 'Tableau de Bord Contenu']);
    }
}
