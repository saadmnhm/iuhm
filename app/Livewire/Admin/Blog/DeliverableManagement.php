<?php

namespace App\Livewire\Admin\Blog;

use App\Models\Deliverable;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class DeliverableManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $statusFilter = 'all';
    public bool $showModal = false;
    public bool $editMode = false;
    public ?int $deliverableId = null;

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

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function openEdit(int $id): void
    {
        $deliverable = Deliverable::findOrFail($id);
        $this->deliverableId = $deliverable->id;
        $this->title = $deliverable->title;
        $this->title_ar = $deliverable->title_ar;
        $this->description = $deliverable->description;
        $this->description_ar = $deliverable->description_ar;
        $this->file = $deliverable->file_url;
        $this->category = $deliverable->category;
        $this->status = $deliverable->status;
        $this->due_date = $deliverable->due_date?->format('Y-m-d');
        $this->is_published = $deliverable->is_published;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'          => $this->title,
            'title_ar'       => $this->title_ar ?: null,
            'description'    => $this->description ?: null,
            'description_ar' => $this->description_ar ?: null,
            'category'       => $this->category ?: null,
            'status'         => $this->status,
            'due_date'       => $this->due_date ? now()->parse($this->due_date) : null,
            'is_published'   => $this->is_published,
        ];

        if ($this->is_published) {
            $data['published_at'] = now();
        }

        if ($this->newFile) {
            $filename = uniqid('deliverable_') . '.' . $this->newFile->getClientOriginalExtension();
            $this->newFile->storeAs('deliverables', $filename, 'uploads');
            $data['file_url'] = 'uploads/deliverables/' . $filename;
            $data['file_type'] = $this->newFile->getClientOriginalExtension();
        }

        if ($this->editMode) {
            $deliverable = Deliverable::findOrFail($this->deliverableId);
            $deliverable->update($data);
            AdminActivityLog::log('deliverable_updated', "Updated deliverable: {$deliverable->title}", Deliverable::class, $deliverable->id);
            session()->flash('success', 'Livrable mis à jour avec succès!');
        } else {
            $data['slug'] = Deliverable::generateSlug($this->title);
            $data['author_id'] = auth()->id();
            $deliverable = Deliverable::create($data);
            AdminActivityLog::log('deliverable_created', "Created deliverable: {$deliverable->title}", Deliverable::class, $deliverable->id);
            session()->flash('success', 'Livrable créé avec succès!');
        }

        $this->resetForm();
    }

    public function togglePublish(int $id): void
    {
        $deliverable = Deliverable::findOrFail($id);
        $deliverable->update([
            'is_published' => !$deliverable->is_published,
            'published_at' => !$deliverable->is_published ? now() : $deliverable->published_at,
        ]);
        $status = $deliverable->is_published ? 'published' : 'unpublished';
        AdminActivityLog::log("deliverable_{$status}", "Toggled deliverable: {$deliverable->title} → {$status}", Deliverable::class, $deliverable->id);
    }

    public function delete(int $id): void
    {
        $deliverable = Deliverable::findOrFail($id);
        AdminActivityLog::log('deliverable_deleted', "Deleted deliverable: {$deliverable->title}", Deliverable::class, $deliverable->id);
        $deliverable->delete();
        session()->flash('success', 'Livrable supprimé avec succès!');
    }

    protected function resetForm(): void
    {
        $this->reset(['deliverableId', 'title', 'title_ar', 'description', 'description_ar', 'file', 'newFile', 'category', 'status', 'due_date', 'is_published']);
        $this->editMode = false;
        $this->showModal = false;
        $this->status = 'pending';
        $this->resetValidation();
        $this->resetPage();
    }

    public function render()
    {
        $query = Deliverable::with('author');

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

        $deliverables = $query->latest()->paginate(15);

        return view('livewire.admin.blog.deliverable-management', [
            'deliverables' => $deliverables,
            'totalDeliverables' => Deliverable::count(),
            'publishedDeliverables' => Deliverable::where('is_published', true)->count(),
            'completedDeliverables' => Deliverable::where('status', 'completed')->count(),
        ])->layout('layouts.admin', ['header' => 'Admin Management']);;
    }
}
