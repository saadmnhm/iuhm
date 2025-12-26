<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Livewire\Component;

class ProjectDetail extends Component
{
    public $projectId;
    public $project;

    public function mount($id)
    {
        $this->projectId = $id;
        $this->loadProject();
    }

    public function loadProject()
    {
        $this->project = Project::with([
            'user',
            'products',
            'employees',
            'presentations',
            'deliveries',
            'equipment',
            'rawMaterials',
            'financials'
        ])->findOrFail($this->projectId);
    }

    public function render()
    {
        return view('livewire.admin.formulaire.project-detail')
            ->layout('layouts.admin', ['header' => 'Project Details']);
    }
}
