<?php

namespace App\Livewire\Admin\Programe;

use Livewire\Component;
use App\Models\Candidat;
use App\Models\ProgrameList as Project_list;
use Livewire\WithPagination;

class ProgrameList extends Component
{
    use WithPagination;

    public function delete($id)
    {
        $project = Project_list::findOrFail($id);
        $project->delete();
        session()->flash('message', 'Project deleted successfully.');
    }   

    public function render()
    {
        return view('livewire.admin.programe.project_list', [
            'projects' => Project_list::with('user')->paginate(10), 
        ])->layout('layouts.admin', ['header' => 'Projects Management']);
    }
}