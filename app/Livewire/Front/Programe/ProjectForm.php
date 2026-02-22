<?php

namespace App\Livewire\Admin\Programe;

use Livewire\Component;
use App\Models\Candidat;
use App\Models\ProgrameList as Project_list;
use Livewire\WithPagination;

class ProgrameForm extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.front.programe.project_form', [
            'projects' => Project_list::with('user')->paginate(10), 
        ])->layout('layouts.admin', ['header' => 'Projects Management']);
    }
}