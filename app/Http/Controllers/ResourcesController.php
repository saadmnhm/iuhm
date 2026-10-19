<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Publication;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ResourcesController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $page = (int) $request->query('page', 1);

        $query = Publication::published()->latest('published_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_ar', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('description_ar', 'like', "%{$search}%");
            });
        }

        $deliverables = $query->paginate(9, ['*'], 'page', $page);
        $featuredDeliverable = $deliverables->first();
        $recentDeliverables = Publication::published()->orderByDesc('downloads_count')->limit(3)->get();

        $dbPage = Page::published()->where('slug', 'resources')->first();

        return view('livewire.site.resources.resources', compact(
            'deliverables',
            'featuredDeliverable',
            'recentDeliverables',
            'search',
            'dbPage',
        ));
    }
}