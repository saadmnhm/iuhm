<?php

namespace App\Livewire\Admin\Formulaire;

use App\Models\DynamicForm;
use App\Models\DynamicFormSubmission;
use Livewire\Component;
use Livewire\WithPagination;

class FormulaireSubmissions extends Component
{
    use WithPagination;

    public int $formId;
    public string $search = '';
    public string $filterStatus = '';

    public function mount($formId)
    {
        $this->formId = $formId;
    }

    public function updateStatus($submissionId, $status)
    {
        $submission = DynamicFormSubmission::findOrFail($submissionId);
        $submission->update([
            'status' => $status,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);
        $this->dispatch('alert', type: 'success', title: 'Mis à jour', message: 'Statut mis à jour.');
    }

    public function render()
    {
        $form = DynamicForm::findOrFail($this->formId);

        $submissions = DynamicFormSubmission::with('candidat')
            ->where('dynamic_form_id', $this->formId)
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->search, function ($q) {
                $q->whereHas('candidat', function ($cq) {
                    $cq->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%")
                        ->orWhere('cin', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.formulaire.formulaire-submissions', [
            'form' => $form,
            'submissions' => $submissions,
        ])->layout('layouts.admin', ['header' => 'Soumissions - ' . $form->title]);
    }
}
