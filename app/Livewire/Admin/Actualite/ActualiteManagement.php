<?php

namespace App\Livewire\Admin\Actualite;

use App\Models\Actualite;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ActualiteManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $categoryFilter = '';
    public string $activeTab = 'list';
    public bool $showModal = false;
    public bool $editMode = false;
    public ?int $newsId = null;

    public $title, $title_ar, $excerpt, $excerpt_ar, $content, $content_ar, $image, $file;
    public $category, $tags_input, $is_published = false;
    public bool $showComments = true;
    public bool $newsletter = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'title'        => 'required|string|max:255',
            'title_ar'     => 'nullable|string|max:255',
            'excerpt'      => 'nullable|string|max:500',
            'excerpt_ar'   => 'nullable|string|max:500',
            'content'      => 'required|string',
            'content_ar'   => 'nullable|string',
            'file'         => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'category'     => 'nullable|string|max:100',
            'tags_input'   => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ];
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }


    public function togglePublish(int $id): void
    {
        $news = Actualite::findOrFail($id);
        $news->update([
            'is_published' => !$news->is_published,
            'published_at' => !$news->is_published ? now() : $news->published_at,
        ]);
        $status = $news->is_published ? 'published' : 'unpublished';
        AdminActivityLog::log("news_{$status}", "Toggled news: {$news->title} → {$status}", Actualite::class, $news->id);
    }

    public function delete(int $id): void
    {
        $news = Actualite::findOrFail($id);
        AdminActivityLog::log('news_deleted', "Deleted news: {$news->title}", Actualite::class, $news->id);
        $news->delete();
        session()->flash('success', 'Actualité supprimée avec succès!');
    }


    public function render()
    {
        $query = Actualite::with('author');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('excerpt', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%");
            });
        }

        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
        }

        $newsItems = $query->latest()->paginate(15);


        $stats_card = [
            'totalarticles' => [
                'label' => 'Total articles',
                'icon' => 'ri-article-line',
                'data' => Actualite::count(),
            ],
            'actualites' => [
                'label' => 'Published articles',
                'icon' => 'ri-newspaper-line',
                'color' => 'text-blue-600',
                'data' => Actualite::where('is_published', true)->count(),
            ],
            'infolettres' => [
                'label' => 'Draft articles',
                'icon' => 'ri-mail-send-line',
                'data' => Actualite::where('is_published', false)->count(),
            ],
            'views' => [
                'label' => 'Views',
                'icon' => 'ri-eye-line',
                'data' => Actualite::sum('views_count'),
            ],

        ];

        return view('livewire.admin.actualite.actualite-management', [
            'newsItems' => $newsItems,
            'stats_card' => $stats_card,
        ])->layout('layouts.admin', ['header' => "Gestion d'actualités"]);
    }
}
