<?php

namespace App\Livewire\Formulairestatic;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\BilanCompetence as BilanCompetenceModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class BilanCompetences extends Component
{
    public $step = 1;
    public $totalSteps = 7;
    public $recordId = null;
    public $isReadOnly = false;

    // Step 1 - Axe Personnel
    public $qualites_defauts = [['qualite' => '', 'defaut' => '']];
    public $qualites_contribution = '';
    public $defauts_freins = '';
    public $loisirs = '';

    // Step 2 - Axe de Formation
    public $niveau_etude = '';
    public $diplomes_obtenus = '';
    public $annee_obtention = '';
    public $etablissement_obtention = '';
    public $competences_formation = [['acquise' => '', 'lacune' => '', 'a_developper' => '']];
    public $besoin_formations = '';
    public $type_formations = '';

    // Step 3 - Axe Professionnel
    public $environnement_professionnel = [
        'travail_bureau' => '', 'travail_exterieur' => '', 'travail_equipe' => '',
        'travail_independant' => '', 'horaires_fixes' => '', 'horaires_flexibles' => '', 'deplacement_frequent' => ''
    ];
    public $secteurs_activite = [];

    // Step 4 - Fonctions Envisagées
    public $fonctions_envisagees = [];
    public $representation_travail = [];

    // Step 5 - Contraintes & Exigences
    public $contraintes_acceptees = [
        'deplacement' => '', 'horaires_variables' => '', 'travail_weekend' => '', 'travail_nuit' => '',
        'port_charges' => '', 'travail_exterieur_meteo' => '', 'travail_repetitif' => '', 'pression_resultats' => ''
    ];
    public $exigences = [];
    public $reflexions_personnelles = '';

    // Step 6 - Stages
    public $stage_societe = '';
    public $stage_lieu = '';
    public $stage_secteur = '';
    public $stage_duree = '';
    public $stage_responsabilites = '';
    public $stage_competences = '';
    public $stage_obstacles = '';
    public $stage_reflexions = '';
    public $stage_plu = '';
    public $stage_deplu = '';
    public $stage_appris = '';

    // Step 7 - Expériences Professionnelles
    public $exp_societe = '';
    public $exp_lieu = '';
    public $exp_secteur = '';
    public $exp_duree = '';
    public $exp_responsabilites = '';
    public $exp_competences = '';
    public $exp_obstacles = '';
    public $exp_integration = '';
    public $exp_depart = '';
    public $exp_reflexions = '';

    public function mount()
    {
        $candidat = Auth::guard('candidat')->user();
        $existing = BilanCompetenceModel::where('candidat_id', $candidat->id)
            ->whereIn('status', ['submitted', 'in_review', 'approved', 'rejected'])->first();

        if ($existing) {
            $this->loadExisting($existing, true);
            return;
        }

        $draft = BilanCompetenceModel::where('candidat_id', $candidat->id)
            ->where('status', 'draft')->first();
        if ($draft) {
            $this->loadExisting($draft);
        }
    }

    protected function loadExisting($record, $readOnly = false)
    {
        $this->recordId = $record->id;
        $this->isReadOnly = $readOnly;
        $this->step = $record->current_step > 0 ? $record->current_step : 1;

        $this->qualites_defauts = $record->qualites_defauts ?: [['qualite' => '', 'defaut' => '']];
        $this->qualites_contribution = $record->qualites_contribution ?? '';
        $this->defauts_freins = $record->defauts_freins ?? '';
        $this->loisirs = $record->loisirs ?? '';

        $this->niveau_etude = $record->niveau_etude ?? '';
        $this->diplomes_obtenus = $record->diplomes_obtenus ?? '';
        $this->annee_obtention = $record->annee_obtention ?? '';
        $this->etablissement_obtention = $record->etablissement_obtention ?? '';
        $this->competences_formation = $record->competences_formation ?: [['acquise' => '', 'lacune' => '', 'a_developper' => '']];
        $this->besoin_formations = $record->besoin_formations ?? '';
        $this->type_formations = $record->type_formations ?? '';

        $this->environnement_professionnel = $record->environnement_professionnel ?: $this->environnement_professionnel;
        $this->secteurs_activite = $record->secteurs_activite ?: [];

        $this->fonctions_envisagees = $record->fonctions_envisagees ?: [];
        $this->representation_travail = $record->representation_travail ?: [];

        $this->contraintes_acceptees = $record->contraintes_acceptees ?: $this->contraintes_acceptees;
        $this->exigences = $record->exigences ?: [];
        $this->reflexions_personnelles = $record->reflexions_personnelles ?? '';

        $this->stage_societe = $record->stage_societe ?? '';
        $this->stage_lieu = $record->stage_lieu ?? '';
        $this->stage_secteur = $record->stage_secteur ?? '';
        $this->stage_duree = $record->stage_duree ?? '';
        $this->stage_responsabilites = $record->stage_responsabilites ?? '';
        $this->stage_competences = $record->stage_competences ?? '';
        $this->stage_obstacles = $record->stage_obstacles ?? '';
        $this->stage_reflexions = $record->stage_reflexions ?? '';
        $this->stage_plu = $record->stage_plu ?? '';
        $this->stage_deplu = $record->stage_deplu ?? '';
        $this->stage_appris = $record->stage_appris ?? '';

        $this->exp_societe = $record->exp_societe ?? '';
        $this->exp_lieu = $record->exp_lieu ?? '';
        $this->exp_secteur = $record->exp_secteur ?? '';
        $this->exp_duree = $record->exp_duree ?? '';
        $this->exp_responsabilites = $record->exp_responsabilites ?? '';
        $this->exp_competences = $record->exp_competences ?? '';
        $this->exp_obstacles = $record->exp_obstacles ?? '';
        $this->exp_integration = $record->exp_integration ?? '';
        $this->exp_depart = $record->exp_depart ?? '';
        $this->exp_reflexions = $record->exp_reflexions ?? '';
    }

