<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\EtudeMarche;
use App\Services\FormSubmissionService;

class EtudeMarcheDetail extends Component
{
    public $etudeMarche;
    public $submission;

    public function mount($id)
    {
        $this->etudeMarche = EtudeMarche::with('candidat')->findOrFail($id);
        $this->submission = $this->etudeMarche;
    }

    public function updateStatus($status)
    {
        $this->etudeMarche->update(['status' => $status]);
        session()->flash('success', 'Statut mis à jour avec succès!');
    }

    public function render()
    {
        return view('livewire.admin.etude-marche-detail')
            ->layout('layouts.admin', ['header' => 'Étude de Marché - Détails']);
    }
}
