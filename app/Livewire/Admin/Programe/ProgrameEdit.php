<?php

namespace App\Livewire\Admin\Programe;

use App\Models\BusinessPlan;
use Livewire\Component;
use App\Models\Candidat;
use Livewire\WithPagination;

class ProgrameEdit extends Component{


    public function render()
    {

        return view('livewire.admin.programe.edit_project', [
        ])->layout('layouts.admin', ['header' => 'Edit Project']);
    }
}