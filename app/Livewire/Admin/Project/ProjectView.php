<?php

namespace App\Livewire\Admin\Project;

use Livewire\Component;
use App\Models\Candidat;
use Livewire\WithPagination;

class ProjectView extends Component{
    use WithPagination;

    public function render()
    {

        return view('livewire.admin.project.project_view', [
        ])->layout('layouts.admin', ['header' => 'INDH Projects Management']);
    }
}