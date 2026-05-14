<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use App\Models\Candidat;
use App\Models\AdminActivityLog;
use App\Models\ProjectsList as Project_list;
use Livewire\WithPagination;

class ProjectsList extends Component
{
    use WithPagination;

    public function delete($id)
    {
        $project = Project_list::findOrFail($id);
        $project->delete();

        AdminActivityLog::log(
            'programme_deleted',
            "Deleted programme: {$project->project_name}",
            Project_list::class,
            $project->id
        );

        session()->flash('message', 'Project deleted successfully.');
    }   

    public function render()
    {

            $stats_card = [
            'Total Projets' => [
                'label' => 'Total Projets',
                'icon' => 'ri-article-line',
                'data' => Project_list::count(),
            ],
            'Projets Actives' => [
                'label' => 'Projets Actives',
                'icon' => 'ri-newspaper-line',
                'color' => 'text-blue-600',
                'data' => Project_list::where('is_active', true)->count(),
            ],
            'Projets Inactives' => [
                'label' => 'Projets Inactives',
                'icon' => 'ri-mail-send-line',
                'data' => Project_list::where('is_active', false)->count(),
            ],

        ];
        $projects = Project_list::with('user')->withCount('formulaires')->paginate(10);
        return view('livewire.admin.projects.project_list', [
            'projects'        => $projects,
            'stats_card'       => $stats_card,
        ])->layout('layouts.admin', ['header' => 'Gestion des Projets']);
    }
}