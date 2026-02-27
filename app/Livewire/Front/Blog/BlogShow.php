<?php

namespace App\Livewire\Front\Blog;

use App\Models\BlogPost;
use Livewire\Component;

class BlogShow extends Component
{
    public BlogPost $post;

    public function mount(string $slug): void
    {
        $this->post = BlogPost::published()->where('slug', $slug)->firstOrFail();
        $this->post->increment('views_count');
    }

    public function render()
    {
        return view('livewire.front.blog.blog-show')
            ->layout('layouts.app', ['pageTitle' => $this->post->title]);
    }
}
