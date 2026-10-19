<?php

namespace App\Livewire\Admin\Project;

use App\Models\Project;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ProjectManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $statusFilter = 'all';

    public bool $showModal = false;
    public bool $editMode = false;
    public ?int $projectId = null;

    // Form fields
    public $title, $title_ar, $description, $description_ar, $short_description, $short_description_ar;
    public $featured_image, $newImage, $slug, $status, $location, $partner, $tags_input, $external_link, $sort_order;
    public $is_published = false, $is_featured = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'title'               => 'required|string|max:255',
            'title_ar'            => 'nullable|string|max:255',
            'description'         => 'nullable|string',
            'description_ar'      => 'nullable|string',
            'short_description'   => 'nullable|string|max:500',
            'short_description_ar'=> 'nullable|string|max:500',
            'slug'                => 'nullable|string|max:255|unique:project,slug',
            'featured_image'      => 'nullable|string|max:255',
            'newImage'            => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'status'              => 'nullable|string|max:50',
            'location'            => 'nullable|string|max:255',
            'partner'             => 'nullable|string|max:255',
            'tags_input'          => 'nullable|string|max:500',
            'external_link'       => 'nullable|url|max:255',
            'sort_order'          => 'nullable|integer',
            'is_published'        => 'boolean',
            'is_featured'         => 'boolean',
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
        $project = Project::findOrFail($id);
        $this->projectId = $project->id;
        $this->title = $project->title;
        $this->title_ar = $project->title_ar;
        $this->description = $project->description;
        $this->description_ar = $project->description_ar;
        $this->short_description = $project->short_description;
        $this->short_description_ar = $project->short_description_ar;
        $this->slug = $project->slug;
        $this->featured_image = $project->featured_image;
        $this->status = $project->status;
        $this->location = $project->location;
        $this->partner = $project->partner;
        $this->tags_input = $project->tags ? implode(', ', $project->tags) : '';
        $this->external_link = $project->external_link;
        $this->sort_order = $project->sort_order;
        $this->is_published = $project->is_published;
        $this->is_featured = $project->is_featured;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'               => $this->title,
            'title_ar'            => $this->title_ar ?: null,
            'description'         => $this->description ?: null,
            'description_ar'      => $this->description_ar ?: null,
            'short_description'   => $this->short_description ?: null,
            'short_description_ar'=> $this->short_description_ar ?: null,
            'slug'                => $this->slug ?: Project::generateSlug($this->title),
            'featured_image'      => $this->featured_image ?: null,
            'status'              => $this->status ?: 'planned',
            'location'            => $this->location ?: null,
            'partner'             => $this->partner ?: null,
            'tags'                => $this->tags_input ? array_map('trim', explode(',', $this->tags_input)) : null,
            'external_link'       => $this->external_link ?: null,
            'sort_order'          => $this->sort_order ?: 0,
            'is_published'        => $this->is_published,
            'is_featured'         => $this->is_featured,
        ];

        if ($this->newImage) {
            $filename = uniqid('project_') . '.' . $this->newImage->getClientOriginalExtension();
            $this->newImage->storeAs('project', $filename, 'uploads');
            $data['featured_image'] = 'uploads/project/' . $filename;
        }

        try {
            if ($this->editMode) {
                $project = Project::findOrFail($this->projectId);
                $project->update($data);
                AdminActivityLog::log('project_updated', "Updated project: {$project->title}", Project::class, $project->id);
                session()->flash('success', 'Projet mis à jour avec succès!');
            } else {
                $project = Project::create($data);
                AdminActivityLog::log('project_created', "Created project: {$project->title}", Project::class, $project->id);
                session()->flash('success', 'Projet créé avec succès!');
            }

            $this->resetForm();

        } catch (\Exception $e) {
            session()->flash('error', 'Une erreur est survenue lors de l\'enregistrement.');
        }
    }

    public function togglePublish(int $id): void
    {
        $project = Project::findOrFail($id);
        $project->update(['is_published' => !$project->is_published]);
        $status = $project->is_published ? 'published' : 'unpublished';
        AdminActivityLog::log("project_{$status}", "Toggled project: {$project->title} → {$status}", Project::class, $project->id);
    }

    public function toggleFeatured(int $id): void
    {
        $project = Project::findOrFail($id);
        $project->update(['is_featured' => !$project->is_featured]);
    }

    public function delete(int $id): void
    {
        $project = Project::findOrFail($id);
        AdminActivityLog::log('project_deleted', "Deleted project: {$project->title}", Project::class, $project->id);
        $project->delete();
        session()->flash('success', 'Projet supprimé avec succès!');
    }

    protected function resetForm(): void
    {
        $this->reset(['projectId', 'title', 'title_ar', 'description', 'description_ar', 'short_description', 'short_description_ar', 'slug', 'featured_image', 'newImage', 'status', 'location', 'partner', 'tags_input', 'external_link', 'sort_order', 'is_published', 'is_featured']);
        $this->editMode = false;
        $this->showModal = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Project::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('title_ar', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
                  ->orWhere('location', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter === 'published') {
            $query->where('is_published', true);
        } elseif ($this->statusFilter === 'draft') {
            $query->where('is_published', false);
        }

        $projects = $query->orderBy('sort_order')->orderBy('id', 'desc')->paginate(15);

        $stats_card = [
            'total' => [
                'label' => 'Total projets',
                'icon' => 'ri-article-line',
                'data' => Project::count(),
            ],
            'published' => [
                'label' => 'Publiés',
                'icon' => 'ri-check-line',
                'color' => 'text-emerald-600',
                'data' => Project::where('is_published', true)->count(),
            ],
            'drafts' => [
                'label' => 'Brouillons',
                'icon' => 'ri-file-line',
                'data' => Project::where('is_published', false)->count(),
            ],
            'featured' => [
                'label' => 'À la une',
                'icon' => 'ri-star-line',
                'color' => 'text-amber-600',
                'data' => Project::where('is_featured', true)->count(),
            ],
        ];

        return view('livewire.admin.project.project-management', compact('projects', 'stats_card'))
            ->layout('layouts.admin', ['header' => 'Gestion des Projets']);
    }
}