    protected function getFormData()
    {
        return [
            'qualites_defauts' => $this->qualites_defauts,
            'qualites_contribution' => $this->qualites_contribution,
            'defauts_freins' => $this->defauts_freins,
            'loisirs' => $this->loisirs,
            'niveau_etude' => $this->niveau_etude,
            'diplomes_obtenus' => $this->diplomes_obtenus,
            'annee_obtention' => $this->annee_obtention,
            'etablissement_obtention' => $this->etablissement_obtention,
            'competences_formation' => $this->competences_formation,
            'besoin_formations' => $this->besoin_formations,
            'type_formations' => $this->type_formations,
            'environnement_professionnel' => $this->environnement_professionnel,
            'secteurs_activite' => $this->secteurs_activite,
            'fonctions_envisagees' => $this->fonctions_envisagees,
            'representation_travail' => $this->representation_travail,
            'contraintes_acceptees' => $this->contraintes_acceptees,
            'exigences' => $this->exigences,
            'reflexions_personnelles' => $this->reflexions_personnelles,
            'stage_societe' => $this->stage_societe,
            'stage_lieu' => $this->stage_lieu,
            'stage_secteur' => $this->stage_secteur,
            'stage_duree' => $this->stage_duree,
            'stage_responsabilites' => $this->stage_responsabilites,
            'stage_competences' => $this->stage_competences,
            'stage_obstacles' => $this->stage_obstacles,
            'stage_reflexions' => $this->stage_reflexions,
            'stage_plu' => $this->stage_plu,
            'stage_deplu' => $this->stage_deplu,
            'stage_appris' => $this->stage_appris,
            'exp_societe' => $this->exp_societe,
            'exp_lieu' => $this->exp_lieu,
            'exp_secteur' => $this->exp_secteur,
            'exp_duree' => $this->exp_duree,
            'exp_responsabilites' => $this->exp_responsabilites,
            'exp_competences' => $this->exp_competences,
            'exp_obstacles' => $this->exp_obstacles,
            'exp_integration' => $this->exp_integration,
            'exp_depart' => $this->exp_depart,
            'exp_reflexions' => $this->exp_reflexions,
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
            $data['current_step'] = $this->step;

            if ($this->recordId) {
                BilanCompetenceModel::findOrFail($this->recordId)->update($data);
            } else {
                $record = BilanCompetenceModel::create($data);
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

        try {
            DB::beginTransaction();
            $candidat_id = Auth::guard('candidat')->user()->id;

            if (BilanCompetenceModel::where('candidat_id', $candidat_id)
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
                BilanCompetenceModel::findOrFail($this->recordId)->update($data);
            } else {
                BilanCompetenceModel::create($data);
            }
            DB::commit();
            $this->isReadOnly = true;
            session()->flash('success', 'Formulaire soumis avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function next()
    {
        if (!$this->isReadOnly) $this->saveAsDraft();
        if ($this->step < $this->totalSteps) $this->step++;
        $this->dispatch('scroll-to-top');
    }

    public function back()
    {
        if ($this->step > 1) $this->step--;
        $this->dispatch('scroll-to-top');
    }

    public function addQualiteDefaut()
    {
        $this->qualites_defauts[] = ['qualite' => '', 'defaut' => ''];
    }

    public function removeQualiteDefaut($index)
    {
        unset($this->qualites_defauts[$index]);
        $this->qualites_defauts = array_values($this->qualites_defauts);
    }

    public function addCompetenceFormation()
    {
        $this->competences_formation[] = ['acquise' => '', 'lacune' => '', 'a_developper' => ''];
    }

    public function removeCompetenceFormation($index)
    {
        unset($this->competences_formation[$index]);
        $this->competences_formation = array_values($this->competences_formation);
    }

    public function fillTestData()
    {
        if (!app()->environment('local')) return;

        // Step 1
        $this->qualites_defauts = [
            ['qualite' => 'Persévérant et déterminé', 'defaut' => 'Parfois trop perfectionniste'],
            ['qualite' => 'Bon communicateur', 'defaut' => 'Manque de patience'],
            ['qualite' => 'Créatif et innovant', 'defaut' => 'Difficulté à déléguer'],
        ];
        $this->qualites_contribution = 'Ma persévérance me permet de surmonter les obstacles et ma créativité m\'aide à trouver des solutions innovantes';
        $this->defauts_freins = 'Mon perfectionnisme peut ralentir l\'avancement des projets et mon impatience peut créer des tensions';
        $this->loisirs = 'Lecture, sport (football), voyages, photographie, bénévolat associatif';

        // Step 2
        $this->niveau_etude = 'Bac+3 Licence en Gestion des Entreprises';
        $this->diplomes_obtenus = 'Baccalauréat Sciences Économiques, Licence en Gestion';
        $this->annee_obtention = '2023';
        $this->etablissement_obtention = 'Université Hassan II - Casablanca';
        $this->competences_formation = [
            ['acquise' => 'Comptabilité générale', 'lacune' => 'Comptabilité analytique avancée', 'a_developper' => 'Fiscalité des entreprises'],
            ['acquise' => 'Marketing de base', 'lacune' => 'Marketing digital', 'a_developper' => 'SEO et publicité en ligne'],
        ];
        $this->besoin_formations = 'oui';
        $this->type_formations = 'Formation en marketing digital et en gestion de projet (PMP)';

        // Step 3
        $this->environnement_professionnel = [
            'travail_bureau' => 'oui', 'travail_exterieur' => 'non', 'travail_equipe' => 'oui',
            'travail_independant' => 'oui', 'horaires_fixes' => 'non', 'horaires_flexibles' => 'oui', 'deplacement_frequent' => 'oui'
        ];
        $this->secteurs_activite = ['Commerce', 'Services', 'Artisanat', 'Technologie'];

        // Step 4
        $this->fonctions_envisagees = ['Direction générale', 'Marketing et vente', 'Gestion de projet', 'Conseil'];
        $this->representation_travail = ['Épanouissement personnel', 'Indépendance financière', 'Contribution sociale', 'Créativité'];

        // Step 5
        $this->contraintes_acceptees = [
            'deplacement' => 'oui', 'horaires_variables' => 'oui', 'travail_weekend' => 'non', 'travail_nuit' => 'non',
            'port_charges' => 'non', 'travail_exterieur_meteo' => 'non', 'travail_repetitif' => 'non', 'pression_resultats' => 'oui'
        ];
        $this->exigences = ['Autonomie', 'Créativité', 'Responsabilité', 'Bon salaire', 'Évolution de carrière'];
        $this->reflexions_personnelles = 'Je souhaite créer ma propre entreprise dans le secteur artisanal pour valoriser le patrimoine local';

        // Step 6
        $this->stage_societe = 'Artisanat Moderne SARL';
        $this->stage_lieu = 'Casablanca, Zone Industrielle Ain Sebaa';
        $this->stage_secteur = 'Artisanat et production';
        $this->stage_duree = '3 mois (Juin - Août 2022)';
        $this->stage_responsabilites = 'Suivi de production, gestion des stocks, relation fournisseurs';
        $this->stage_competences = 'Gestion de stock, négociation fournisseurs, contrôle qualité';
        $this->stage_obstacles = 'Ruptures de stock fréquentes, communication difficile avec certains fournisseurs';
        $this->stage_reflexions = 'Ce stage m\'a confirmé mon intérêt pour l\'entrepreneuriat dans l\'artisanat';
        $this->stage_plu = 'L\'ambiance de travail collaborative et la créativité des artisans';
        $this->stage_deplu = 'Le manque d\'organisation administrative et de digitalisation';
        $this->stage_appris = 'J\'ai appris que je suis capable de gérer une équipe et que j\'aime résoudre des problèmes concrets';

        // Step 7
        $this->exp_societe = 'Maroc Artisan Express';
        $this->exp_lieu = 'Rabat, Médina';
        $this->exp_secteur = 'E-commerce artisanal';
        $this->exp_duree = '6 mois (CDD)';
        $this->exp_responsabilites = 'Gestion de la boutique en ligne, service client, coordination des livraisons';
        $this->exp_competences = 'E-commerce, service client, logistique, réseaux sociaux';
        $this->exp_obstacles = 'Retards de livraison, gestion des retours clients';
        $this->exp_integration = 'Formation initiale d\'une semaine, tutorat par le responsable';
        $this->exp_depart = 'Fin de CDD, souhait de créer mon propre projet';
        $this->exp_reflexions = 'Cette expérience m\'a donné une vision complète de la chaîne de valeur artisanale';

        session()->flash('success', 'Données de test du bilan de compétences remplies!');
    }

    public function render()
    {
        return view('livewire.front.bilan_competence.public-form-wizard');
    }
}
