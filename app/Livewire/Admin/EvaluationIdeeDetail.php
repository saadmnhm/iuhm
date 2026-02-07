<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\EvaluationIdee;

class EvaluationIdeeDetail extends Component
{
    public $evaluationIdee;
    public $submission;

    public function mount($id)
    {
        $this->evaluationIdee = EvaluationIdee::with('candidat')->findOrFail($id);
        $this->submission = $this->evaluationIdee;
    }

    public function updateStatus($status)
    {
        $this->evaluationIdee->update(['status' => $status]);
        session()->flash('success', 'Statut mis à jour avec succès!');
    }

    public function render()
    {
        return view('livewire.admin.evaluation-idee-detail')
            ->layout('layouts.admin', ['header' => 'Évaluation d\'Idée - Détails']);
    }
}
