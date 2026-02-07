<?php

namespace App\Livewire\Admin\Programe;

use App\Models\BusinessPlan;
use Livewire\Component;
use App\Models\Candidat;
use Livewire\WithPagination;

class ProgrameCreate extends Component{


    public function render()
    {

        return view('livewire.admin.programe.create_project', [
        ])->layout('layouts.admin', ['header' => 'Create New Project']);
    }
}