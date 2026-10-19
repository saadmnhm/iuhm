<?php

namespace App\Livewire\Admin\Media;

use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $typeFilter = '';
    public $categoryFilter = '';
    public $perPage = 20;
    public $viewMode = 'grid'; // grid, list
    public $selectedMedia = [];

    // Modal
    public $showModal = false;
    public $editMode = false;
    public $mediaId = null;

    // Form fields
    public $title = '';
    public $title_ar = '';
    public $description = '';
    public $description_ar = '';
    public $category = '';
    public $tags = '';
    public $is_public = true;
    public $file = null;
    public $existingFile = '';

    protected $queryString = ['search', 'typeFilter', 'categoryFilter', 'perPage'];
    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:500',
            'is_public' => 'boolean',
            'file' => $this->editMode ? 'nullable|file|max:51200' : 'required|file|max:51200',
        ];
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingTypeFilter() { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }

    public function openCreate()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function openEdit($id)
    {
        $media = Media::findOrFail($id);
        $this->mediaId = $media->id;
        $this->title = $media->title;
        $this->title_ar = $media->title_ar;
        $this->description = $media->description;
        $this->description_ar = $media->description_ar;
        $this->category = $media->category;
        $this->tags = is_array($media->tags) ? implode(', ', $media->tags) : $media->tags;
        $this->is_public = $media->is_public;
        $this->existingFile = $media->file_path;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'category' => $this->category,
            'tags' => $this->tags ? array_map('trim', explode(',', $this->tags)) : [],
            'is_public' => $this->is_public,
            'uploaded_by' => Auth::id(),
        ];

        if ($this->file) {
            // Delete old file if exists
            if ($this->existingFile && file_exists(base_path($this->existingFile))) {
                unlink(base_path($this->existingFile));
            }

            $filename = time() . '_' . Str::slug($this->title) . '.' . $this->file->getClientOriginalExtension();
            $this->file->storeAs('media', $filename, 'uploads');
            $data['file_path'] = 'uploads/media/' . $filename;
            $data['file_name'] = $this->file->getClientOriginalName();
            $data['file_size'] = $this->file->getSize();
            $data['file_type'] = $this->file->getClientMimeType();
            $data['mime_type'] = $this->file->getClientMimeType();
        }

        if ($this->editMode) {
            $media = Media::findOrFail($this->mediaId);
            $media->update($data);
            session()->flash('success', 'Média mis à jour avec succès!');
        } else {
            $media = Media::create($data);
            session()->flash('success', 'Média créé avec succès!');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $media = Media::findOrFail($id);
        if ($media->file_path && file_exists(base_path($media->file_path))) {
            unlink(base_path($media->file_path));
        }
        $media->delete();
        session()->flash('success', 'Média supprimé avec succès!');
    }

    public function togglePublic($id)
    {
        $media = Media::findOrFail($id);
        $media->update(['is_public' => !$media->is_public]);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->title = '';
        $this->title_ar = '';
        $this->description = '';
        $this->description_ar = '';
        $this->category = '';
        $this->tags = '';
        $this->is_public = true;
        $this->file = null;
        $this->existingFile = '';
        $this->mediaId = null;
        $this->editMode = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Media::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('title_ar', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
                  ->orWhere('category', 'like', "%{$this->search}%");
            });
        }

        if ($this->typeFilter) {
            $query->where('mime_type', 'like', $this->typeFilter . '%');
        }

        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
        }

        $media = $query->latest()->paginate($this->perPage);

        // Get unique categories and types for filters
        $categories = Media::select('category')->distinct()->pluck('category')->filter()->sort()->values();
        $types = Media::select('mime_type')->distinct()->pluck('mime_type')->filter()->map(fn($t) => explode('/', $t)[0])->unique()->sort()->values();

        $stats = [
            'total' => Media::count(),
            'public' => Media::where('is_public', true)->count(),
            'private' => Media::where('is_public', false)->count(),
            'images' => Media::where('mime_type', 'like', 'image/%')->count(),
            'documents' => Media::where('mime_type', 'like', 'application/%')->count(),
        ];

        return view('livewire.admin.media.media-management', compact(
            'media', 'stats', 'categories', 'types'
        ))->layout('layouts.admin', ['header' => 'Gestion des Médias']);
    }
}