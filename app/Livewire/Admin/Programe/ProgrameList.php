<?php

namespace App\Livewire\Admin\Programe;

use Livewire\Component;
use App\Models\Candidat;
use App\Models\AdminActivityLog;
use App\Models\ProgrameList as Project_list;
use Livewire\WithPagination;

class ProgrameList extends Component
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
        $projects = Project_list::with('user')->withCount('formulaires')->paginate(10);
        return view('livewire.admin.programe.project_list', [
            'projects' => $projects,
            'totalProjects' => Project_list::count(),
        ])->layout('layouts.admin', ['header' => 'Projects Management']);
    }
}