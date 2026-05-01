<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Candidat;
use App\Models\DynamicFormSubmission;
use App\Models\ProjectSubmission;
use App\Models\ProgrameList;
use Livewire\Component;

class Aside extends Component
{
    public $projectId;


    public function render()
    {

      

        return view('livewire.admin.aside');
    }
    
}
