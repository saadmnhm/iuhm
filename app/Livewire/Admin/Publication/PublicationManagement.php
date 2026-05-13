<?php

namespace App\Livewire\Admin\Publication;

use App\Models\Publication;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class PublicationManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $statusFilter = 'all';
    public bool $showModal = false;
    public bool $editMode = false;
    public ?int $publicationId = null;

    public $title, $title_ar, $description, $description_ar, $file, $newFile;
    public $category, $status = 'pending', $due_date, $is_published = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'title'          => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'newFile'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:5120',
            'category'       => 'nullable|string|max:100',
            'status'         => 'in:pending,completed,overdue',
            'due_date'       => 'nullable|date',
            'is_published'   => 'boolean',
        ];
    }

    public function updatingSearch() { $this->resetPage(); }



    public function togglePublish(int $id): void
    {
        $publication = Publication::findOrFail($id);
        $publication->update([
            'is_published' => !$publication->is_published,
            'published_at' => !$publication->is_published ? now() : $publication->published_at,
        ]);
        $status = $publication->is_published ? 'published' : 'unpublished';
        AdminActivityLog::log("publication_{$status}", "Toggled publication: {$publication->title} → {$status}", Publication::class, $publication->id);
    }

    public function delete(int $id): void
    {
        $publication = Publication::findOrFail($id);
        AdminActivityLog::log('publication_deleted', "Deleted publication: {$publication->title}", Publication::class, $publication->id);
        $publication->delete();
        session()->flash('success', 'Publication supprimée avec succès!');
    }

    public function render()
    {
        $query = Publication::with('author');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
                  ->orWhere('category', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter === 'published') {
            $query->where('is_published', true);
        } elseif ($this->statusFilter === 'draft') {
            $query->where('is_published', false);
        }

        $publications = $query->latest()->paginate(15);



        $stats_card = [
            'total' => [
                'label' => 'TOTAL PUBLIÉS',
                'icon' => 'ri-article-line',
                'data' => Publication::count(),
            ],
            'published' => [
                'label' => 'PUBLIÉS',
                'icon' => 'ri-newspaper-line',
                'color' => 'text-blue-600',
                'data' => Publication::where('is_published', true)->count(),
            ],
            'draft' => [
                'label' => 'NON PUBLIÉS',
                'icon' => 'ri-mail-send-line',
                'data' => Publication::where('status', 'completed')->count(),
            ],

        ];

        return view('livewire.admin.publication.publication-management', [
            'publications' => $publications,
            'stats_card' => $stats_card,
        ])->layout('layouts.admin', ['header' => 'Publication Management']);
    }
}
