<?php

namespace App\Livewire\Formulairestatic;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\EvaluationIdee as EvaluationIdeeModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class EvaluationIdee extends Component
{
    public $step = 1;
    public $recordId = null;
    public $isReadOnly = false;

    // Q1-Q10
    public $idee_projet = '';
    public $resume_idee = '';
    public $besoin_projet = '';
    public $produits_services = '';
    public $clients_identifies = '';
    public $idee_existe_marche = '';
    public $valeur_ajoutee = '';
    public $resultats_prevus = '';
    public $proches_comprennent = '';
    public $reactions_positives = '';

    public function mount()
    {
        $candidat = Auth::guard('candidat')->user();
        $existing = EvaluationIdeeModel::where('candidat_id', $candidat->id)
            ->whereIn('status', ['submitted', 'in_review', 'approved', 'rejected'])->first();

        if ($existing) {
            $this->loadExisting($existing, true);
            return;
        }

        $draft = EvaluationIdeeModel::where('candidat_id', $candidat->id)
            ->where('status', 'draft')->first();
        if ($draft) {
            $this->loadExisting($draft);
        }
    }

    protected function loadExisting($record, $readOnly = false)
    {
        $this->recordId = $record->id;
        $this->isReadOnly = $readOnly;
        $this->idee_projet = $record->idee_projet ?? '';
        $this->resume_idee = $record->resume_idee ?? '';
        $this->besoin_projet = $record->besoin_projet ?? '';
        $this->produits_services = $record->produits_services ?? '';
        $this->clients_identifies = $record->clients_identifies ?? '';
        $this->idee_existe_marche = $record->idee_existe_marche ?? '';
        $this->valeur_ajoutee = $record->valeur_ajoutee ?? '';
        $this->resultats_prevus = $record->resultats_prevus ?? '';
        $this->proches_comprennent = $record->proches_comprennent ?? '';
        $this->reactions_positives = $record->reactions_positives ?? '';
    }

    protected function getFormData()
    {
        return [
            'idee_projet' => $this->idee_projet,
            'resume_idee' => $this->resume_idee,
            'besoin_projet' => $this->besoin_projet,
            'produits_services' => $this->produits_services,
            'clients_identifies' => $this->clients_identifies,
            'idee_existe_marche' => $this->idee_existe_marche,
            'valeur_ajoutee' => $this->valeur_ajoutee,
            'resultats_prevus' => $this->resultats_prevus,
            'proches_comprennent' => $this->proches_comprennent,
            'reactions_positives' => $this->reactions_positives,
        ];
    }

    public function saveAsDraft()
    {
        if ($this->isReadOnly) {
            session()->flash('error', 'Impossible de modifier un formulaire déjà soumis.');
            return;
        }
        try {
            DB::beginTransaction();
            $data = $this->getFormData();
            $data['candidat_id'] = Auth::guard('candidat')->user()->id;
            $data['status'] = 'draft';
            $data['current_step'] = 1;

            if ($this->recordId) {
                EvaluationIdeeModel::findOrFail($this->recordId)->update($data);
            } else {
                $record = EvaluationIdeeModel::create($data);
                $this->recordId = $record->id;
            }
            DB::commit();
            session()->flash('success', 'Brouillon sauvegardé avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function submit()
    {
        if ($this->isReadOnly) {
            session()->flash('error', 'Formulaire déjà soumis.');
            return;
        }

        $this->validate([
            'idee_projet' => 'required|string',
            'resume_idee' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $candidat_id = Auth::guard('candidat')->user()->id;

            if (EvaluationIdeeModel::where('candidat_id', $candidat_id)
                ->whereIn('status', ['submitted', 'in_review', 'approved', 'rejected'])->exists()) {
                DB::rollBack();
                session()->flash('error', 'Vous avez déjà soumis ce formulaire.');
                return;
            }

            $data = $this->getFormData();
            $data['candidat_id'] = $candidat_id;
            $data['status'] = 'submitted';
            $data['submitted_at'] = now();

            if ($this->recordId) {
                EvaluationIdeeModel::findOrFail($this->recordId)->update($data);
            } else {
                EvaluationIdeeModel::create($data);
            }
            DB::commit();
            $this->isReadOnly = true;
            session()->flash('success', 'Formulaire soumis avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function fillTestData()
    {
        if (!app()->environment('local')) return;
        $this->idee_projet = 'Application mobile de livraison de repas traditionnels';
        $this->resume_idee = 'Une plateforme qui connecte les cuisiniers locaux avec les clients pour la livraison de plats maison traditionnels';
        $this->besoin_projet = 'Besoin de repas faits maison de qualité, accessibles rapidement pour les travailleurs et familles pressées';
        $this->produits_services = 'Plateforme mobile + service de livraison + programme de fidélité';
        $this->clients_identifies = 'Oui: employés de bureau (25-45 ans), familles urbaines, étudiants universitaires';
        $this->idee_existe_marche = 'Des services de livraison existent (Glovo, Jumia Food) mais aucun ne se spécialise dans les repas traditionnels faits maison';
        $this->valeur_ajoutee = 'Authenticité des plats, soutien aux cuisiniers locaux, prix abordables, circuit court';
        $this->resultats_prevus = '500 commandes/mois la première année, 50 cuisiniers partenaires, CA de 600K DH/an';
        $this->proches_comprennent = 'oui';
        $this->reactions_positives = 'oui';
        session()->flash('success', 'Données de test remplies!');
    }

    public function render()
    {
        return view('livewire.front.evaluation_idee.public-form-wizard');
    }
}
