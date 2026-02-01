<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use App\Models\Candidat;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProjectDetail extends Component
{
    public $projectId;
    public $project;
    public $showModal = false;
    public $showStatusModal = false;
    public $registration;
    public $candidat;
    public $newStatus;
    public $reviewNotes;

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
            'financials',
            'reviewer'
        ])->findOrFail($this->projectId);

        $this->candidat = $this->project->candidat;
        $this->newStatus = $this->project->status;
        $this->reviewNotes = $this->project->review_notes;
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

        $oldRegistration = $this->project->registration;
        
        $this->project->update([
            'registration' => $this->registration
        ]);

        // Log the activity
        AdminActivityLog::log(
            'registration_added',
            'Added registration number: ' . $this->registration,
            Project::class,
            $this->project->id,
            [
                'old_registration' => $oldRegistration,
                'new_registration' => $this->registration,
            ]
        );

        $this->showModal = false;
        $this->registration = '';
        $this->loadProject();
        $this->candidat = $this->project->candidat;

        session()->flash('success', 'Matriculation ajoutée avec succès!');
    }
    
    public function openStatusModal()
    {
        $this->showStatusModal = true;
    }
    
    public function closeStatusModal()
    {
        $this->showStatusModal = false;
    }
    
    public function updateStatus()
    {
        $this->validate([
            'newStatus' => 'required|in:draft,submitted,in_review,approved,rejected',
            'reviewNotes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $this->project->status;
        
        $this->project->update([
            'status' => $this->newStatus,
            'review_notes' => $this->reviewNotes,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Log the activity
        AdminActivityLog::log(
            'project_status_changed',
            "Changed project status from {$oldStatus} to {$this->newStatus}",
            Project::class,
            $this->project->id,
            [
                'old_status' => $oldStatus,
                'new_status' => $this->newStatus,
                'review_notes' => $this->reviewNotes,
            ]
        );

        $this->showStatusModal = false;
        $this->loadProject();

        session()->flash('success', 'Project status updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.formulaire.project-detail')
            ->layout('layouts.admin', ['header' => 'Project Details']);
    }
}
