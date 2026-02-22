<?php

namespace App\Livewire\Formulairestatic;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Bmc as BmcModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class Bmc extends Component
{
    public $step = 1;
    public $recordId = null;
    public $isReadOnly = false;

    // 9 BMC Canvas blocks
    public $partenaires_cles = '';
    public $activites_cles = '';
    public $proposition_valeur = '';
    public $relations_clients = '';
    public $segments_clientele = '';
    public $ressources_cles = '';
    public $canaux = '';
    public $structure_couts = '';
    public $flux_revenus = '';

    public function mount()
    {
        $candidat = Auth::guard('candidat')->user();
        $existing = BmcModel::where('candidat_id', $candidat->id)
            ->whereIn('status', ['submitted', 'in_review', 'approved', 'rejected'])->first();

        if ($existing) {
            $this->loadExisting($existing, true);
            return;
        }

        $draft = BmcModel::where('candidat_id', $candidat->id)
            ->where('status', 'draft')->first();
        if ($draft) {
            $this->loadExisting($draft);
        }
    }

    protected function loadExisting($record, $readOnly = false)
    {
        $this->recordId = $record->id;
        $this->isReadOnly = $readOnly;
        $this->partenaires_cles = $record->partenaires_cles ?? '';
        $this->activites_cles = $record->activites_cles ?? '';
        $this->proposition_valeur = $record->proposition_valeur ?? '';
        $this->relations_clients = $record->relations_clients ?? '';
        $this->segments_clientele = $record->segments_clientele ?? '';
        $this->ressources_cles = $record->ressources_cles ?? '';
        $this->canaux = $record->canaux ?? '';
        $this->structure_couts = $record->structure_couts ?? '';
        $this->flux_revenus = $record->flux_revenus ?? '';
    }

    protected function getFormData()
    {
        return [
            'partenaires_cles' => $this->partenaires_cles,
            'activites_cles' => $this->activites_cles,
            'proposition_valeur' => $this->proposition_valeur,
            'relations_clients' => $this->relations_clients,
            'segments_clientele' => $this->segments_clientele,
            'ressources_cles' => $this->ressources_cles,
            'canaux' => $this->canaux,
            'structure_couts' => $this->structure_couts,
            'flux_revenus' => $this->flux_revenus,
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
                BmcModel::findOrFail($this->recordId)->update($data);
            } else {
                $record = BmcModel::create($data);
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
            'proposition_valeur' => 'required|string',
            'segments_clientele' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $candidat_id = Auth::guard('candidat')->user()->id;

            if (BmcModel::where('candidat_id', $candidat_id)
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
                BmcModel::findOrFail($this->recordId)->update($data);
            } else {
                BmcModel::create($data);
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
        $this->partenaires_cles = 'Fournisseurs locaux de matières premières, coopératives artisanales, agences de transport, plateformes e-commerce';
        $this->activites_cles = 'Production artisanale, contrôle qualité, marketing digital, gestion des commandes, service client';
        $this->proposition_valeur = 'Produits artisanaux authentiques de haute qualité, personnalisables, à prix justes, soutenant l\'économie locale';
        $this->relations_clients = 'Service personnalisé, suivi après-vente, programme de fidélité, newsletter mensuelle, SAV réactif';
        $this->segments_clientele = 'Touristes (nationaux et internationaux), décorateurs d\'intérieur, boutiques de cadeaux, collectionneurs d\'art';
        $this->ressources_cles = 'Artisans qualifiés, atelier de production, matières premières de qualité, plateforme en ligne, réseau de distribution';
        $this->canaux = 'Boutique physique, site e-commerce, réseaux sociaux (Instagram, Facebook), marchés artisanaux, partenariats hôteliers';
        $this->structure_couts = 'Matières premières (30%), main d\'œuvre (25%), loyer atelier (15%), marketing (10%), logistique (10%), autres (10%)';
        $this->flux_revenus = 'Ventes directes (60%), commandes personnalisées (25%), ventes en gros aux boutiques (15%)';
        session()->flash('success', 'Données de test BMC remplies!');
    }

    public function render()
    {
        return view('livewire.front.bmc.public-form-wizard');
    }
}
