<?php

namespace App\Livewire\Admin\Article;

use App\Models\Article;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ArticleManagement extends Component
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
            'newImage'     => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
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
        $post = Article::findOrFail($id);
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
            $filename = uniqid('article_') . '.' . $this->newImage->getClientOriginalExtension();
            $this->newImage->storeAs('article', $filename, 'uploads');
            $data['image'] = 'uploads/article/' . $filename;
        }

        if ($this->editMode) {
            $post = Article::findOrFail($this->postId);
            $post->update($data);
            AdminActivityLog::log('article_updated', "Updated article: {$post->title}", Article::class, $post->id);
            session()->flash('success', 'Article mis à jour avec succès!');
        } else {
            $data['slug'] = Article::generateSlug($this->title);
            $data['author_id'] = auth()->id();
            $post = Article::create($data);
            AdminActivityLog::log('article_created', "Created article: {$post->title}", Article::class, $post->id);
            session()->flash('success', 'Article créé avec succès!');
        }

        $this->resetForm();
    }

    public function togglePublish(int $id): void
    {
        $post = Article::findOrFail($id);
        $post->update([
            'is_published' => !$post->is_published,
            'published_at' => !$post->is_published ? now() : $post->published_at,
        ]);
        $status = $post->is_published ? 'published' : 'unpublished';
        AdminActivityLog::log("article_{$status}", "Toggled article: {$post->title} → {$status}", Article::class, $post->id);
    }

    public function delete(int $id): void
    {
        $post = Article::findOrFail($id);
        AdminActivityLog::log('article_deleted', "Deleted article: {$post->title}", Article::class, $post->id);
        $post->delete();
        session()->flash('success', 'Article supprimé avec succès!');
    }

    protected function resetForm(): void
    {
        $this->reset(['postId', 'title', 'title_ar', 'excerpt', 'content', 'image', 'newImage', 'category', 'tags_input', 'is_published']);
        $this->editMode = false;
        $this->showModal = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Article::with('author');

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



        $stats_card = [
            'totalarticles' => [
                'label' => 'Total articles',
                'icon' => 'ri-article-line',
                'data' => Article::count(),
            ],
            'actualites' => [
                'label' => 'Published articles',
                'icon' => 'ri-newspaper-line',
                'color' => 'text-blue-600',
                'data' => Article::where('is_published', true)->count(),
            ],
            'infolettres' => [
                'label' => 'Draft articles',
                'icon' => 'ri-mail-send-line',
                'data' => Article::where('is_published', false)->count(),
            ],
            'views' => [
                'label' => 'Views',
                'icon' => 'ri-eye-line',
                'data' => Article::sum('views_count'),
            ],

        ];

        return view('livewire.admin.article.article-management', compact('posts', 'stats_card'))
            ->layout('layouts.admin', ['header' => 'Gestion des Articles']);
    }
}
