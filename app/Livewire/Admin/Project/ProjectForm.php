<?php

namespace App\Livewire\Admin\Project;

use App\Models\Project;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProjectForm extends Component
{
    use WithFileUploads;

    public ?int $projectId = null;
    public bool $editMode = false;

    public $title, $title_ar, $short_description, $short_description_ar, $description, $description_ar;
    public $featured_image, $newImage;
    public $slug, $status, $location, $partner, $tags_input, $external_link, $sort_order;
    public $is_published = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'title'               => 'required|string|max:255',
            'title_ar'            => 'nullable|string|max:255',
            'short_description'   => 'nullable|string|max:500',
            'short_description_ar'=> 'nullable|string|max:500',
            'description'         => 'required|string',
            'description_ar'      => 'nullable|string',
            'slug'                => 'nullable|string|max:255|unique:projects,slug,' . $this->projectId,
            'featured_image'      => 'nullable|string|max:255',
            'newImage'            => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'status'              => 'nullable|string|max:50',
            'location'            => 'nullable|string|max:255',
            'partner'             => 'nullable|string|max:255',
            'tags_input'          => 'nullable|string|max:500',
            'external_link'       => 'nullable|url|max:255',
            'sort_order'          => 'nullable|integer',
            'is_published'        => 'boolean',
        ];
    }

    public function mount($id = 'new')
    {
        if ($id && $id !== 'new') {
            $this->editMode = true;
            $this->projectId = $id;

            $project = Project::findOrFail($id);
            $this->title = is_array($project->title) ? ($project->title['fr'] ?? $project->title['ar'] ?? reset($project->title) ?? '') : ($project->title ?? '');
            $this->title_ar = is_array($project->title) ? ($project->title['ar'] ?? '') : '';
            $this->short_description = is_array($project->short_description) ? ($project->short_description['fr'] ?? $project->short_description['ar'] ?? reset($project->short_description) ?? '') : ($project->short_description ?? '');
            $this->short_description_ar = is_array($project->short_description) ? ($project->short_description['ar'] ?? '') : '';
            $this->description = is_array($project->description) ? ($project->description['fr'] ?? $project->description['ar'] ?? reset($project->description) ?? '') : ($project->description ?? '');
            $this->description_ar = is_array($project->description) ? ($project->description['ar'] ?? '') : '';
            $this->slug = $project->slug;
            $this->featured_image = $project->featured_image;
            $this->status = $project->status;
            $this->location = $project->location;
            $this->partner = $project->partner;
            $this->tags_input = $project->tags ? implode(', ', $project->tags) : '';
            $this->external_link = $project->external_link;
            $this->sort_order = $project->sort_order;
            $this->is_published = $project->is_published;
        } else {
            $this->editMode = false;
            $this->status = 'planned';
            $this->sort_order = 0;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title'               => ['fr' => $this->title, 'ar' => $this->title_ar ?: null],
            'description'         => ['fr' => $this->description, 'ar' => $this->description_ar ?: null],
            'short_description'   => ['fr' => $this->short_description, 'ar' => $this->short_description_ar ?: null],
            'slug'                => $this->slug ?: Project::generateSlug($this->title),
            'featured_image'      => $this->featured_image ?: null,
            'status'              => $this->status ?: 'planned',
            'location'            => $this->location ?: null,
            'partner'             => $this->partner ?: null,
            'tags'                => $this->tags_input ? array_map('trim', explode(',', $this->tags_input)) : null,
            'external_link'       => $this->external_link ?: null,
            'sort_order'          => $this->sort_order ?: 0,
            'is_published'        => $this->is_published,
        ];

        if ($this->is_published && empty($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        } elseif (!$this->is_published) {
            $data['published_at'] = null;
        }

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
                $this->dispatch('alert', [
                    'type' => 'success',
                    'title' => 'Succès',
                    'message' => 'Projet mis à jour avec succès!'
                ]);
            } else {
                $data['author_id'] = auth()->id();
                $project = Project::create($data);
                AdminActivityLog::log('project_created', "Created project: {$project->title}", Project::class, $project->id);

                $this->editMode = true;
                $this->projectId = $project->id;

                $this->dispatch('alert', [
                    'type' => 'success',
                    'title' => 'Succès',
                    'message' => 'Projet créé avec succès!'
                ]);
            }

            if ($this->newImage) {
                $this->featured_image = $data['featured_image'];
                $this->newImage = null;
            }

        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Erreur',
                'message' => 'Une erreur est survenue lors de l\'enregistrement.'
            ]);
        }
    }

    public function resetForm()
    {
        return redirect()->route('admin.project.index');
    }

    public function render()
    {
        return view('livewire.admin.project.project-form', [
            
        ])->layout('layouts.admin', ['header' => "Gestion des Projets"]);
    }
}
