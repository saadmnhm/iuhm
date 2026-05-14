<?php

namespace App\Livewire\Admin\Formulaire;

use App\Models\DynamicForm;
use App\Models\DynamicFormSubmission;
use Livewire\Component;

class FormulaireSubmissionDetail extends Component
{
    public int $submissionId;
    public string $reviewNotes = '';
    public bool $showStatusModal = false;
    public string $newStatus = '';

    public function mount($id)
    {
        $this->submissionId = $id;
    }

    public function openStatusModal($status)
    {
        $this->newStatus = $status;
        $this->showStatusModal = true;
    }

    public function updateStatus()
    {
        $submission = DynamicFormSubmission::findOrFail($this->submissionId);
        $submission->update([
            'status' => $this->newStatus,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'review_notes' => $this->reviewNotes ?: $submission->review_notes,
        ]);

        $this->showStatusModal = false;
        $this->dispatch('alert', type: 'success', title: 'Mis à jour', message: 'Statut mis à jour.');
    }

    public function render()
    {
        $submission = DynamicFormSubmission::with([
            'form.steps.fields',
            'form.steps.tables.columns',
            'form.steps.tables.fixedRows',
            'candidat',
            'answers',
            'tableAnswers',
            'reviewer',
        ])->findOrFail($this->submissionId);

        return view('livewire.admin.projects.candidat.details.formulaire-submission-detail', [
            'submission' => $submission,
        ])->layout('layouts.admin', ['header' => 'Détail Soumission']);
    }
}
