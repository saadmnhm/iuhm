<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;

class ServicesController extends Controller
{
    public function index(): View
    {
        $page = Page::published()
            ->where('slug', 'services')
            ->with(['sections' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->first();

        return view('livewire.site.services.services', compact('page'));
    }
}