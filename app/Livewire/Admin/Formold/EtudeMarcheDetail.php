<?php

namespace App\Livewire\Admin\Formold;

use Livewire\Component;
use App\Models\EtudeMarche;
use App\Models\DynamicFormSubmission;
use App\Services\FormSubmissionService;

class EtudeMarcheDetail extends Component
{
    public $etudeMarche;
    public $submission;

    // Modal state
    public bool $showSubmissionModal = false;
    public ?int $selectedSubmissionId = null;

    public function mount($id)
    {
        $this->etudeMarche = EtudeMarche::with('candidat')->findOrFail($id);
        $this->submission = $this->etudeMarche;
    }

    public function viewSubmission(int $submissionId): void
    {
        $this->selectedSubmissionId = $submissionId;
        $this->showSubmissionModal = true;
    }

    public function closeModal(): void
    {
        $this->showSubmissionModal = false;
        $this->selectedSubmissionId = null;
    }

    public function updateStatus($status)
    {
        $this->etudeMarche->update(['status' => $status]);
        session()->flash('success', 'Statut mis à jour avec succès!');
    }

    public function render()
    {
        $dynamicSubmissions = DynamicFormSubmission::with(['form'])
            ->where('candidat_id', $this->etudeMarche->candidat_id)
            ->orderByDesc('created_at')
            ->get();

        $selectedSubmission = null;
        if ($this->selectedSubmissionId) {
            $selectedSubmission = DynamicFormSubmission::with([
                'form.steps.fields',
                'form.steps.tables.columns',
                'form.steps.tables.fixedRows',
                'candidat',
                'answers',
                'tableAnswers',
                'reviewer',
            ])->find($this->selectedSubmissionId);
        }

        return view('livewire.admin.etude-marche-detail', [
            'dynamicSubmissions' => $dynamicSubmissions,
            'selectedSubmission' => $selectedSubmission,
        ])->layout('layouts.admin', ['header' => 'Étude de Marché - Détails']);
    }
}
