<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Livewire\Component;

class RegistrationId extends Component
{
    public $projectId;
    public $registration;

    public function mount($id)
    {
        $this->projectId = $id;
        $project = Project::findOrFail($id);
        $this->registration = $project->registration;
    }

    public function updateRegistration()
    {
        $this->validate([
            'registration' => 'required|string|max:255',
        ]);

        $project = Project::findOrFail($this->projectId);
        $project->registration = $this->registration;
        $project->save();

        session()->flash('success', 'Matriculation mise à jour avec succès !');

        return redirect()->route('admin.projects');
    }

    public function render()
    {
        return view('livewire.admin.registration-id')->layout('layouts.admin', ['header' => 'Add Registration']);
    }
}