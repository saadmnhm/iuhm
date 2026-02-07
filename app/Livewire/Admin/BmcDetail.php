<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Bmc;

class BmcDetail extends Component
{
    public $bmc;
    public $submission;

    public function mount($id)
    {
        $this->bmc = Bmc::with('candidat')->findOrFail($id);
        $this->submission = $this->bmc;
    }

    public function updateStatus($status)
    {
        $this->bmc->update(['status' => $status]);
        session()->flash('success', 'Statut mis à jour avec succès!');
    }

    public function render()
    {
        return view('livewire.admin.bmc-detail')
            ->layout('layouts.admin', ['header' => 'Business Model Canvas - Détails']);
    }
}
