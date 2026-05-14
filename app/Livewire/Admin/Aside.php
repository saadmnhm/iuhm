<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Candidat;
use App\Models\DynamicFormSubmission;
use App\Models\ProjectsSubmission;
use App\Models\ProjectsList;
use Livewire\Component;

class Aside extends Component
{
    public $projectId;


    public function render()
    {

      

        return view('livewire.admin.aside');
    }
    
}
