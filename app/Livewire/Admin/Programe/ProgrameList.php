<?php

namespace App\Livewire\Admin\Programe;

use App\Models\BusinessPlan;
use Livewire\Component;
use App\Models\Candidat;
use Livewire\WithPagination;

class ProgrameList extends Component{
    use WithPagination;

    public function render()
    {

        return view('livewire.admin.programe.project_list', [
        ])->layout('layouts.admin', ['header' => 'Projects Management']);
    }
}