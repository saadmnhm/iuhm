<?php

namespace App\Livewire\Admin\Formold;

use Livewire\Component;
use App\Models\BilanCompetence;

class BilanCompetenceDetail extends Component
{
    public $bilanCompetence;
    public $submission;

    public function mount($id)
    {
        $this->bilanCompetence = BilanCompetence::with('candidat')->findOrFail($id);
        $this->submission = $this->bilanCompetence;
    }

    public function updateStatus($status)
    {
        $this->bilanCompetence->update(['status' => $status]);
        session()->flash('success', 'Statut mis à jour avec succès!');
    }

    public function render()
    {
        return view('livewire.admin.bilan-competence-detail')
            ->layout('layouts.admin', ['header' => 'Bilan de Compétences - Détails']);
    }
}
