<?php

namespace App\Livewire\Admin\Blog;

use App\Models\BlogPost;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class BlogManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $statusFilter = 'all';

    public bool $showModal = false;
    public bool $editMode = false;
    public ?int $postId = null;

    // Form fields
    public $title, $title_ar, $excerpt, $content, $image, $newImage;
    public $category, $tags_input, $is_published = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'title'        => 'required|string|max:255',
            'title_ar'     => 'nullable|string|max:255',
            'excerpt'      => 'nullable|string|max:500',
            'content'      => 'required|string',
            'newImage'     => 'nullable|image|max:2048',
            'category'     => 'nullable|string|max:100',
            'tags_input'   => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ];
    }

    public function updatingSearch() { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function openEdit(int $id): void
    {
        $post = BlogPost::findOrFail($id);
        $this->postId = $post->id;
        $this->title = $post->title;
        $this->title_ar = $post->title_ar;
        $this->excerpt = $post->excerpt;
        $this->content = $post->content;
        $this->image = $post->image;
        $this->category = $post->category;
        $this->tags_input = $post->tags ? implode(', ', $post->tags) : '';
        $this->is_published = $post->is_published;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'        => $this->title,
            'title_ar'     => $this->title_ar ?: null,
            'excerpt'      => $this->excerpt ?: null,
            'content'      => $this->content,
            'category'     => $this->category ?: null,
            'tags'         => $this->tags_input ? array_map('trim', explode(',', $this->tags_input)) : null,
            'is_published' => $this->is_published,
        ];

        if ($this->is_published) {
            $data['published_at'] = now();
        }

        if ($this->newImage) {
            $path = $this->newImage->store('blog', 'public');
            $data['image'] = '/storage/' . $path;
        }

        if ($this->editMode) {
            $post = BlogPost::findOrFail($this->postId);
            $post->update($data);
            AdminActivityLog::log('blog_post_updated', "Updated blog post: {$post->title}", BlogPost::class, $post->id);
            session()->flash('success', 'Article mis à jour avec succès!');
        } else {
            $data['slug'] = BlogPost::generateSlug($this->title);
            $data['author_id'] = auth()->id();
            $post = BlogPost::create($data);
            AdminActivityLog::log('blog_post_created', "Created blog post: {$post->title}", BlogPost::class, $post->id);
            session()->flash('success', 'Article créé avec succès!');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function togglePublish(int $id): void
    {
        $post = BlogPost::findOrFail($id);
        $post->update([
            'is_published' => !$post->is_published,
            'published_at' => !$post->is_published ? now() : $post->published_at,
        ]);
        $status = $post->is_published ? 'published' : 'unpublished';
        AdminActivityLog::log("blog_post_{$status}", "Toggled blog post: {$post->title} → {$status}", BlogPost::class, $post->id);
    }

    public function delete(int $id): void
    {
        $post = BlogPost::findOrFail($id);
        AdminActivityLog::log('blog_post_deleted', "Deleted blog post: {$post->title}", BlogPost::class, $post->id);
        $post->delete();
        session()->flash('success', 'Article supprimé avec succès!');
    }

    protected function resetForm(): void
    {
        $this->reset(['postId', 'title', 'title_ar', 'excerpt', 'content', 'image', 'newImage', 'category', 'tags_input', 'is_published']);
        $this->editMode = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = BlogPost::with('author');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('excerpt', 'like', "%{$this->search}%")
                  ->orWhere('category', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter === 'published') {
            $query->where('is_published', true);
        } elseif ($this->statusFilter === 'draft') {
            $query->where('is_published', false);
        }

        $posts = $query->latest()->paginate(12);

        $stats = [
            'total'     => BlogPost::count(),
            'published' => BlogPost::where('is_published', true)->count(),
            'draft'     => BlogPost::where('is_published', false)->count(),
            'views'     => BlogPost::sum('views_count'),
        ];

        return view('livewire.admin.blog.blog-management', compact('posts', 'stats'))
            ->layout('layouts.admin', ['header' => 'Blog & Actualités']);
    }
}
