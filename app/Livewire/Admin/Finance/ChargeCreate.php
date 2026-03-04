<?php

namespace App\Livewire\Admin\Finance;

use App\Models\FinanceCaisse;
use App\Models\FinanceCharge;
use Livewire\Component;

class ChargeCreate extends Component
{
    public $label = '';
    public $montant = '';
    public $frequence = 'mensuel';
    public $fournisseur = '';
    public $date_echeance = '';
    public $notes = '';

    protected function rules()
    {
        return [
            'label' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'frequence' => 'required|in:mensuel,trimestriel,annuel,ponctuel',
            'fournisseur' => 'nullable|string|max:255',
            'date_echeance' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function save()
    {
        $this->validate();

        $caisse = FinanceCaisse::firstOrCreate(
            ['id' => 1],
            ['label' => 'Caisse Principale', 'solde_initial' => 0, 'created_by' => auth()->id()]
        );

        FinanceCharge::create([
            'caisse_id' => $caisse->id,
            'label' => $this->label,
            'montant' => $this->montant,
            'frequence' => $this->frequence,
            'fournisseur' => $this->fournisseur,
            'date_echeance' => $this->date_echeance ?: null,
            'notes' => $this->notes,
            'created_by' => auth()->id(),
        ]);

        session()->flash('success', 'Charge récurrente créée!');
        return redirect()->route('admin.finance.index');
    }

    public function render()
    {
        return view('livewire.admin.finance.charge-create')
            ->layout('layouts.admin', ['header' => 'Nouvelle Charge Récurrente']);
    }
}
