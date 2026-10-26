<?php

namespace App\Livewire\Admin\Project;

use App\Models\Project;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch() { $this->resetPage(); }

    public function togglePublish(int $id): void
    {
        $project = Project::findOrFail($id);
        $project->update(['is_published' => !$project->is_published]);
        $status = $project->is_published ? 'published' : 'unpublished';
        $title = $project->getLocalized('title') ?: ('Projet #' . $project->id);
        AdminActivityLog::log("project_{$status}", "Toggled project: {$title} → {$status}", Project::class, $project->id);
    }

    public function delete(int $id): void
    {
        $project = Project::findOrFail($id);
        $title = $project->getLocalized('title') ?: ('Projet #' . $project->id);
        AdminActivityLog::log('project_deleted', "Deleted project: {$title}", Project::class, $project->id);
        $project->delete();
        session()->flash('success', 'Projet supprimé avec succès!');
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
