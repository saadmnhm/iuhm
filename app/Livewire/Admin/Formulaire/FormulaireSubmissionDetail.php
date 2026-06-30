<?php

namespace App\Livewire\Admin\Formulaire;

use App\Models\DynamicForm;
use App\Models\DynamicFormAnswer;
use App\Models\DynamicFormSubmission;
use App\Models\DynamicFormTableAnswer;
use Livewire\Component;

class FormulaireSubmissionDetail extends Component
{
    public int $submissionId;
    public string $reviewNotes = '';
    public bool $showStatusModal = false;
    public string $newStatus = '';
    public bool $showEditAnswerModal = false;
    public string $editAnswerType = 'field';
    public ?string $editFieldKey = null;
    public ?string $editTableKey = null;
    public ?int $editRowIndex = null;
    public ?string $editColumnKey = null;
    public string $editAnswerValue = '';

    public function mount($id)
    {
        $this->submissionId = $id;
    }

    public function openStatusModal($status)
    {
        $this->newStatus = $status;
        $this->showStatusModal = true;
    }

    public function openEditAnswerModal(string $type, ?string $fieldKey = null, ?string $tableKey = null, ?int $rowIndex = null, ?string $columnKey = null, ?string $currentValue = null): void
    {
        $this->showEditAnswerModal = true;
        $this->editAnswerType = $type === 'table' ? 'table' : 'field';
        $this->editFieldKey = $fieldKey;
        $this->editTableKey = $tableKey;
        $this->editRowIndex = $rowIndex;
        $this->editColumnKey = $columnKey;
        $this->editAnswerValue = (string) ($currentValue ?? '');
    }

    public function closeEditAnswerModal(): void
    {
        $this->showEditAnswerModal = false;
        $this->editAnswerType = 'field';
        $this->editFieldKey = null;
        $this->editTableKey = null;
        $this->editRowIndex = null;
        $this->editColumnKey = null;
        $this->editAnswerValue = '';
    }

    public function saveEditedAnswer(): void
    {
        $this->validate([
            'editAnswerValue' => 'nullable|string',
        ]);

        $submission = DynamicFormSubmission::findOrFail($this->submissionId);

        if ($this->editAnswerType === 'table') {
            DynamicFormTableAnswer::updateOrCreate(
                [
                    'dynamic_form_submission_id' => $submission->id,
                    'table_key' => $this->editTableKey,
                    'row_index' => $this->editRowIndex,
                    'column_key' => $this->editColumnKey,
                ],
                [
                    'value' => $this->editAnswerValue,
                ]
            );
        } else {
            DynamicFormAnswer::updateOrCreate(
                [
                    'dynamic_form_submission_id' => $submission->id,
                    'field_key' => $this->editFieldKey,
                ],
                [
                    'value' => $this->editAnswerValue,
                ]
            );
        }

        $this->showEditAnswerModal = false;
        $this->dispatch('alert', type: 'success', title: 'Mis à jour', message: 'Réponse mise à jour.');
        $this->editAnswerType = 'field';
        $this->editFieldKey = null;
        $this->editTableKey = null;
        $this->editRowIndex = null;
        $this->editColumnKey = null;
        $this->editAnswerValue = '';
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
