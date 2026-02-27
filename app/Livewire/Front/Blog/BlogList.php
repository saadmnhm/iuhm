<?php

namespace App\Livewire\Front\Blog;

use App\Models\BlogPost;
use Livewire\Component;
use Livewire\WithPagination;

class BlogList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = 'all';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingCategory() { $this->resetPage(); }

    public function render()
    {
        $query = BlogPost::published()->with('author');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('excerpt', 'like', "%{$this->search}%");
            });
        }

        if ($this->category !== 'all') {
            $query->where('category', $this->category);
        }

        $posts = $query->latest('published_at')->paginate(9);
        $categories = BlogPost::published()->whereNotNull('category')
            ->select('category')->distinct()->pluck('category');

        return view('livewire.front.blog.blog-list', compact('posts', 'categories'))
            ->layout('layouts.app', ['pageTitle' => 'Blog & Actualités']);
    }
}
