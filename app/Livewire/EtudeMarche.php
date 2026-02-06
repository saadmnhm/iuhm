<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\EtudeMarche as EtudeMarcheModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class EtudeMarche extends Component
{
    public $step = 1;
    public $etudeId = null;
    public $existingEtude = null;
    public $isReadOnly = false;

    // Step 1 - Service/Produit
    public $produit_service, $description_offre, $benefices_clients, $prix_marche, $controle_prix;

    // Step 2 - Clientèle cible
    public $type_clients, $caracteristiques_clientele, $frequence_consommation, $localisation_clients, $exigences_principales;

    // Step 3 - Concurrence
    public $nombre_concurrents_directs, $concurrents_indirects, $taille_concurrents, $informations_concurrents, $communication_concurrents;

    // Step 4 - Approvisionnement
    public $nombre_fournisseurs, $origine_fournisseurs, $prix_fournisseurs, $delais_livraison, $stabilite_marche;

    public function mount()
    {
        $candidat = Auth::guard('candidat')->user();
        $existingSubmitted = EtudeMarcheModel::where('candidat_id', $candidat->id)->whereIn('status', ['submitted', 'in_review', 'approved', 'rejected'])->first();

        if ($existingSubmitted) {
            $this->loadExisting($existingSubmitted->id, true);
            return;
        }

        $draft = EtudeMarcheModel::where('candidat_id', $candidat->id)->where('status', 'draft')->first();
        if ($draft) $this->loadExisting($draft->id);
    }

    protected function loadExisting($id, $readOnly = false)
    {
        $etude = EtudeMarcheModel::findOrFail($id);
        $this->etudeId = $etude->id;
        $this->existingEtude = $etude;
        $this->isReadOnly = $readOnly;
        $this->step = $etude->current_step > 0 ? $etude->current_step : 1;
        foreach ($etude->getAttributes() as $key => $value) {
            if (property_exists($this, $key) && $key !== 'id') $this->$key = $value;
        }
    }

    protected function rules()
    {
        return match ($this->step) {
            1 => ['produit_service' => 'required|string', 'description_offre' => 'nullable|string', 'benefices_clients' => 'nullable|string', 'prix_marche' => 'nullable|string', 'controle_prix' => 'nullable|string'],
            2 => ['type_clients' => 'nullable|string', 'caracteristiques_clientele' => 'nullable|string', 'frequence_consommation' => 'nullable|string', 'localisation_clients' => 'nullable|string', 'exigences_principales' => 'nullable|string'],
            3 => ['nombre_concurrents_directs' => 'nullable|string', 'concurrents_indirects' => 'nullable|string', 'taille_concurrents' => 'nullable|string', 'informations_concurrents' => 'nullable|string', 'communication_concurrents' => 'nullable|string'],
            4 => ['nombre_fournisseurs' => 'nullable|string', 'origine_fournisseurs' => 'nullable|string', 'prix_fournisseurs' => 'nullable|string', 'delais_livraison' => 'nullable|string', 'stabilite_marche' => 'nullable|string'],
            default => [],
        };
    }

    public function saveAsDraft()
    {
        if ($this->isReadOnly) { session()->flash('error', 'Impossible de modifier un formulaire déjà soumis.'); return; }
        try {
            DB::beginTransaction();
            $data = $this->getFormData();
            $data['candidat_id'] = Auth::guard('candidat')->user()->id;
            $data['status'] = 'draft';
            $data['current_step'] = $this->step;
            if ($this->etudeId) {
                EtudeMarcheModel::findOrFail($this->etudeId)->update($data);
            } else {
                $etude = EtudeMarcheModel::create($data);
                $this->etudeId = $etude->id;
            }
            DB::commit();
            session()->flash('success', 'Brouillon sauvegardé!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function submit()
    {
        if ($this->isReadOnly) { session()->flash('error', 'Déjà soumis.'); return; }
        $this->validate();
        try {
            DB::beginTransaction();
            $candidat_id = Auth::guard('candidat')->user()->id;
            if (EtudeMarcheModel::where('candidat_id', $candidat_id)->whereIn('status', ['submitted', 'in_review', 'approved', 'rejected'])->exists()) {
                DB::rollBack(); session()->flash('error', 'Déjà soumis.'); return;
            }
            $data = $this->getFormData();
            $data['candidat_id'] = $candidat_id;
            $data['status'] = 'submitted';
            $data['submitted_at'] = now();
            if ($this->etudeId) {
                $etude = EtudeMarcheModel::findOrFail($this->etudeId);
                $etude->update($data);
            } else {
                $etude = EtudeMarcheModel::create($data);
            }
            DB::commit();
            $this->isReadOnly = true;
            $this->existingEtude = $etude;
            session()->flash('success', 'Formulaire soumis avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur: ' . $e->getMessage());
        }
    }

    protected function getFormData()
    {
        return ['produit_service' => $this->produit_service, 'description_offre' => $this->description_offre, 'benefices_clients' => $this->benefices_clients, 'prix_marche' => $this->prix_marche, 'controle_prix' => $this->controle_prix, 'type_clients' => $this->type_clients, 'caracteristiques_clientele' => $this->caracteristiques_clientele, 'frequence_consommation' => $this->frequence_consommation, 'localisation_clients' => $this->localisation_clients, 'exigences_principales' => $this->exigences_principales, 'nombre_concurrents_directs' => $this->nombre_concurrents_directs, 'concurrents_indirects' => $this->concurrents_indirects, 'taille_concurrents' => $this->taille_concurrents, 'informations_concurrents' => $this->informations_concurrents, 'communication_concurrents' => $this->communication_concurrents, 'nombre_fournisseurs' => $this->nombre_fournisseurs, 'origine_fournisseurs' => $this->origine_fournisseurs, 'prix_fournisseurs' => $this->prix_fournisseurs, 'delais_livraison' => $this->delais_livraison, 'stabilite_marche' => $this->stabilite_marche];
    }

    public function next()
    {
        $this->validate();
        if (!$this->isReadOnly) $this->saveAsDraft();
        $this->step++;
        $this->dispatch('scroll-to-top');
    }

    public function back()
    {
        $this->step--;
        $this->dispatch('scroll-to-top');
    }

    public function fillTestData()
    {
        if (!app()->environment('local')) return;
        $this->produit_service = 'Produits artisanaux locaux - bijoux, poterie, textiles';
        $this->description_offre = 'Offre de produits artisanaux de haute qualité, fabriqués localement avec des matériaux durables';
        $this->benefices_clients = 'Qualité supérieure, produits uniques, soutien aux artisans locaux';
        $this->prix_marche = 'Prix moyens: 50-500 DH selon le produit';
        $this->controle_prix = 'Prix contrôlable basé sur les coûts de production';
        $this->type_clients = 'Particuliers, touristes, boutiques de décoration';
        $this->caracteristiques_clientele = 'Âge 25-55 ans, revenus moyens à élevés';
        $this->frequence_consommation = 'Occasionnelle pour les particuliers, régulière pour les boutiques';
        $this->localisation_clients = 'Casablanca, Rabat, Marrakech, international';
        $this->exigences_principales = 'Qualité artisanale, authenticité, délais respectés';
        $this->nombre_concurrents_directs = '15-20 ateliers artisanaux dans la région';
        $this->concurrents_indirects = 'Oui, produits industriels à bas prix';
        $this->taille_concurrents = 'Petites structures (2-10 employés)';
        $this->informations_concurrents = 'Concurrent A: CA 500K DH/an, 10 ans d\'expérience';
        $this->communication_concurrents = 'Réseaux sociaux, foires artisanales';
        $this->nombre_fournisseurs = '8-10 fournisseurs de matières premières';
        $this->origine_fournisseurs = 'Principalement nationaux (Fès, Marrakech)';
        $this->prix_fournisseurs = 'Prix raisonnables et négociables';
        $this->delais_livraison = 'Délais fiables: 1-2 semaines';
        $this->stabilite_marche = 'Marché stable avec variation saisonnière';
        session()->flash('success', 'Données de test remplies!');
    }

    public function render()
    {
        return view('livewire.front.etude_marche.public-form-wizard');
    }
}
