<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use App\Models\Candidat;
use Livewire\Component;

class ProjectDetail extends Component
{
    public $projectId;
    public $project;
    public $showModal = false;
    public $registration;
    public $candidat;

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
            'candidat',
            'employees',
            'presentations',
            'deliveries',
            'equipment',
            'rawMaterials',
            'financials'
        ])->findOrFail($this->projectId);

        $this->candidat = $this->project->candidat;
    }

    public function saveRegistration()
    {
        $this->validate([
            'registration' => 'required|string|max:255|unique:projects,registration,' . $this->projectId,
        ], [
            'registration.required' => 'Le numéro de matriculation est requis.',
            'registration.unique' => 'Ce numéro de matriculation existe déjà.',
            'registration.max' => 'Le numéro de matriculation ne peut pas dépasser 255 caractères.',
        ]);

        $this->project->update([
            'registration' => $this->registration
        ]);

        $this->showModal = false;
        $this->registration = '';
        $this->loadProject();
        $this->candidat = $this->project->candidat;

        session()->flash('success', 'Matriculation ajoutée avec succès!');
    }

    public function render()
    {
        return view('livewire.admin.formulaire.project-detail')
            ->layout('layouts.admin', ['header' => 'Project Details']);
    }
}
