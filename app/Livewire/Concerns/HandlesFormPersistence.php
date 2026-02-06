<?php

namespace App\Livewire\Concerns;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait HandlesFormPersistence
{
    public $step = 1;
    public $projectId = null;
    public $existingProject = null;
    public $isReadOnly = false;
    public $rows = [];

    // Step 1 - Project Info
    public $candidat_id, $registration, $project_name, $description, $legal_structure, $resume_executif;
    
    // Step 2 - Market Analysis
    public $public_cible, $concurrent, $volume_produits_locaux, $volume_demande;
    public $demande_offre, $motivations_achat, $raison_choix_client;
    
    // Step 3 - Marketing & Timeline
    public $méthodes_marketing, $adaptation_methodes, $differenciation_marketing;
    public $plan_affaires, $obtention_financement, $ouverture_proces;
    public $lancement_recrutement, $ouverture_definitive, $duree;
    
    // Step 4 - Location & Distribution
    public $lieu_projet, $adaptation_lieu, $benefices_from_projet, $valeur_projet;
    
    // Step 5 - Capacities & Investment
    public $step_8_1, $step_8_2, $step_8_3, $step_8_4;
    public $couts_creation, $preparation_entreprise, $achat_machines;
    public $achat_matieres_premieres, $autres_couts, $total;
    
    // Step 5 - Revenue Projections
    public $ventes_premiere_annee, $ventes_deuxieme_annee, $ventes_troisieme_annee;
    public $services_premiere_annee, $services_deuxieme_annee, $services_troisieme_annee;
    public $aide_financiere_premiere_annee, $aide_financiere_deuxieme_annee, $aide_financiere_troisieme_annee;
    public $revenus_financiers_premiere_annee, $revenus_financiers_deuxieme_annee, $revenus_financiers_troisieme_annee;
    public $autres_revenus_premiere_annee, $autres_revenus_deuxieme_annee, $autres_revenus_troisieme_annee;
    public $total_revenus_premiere_annee, $total_revenus_deuxieme_annee, $total_revenus_troisieme_annee;
    
    // Step 6 - Expected Expenses
    public $achat_prevue_premiere_annee, $achat_prevue_deuxieme_annee, $achat_prevue_troisieme_annee;
    public $frais_fonctionnement_premiere_annee, $frais_fonctionnement_deuxieme_annee, $frais_fonctionnement_troisieme_annee;
    public $charges_personnel_premiere_annee, $charges_personnel_deuxieme_annee, $charges_personnel_troisieme_annee;
    public $dettes_premiere_annee, $dettes_deuxieme_annee, $dettes_troisieme_annee;
    public $etablissement_bancaire_premiere_annee, $etablissement_bancaire_deuxieme_annee, $etablissement_bancaire_troisieme_annee;
    public $fournisseurs_premiere_annee, $fournisseurs_deuxieme_annee, $fournisseurs_troisieme_annee;
    public $autres_dettes_premiere_annee, $autres_dettes_deuxieme_annee, $autres_dettes_troisieme_annee;
    public $autres_charges_premiere_annee, $autres_charges_deuxieme_annee, $autres_charges_troisieme_annee;
    public $total_frais_premiere_annee, $total_frais_deuxieme_annee, $total_frais_troisieme_annee;
    
    // Step 6 - Results
    public $revenus_premiere_annee, $revenus_deuxieme_annee, $revenus_troisieme_annee;
    public $depenses_premiere_annee, $depenses_deuxieme_annee, $depenses_troisieme_annee;
    public $resultat_premiere_annee, $resultat_deuxieme_annee, $resultat_troisieme_annee;
    public $generer_profits, $projet_durable;

    /**
     * Each form component must define its own form type
     */
    abstract protected function getFormType(): string;

    /**
     * Boot form persistence - call this from mount()
     */
    public function mountFormPersistence()
    {
        $candidat = Auth::guard('candidat')->user();
        $formType = $this->getFormType();

        // Check if user already has a submitted project for this form type
        $existingSubmitted = Project::where('candidat_id', $candidat->id)
            ->where('form_type', $formType)
            ->whereIn('status', ['submitted', 'in_review', 'approved', 'rejected'])
            ->first();
        
        if ($existingSubmitted) {
            $this->loadExistingProject($existingSubmitted->id, true);
            return;
        }
        
        // Check for existing draft for this form type
        $draft = Project::where('candidat_id', $candidat->id)
            ->where('form_type', $formType)
            ->where('status', 'draft')
            ->first();
        
        if ($draft) {
            $this->loadExistingProject($draft->id);
        }
        
        $this->mountManagesTableRows();
    }

    /**
     * Collect all form data into an array for saving
     */
    protected function getProjectData(): array
    {
        return [
            'registration' => $this->registration,
            'project_name' => $this->project_name,
            'description' => $this->description,
            'legal_structure' => $this->legal_structure,
            'resume_executif' => $this->resume_executif,
            'public_cible' => $this->public_cible,
            'concurrent' => $this->concurrent,
            'volume_produits_locaux' => $this->volume_produits_locaux,
            'volume_demande' => $this->volume_demande,
            'demande_offre' => $this->demande_offre,
            'motivations_achat' => $this->motivations_achat,
            'raison_choix_client' => $this->raison_choix_client,
            'méthodes_marketing' => $this->méthodes_marketing,
            'adaptation_methodes' => $this->adaptation_methodes,
            'differenciation_marketing' => $this->differenciation_marketing,
            'plan_affaires' => $this->plan_affaires,
            'obtention_financement' => $this->obtention_financement,
            'ouverture_proces' => $this->ouverture_proces,
            'lancement_recrutement' => $this->lancement_recrutement,
            'ouverture_definitive' => $this->ouverture_definitive,
            'duree' => $this->duree,
            'lieu_projet' => $this->lieu_projet,
            'adaptation_lieu' => $this->adaptation_lieu,
            'benefices_from_projet' => $this->benefices_from_projet,
            'valeur_projet' => $this->valeur_projet,
            'step_8_1' => $this->step_8_1,
            'step_8_2' => $this->step_8_2,
            'step_8_3' => $this->step_8_3,
            'step_8_4' => $this->step_8_4,
            'couts_creation' => $this->couts_creation,
            'preparation_entreprise' => $this->preparation_entreprise,
            'achat_machines' => $this->achat_machines,
            'achat_matieres_premieres' => $this->achat_matieres_premieres,
            'autres_couts' => $this->autres_couts,
            'total' => $this->total,
            'generer_profits' => $this->generer_profits,
            'projet_durable' => $this->projet_durable,
        ];
    }

    /**
     * Save as draft - auto-saves when navigating between steps
     */
    public function saveAsDraft()
    {
        if ($this->isReadOnly) {
            session()->flash('error', 'Impossible de modifier un formulaire déjà soumis.');
            return;
        }
        
        try {
            DB::beginTransaction();

            $candidat_id = Auth::guard('candidat')->user()->id;

            $projectData = $this->getProjectData();
            $projectData['candidat_id'] = $candidat_id;
            $projectData['form_type'] = $this->getFormType();
            $projectData['status'] = 'draft';
            $projectData['current_step'] = $this->step;

            if ($this->projectId) {
                $project = Project::findOrFail($this->projectId);
                $project->update($projectData);
            } else {
                $project = Project::create($projectData);
                $this->projectId = $project->id;
            }

            $this->saveDraftTables($project);

            DB::commit();
            session()->flash('success', 'Brouillon sauvegardé avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Draft save failed: ' . $e->getMessage());
            session()->flash('error', 'Erreur lors de la sauvegarde: ' . $e->getMessage());
        }
    }
    
    /**
     * Save all related table data
     */
    protected function saveDraftTables($project)
    {
        $project->products()->delete();
        $project->employees()->delete();
        $project->presentations()->delete();
        $project->deliveries()->delete();
        $project->equipment()->delete();
        $project->rawMaterials()->delete();
        $project->financials()->delete();

        foreach ($this->table1Rows as $index => $row) {
            if (!empty($row['product_name']) || !empty($row['description'])) {
                $project->products()->create([
                    'product_name' => $row['product_name'],
                    'description' => $row['description'],
                    'sort_order' => $index,
                ]);
            }
        }

        foreach ($this->table2Rows as $index => $row) {
            if (!empty($row['item'])) {
                $project->employees()->create([
                    'item' => $row['item'],
                    'total_employee_1' => $row['total_employee_1'] ?? 0,
                    'total_employee_2' => $row['total_employee_2'] ?? 0,
                    'sort_order' => $index,
                ]);
            }
        }

        foreach ($this->table3Rows as $index => $row) {
            if (!empty($row['product_name_presentation'])) {
                $project->presentations()->create([
                    'product_name_presentation' => $row['product_name_presentation'],
                    'presentation_methode' => $row['presentation_methode'],
                    'sort_order' => $index,
                ]);
            }
        }

        foreach ($this->table4Rows as $index => $row) {
            if (!empty($row['product_name_livraison'])) {
                $project->deliveries()->create([
                    'product_name_livraison' => $row['product_name_livraison'],
                    'livraison_methode' => $row['livraison_methode'],
                    'sort_order' => $index,
                ]);
            }
        }

        foreach ($this->table5Rows as $index => $row) {
            if (!empty($row['equipement'])) {
                $project->equipment()->create([
                    'equipement' => $row['equipement'],
                    'reference' => $row['reference'],
                    'prix_equipement' => $row['prix_equipement'] ?? 0,
                    'sort_order' => $index,
                ]);
            }
        }

        foreach ($this->table6Rows as $index => $row) {
            if (!empty($row['matiere_premiere'])) {
                $project->rawMaterials()->create([
                    'matiere_premiere' => $row['matiere_premiere'],
                    'comment_procurer' => $row['comment_procurer'],
                    'fournisseur_matiere' => $row['fournisseur_matiere'],
                    'sort_order' => $index,
                ]);
            }
        }

        if ($this->ventes_premiere_annee || $this->ventes_deuxieme_annee || $this->ventes_troisieme_annee) {
            $project->financials()->updateOrCreate(
                ['project_id' => $project->id],
                $this->getFinancialData()
            );
        }
    }

    /**
     * Get financial data array
     */
    protected function getFinancialData(): array
    {
        return [
            'ventes_premiere_annee' => $this->ventes_premiere_annee,
            'ventes_deuxieme_annee' => $this->ventes_deuxieme_annee,
            'ventes_troisieme_annee' => $this->ventes_troisieme_annee,
            'services_premiere_annee' => $this->services_premiere_annee,
            'services_deuxieme_annee' => $this->services_deuxieme_annee,
            'services_troisieme_annee' => $this->services_troisieme_annee,
            'aide_financiere_premiere_annee' => $this->aide_financiere_premiere_annee,
            'aide_financiere_deuxieme_annee' => $this->aide_financiere_deuxieme_annee,
            'aide_financiere_troisieme_annee' => $this->aide_financiere_troisieme_annee,
            'revenus_financiers_premiere_annee' => $this->revenus_financiers_premiere_annee,
            'revenus_financiers_deuxieme_annee' => $this->revenus_financiers_deuxieme_annee,
            'revenus_financiers_troisieme_annee' => $this->revenus_financiers_troisieme_annee,
            'autres_revenus_premiere_annee' => $this->autres_revenus_premiere_annee,
            'autres_revenus_deuxieme_annee' => $this->autres_revenus_deuxieme_annee,
            'autres_revenus_troisieme_annee' => $this->autres_revenus_troisieme_annee,
            'total_revenus_premiere_annee' => $this->total_revenus_premiere_annee,
            'total_revenus_deuxieme_annee' => $this->total_revenus_deuxieme_annee,
            'total_revenus_troisieme_annee' => $this->total_revenus_troisieme_annee,
            'achat_prevue_premiere_annee' => $this->achat_prevue_premiere_annee,
            'achat_prevue_deuxieme_annee' => $this->achat_prevue_deuxieme_annee,
            'achat_prevue_troisieme_annee' => $this->achat_prevue_troisieme_annee,
            'frais_fonctionnement_premiere_annee' => $this->frais_fonctionnement_premiere_annee,
            'frais_fonctionnement_deuxieme_annee' => $this->frais_fonctionnement_deuxieme_annee,
            'frais_fonctionnement_troisieme_annee' => $this->frais_fonctionnement_troisieme_annee,
            'charges_personnel_premiere_annee' => $this->charges_personnel_premiere_annee,
            'charges_personnel_deuxieme_annee' => $this->charges_personnel_deuxieme_annee,
            'charges_personnel_troisieme_annee' => $this->charges_personnel_troisieme_annee,
            'dettes_premiere_annee' => $this->dettes_premiere_annee,
            'dettes_deuxieme_annee' => $this->dettes_deuxieme_annee,
            'dettes_troisieme_annee' => $this->dettes_troisieme_annee,
            'etablissement_bancaire_premiere_annee' => $this->etablissement_bancaire_premiere_annee,
            'etablissement_bancaire_deuxieme_annee' => $this->etablissement_bancaire_deuxieme_annee,
            'etablissement_bancaire_troisieme_annee' => $this->etablissement_bancaire_troisieme_annee,
            'fournisseurs_premiere_annee' => $this->fournisseurs_premiere_annee,
            'fournisseurs_deuxieme_annee' => $this->fournisseurs_deuxieme_annee,
            'fournisseurs_troisieme_annee' => $this->fournisseurs_troisieme_annee,
            'autres_dettes_premiere_annee' => $this->autres_dettes_premiere_annee,
            'autres_dettes_deuxieme_annee' => $this->autres_dettes_deuxieme_annee,
            'autres_dettes_troisieme_annee' => $this->autres_dettes_troisieme_annee,
            'autres_charges_premiere_annee' => $this->autres_charges_premiere_annee,
            'autres_charges_deuxieme_annee' => $this->autres_charges_deuxieme_annee,
            'autres_charges_troisieme_annee' => $this->autres_charges_troisieme_annee,
            'total_frais_premiere_annee' => $this->total_frais_premiere_annee,
            'total_frais_deuxieme_annee' => $this->total_frais_deuxieme_annee,
            'total_frais_troisieme_annee' => $this->total_frais_troisieme_annee,
            'revenus_premiere_annee' => $this->revenus_premiere_annee,
            'revenus_deuxieme_annee' => $this->revenus_deuxieme_annee,
            'revenus_troisieme_annee' => $this->revenus_troisieme_annee,
            'depenses_premiere_annee' => $this->depenses_premiere_annee,
            'depenses_deuxieme_annee' => $this->depenses_deuxieme_annee,
            'depenses_troisieme_annee' => $this->depenses_troisieme_annee,
            'resultat_premiere_annee' => $this->resultat_premiere_annee,
            'resultat_deuxieme_annee' => $this->resultat_deuxieme_annee,
            'resultat_troisieme_annee' => $this->resultat_troisieme_annee,
        ];
    }
    
    /**
     * Load existing project data into form fields
     */
    protected function loadExistingProject($projectId, $readOnly = false)
    {
        $project = Project::with(['products', 'employees', 'presentations', 'deliveries', 'equipment', 'rawMaterials', 'financials'])
            ->findOrFail($projectId);
        
        $this->projectId = $project->id;
        $this->existingProject = $project;
        $this->isReadOnly = $readOnly;
        $this->step = $project->current_step > 0 ? $project->current_step : 1;
        
        // Load basic fields
        $this->registration = $project->registration;
        $this->project_name = $project->project_name;
        $this->description = $project->description;
        $this->legal_structure = $project->legal_structure;
        $this->resume_executif = $project->resume_executif;
        $this->public_cible = $project->public_cible;
        $this->concurrent = $project->concurrent;
        $this->volume_produits_locaux = $project->volume_produits_locaux;
        $this->volume_demande = $project->volume_demande;
        $this->demande_offre = $project->demande_offre;
        $this->motivations_achat = $project->motivations_achat;
        $this->raison_choix_client = $project->raison_choix_client;
        $this->méthodes_marketing = $project->méthodes_marketing;
        $this->adaptation_methodes = $project->adaptation_methodes;
        $this->differenciation_marketing = $project->differenciation_marketing;
        $this->plan_affaires = $project->plan_affaires;
        $this->obtention_financement = $project->obtention_financement;
        $this->ouverture_proces = $project->ouverture_proces;
        $this->lancement_recrutement = $project->lancement_recrutement;
        $this->ouverture_definitive = $project->ouverture_definitive;
        $this->duree = $project->duree;
        $this->lieu_projet = $project->lieu_projet;
        $this->adaptation_lieu = $project->adaptation_lieu;
        $this->benefices_from_projet = $project->benefices_from_projet;
        $this->valeur_projet = $project->valeur_projet;
        $this->step_8_1 = $project->step_8_1;
        $this->step_8_2 = $project->step_8_2;
        $this->step_8_3 = $project->step_8_3;
        $this->step_8_4 = $project->step_8_4;
        $this->couts_creation = $project->couts_creation;
        $this->preparation_entreprise = $project->preparation_entreprise;
        $this->achat_machines = $project->achat_machines;
        $this->achat_matieres_premieres = $project->achat_matieres_premieres;
        $this->autres_couts = $project->autres_couts;
        $this->total = $project->total;
        $this->generer_profits = $project->generer_profits;
        $this->projet_durable = $project->projet_durable;
        
        // Load tables
        $this->table1Rows = $project->products->map(fn($item) => [
            'product_name' => $item->product_name, 'description' => $item->description
        ])->toArray() ?: [['product_name' => '', 'description' => '']];
        
        $this->table2Rows = $project->employees->map(fn($item) => [
            'item' => $item->item, 'total_employee_1' => $item->total_employee_1, 'total_employee_2' => $item->total_employee_2
        ])->toArray() ?: [['item' => '', 'total_employee_1' => 0, 'total_employee_2' => 0]];
        
        $this->table3Rows = $project->presentations->map(fn($item) => [
            'product_name_presentation' => $item->product_name_presentation, 'presentation_methode' => $item->presentation_methode
        ])->toArray() ?: [['product_name_presentation' => '', 'presentation_methode' => '']];
        
        $this->table4Rows = $project->deliveries->map(fn($item) => [
            'product_name_livraison' => $item->product_name_livraison, 'livraison_methode' => $item->livraison_methode
        ])->toArray() ?: [['product_name_livraison' => '', 'livraison_methode' => '']];
        
        $this->table5Rows = $project->equipment->map(fn($item) => [
            'equipement' => $item->equipement, 'reference' => $item->reference, 'prix_equipement' => $item->prix_equipement
        ])->toArray() ?: array_fill(0, 15, ['equipement' => '', 'reference' => '', 'prix_equipement' => 0]);
        
        $this->table6Rows = $project->rawMaterials->map(fn($item) => [
            'matiere_premiere' => $item->matiere_premiere, 'comment_procurer' => $item->comment_procurer, 'fournisseur_matiere' => $item->fournisseur_matiere
        ])->toArray() ?: array_fill(0, 15, ['matiere_premiere' => '', 'comment_procurer' => '', 'fournisseur_matiere' => '']);
        
        // Load financials
        if ($project->financials) {
            $f = $project->financials;
            $this->ventes_premiere_annee = $f->ventes_premiere_annee;
            $this->ventes_deuxieme_annee = $f->ventes_deuxieme_annee;
            $this->ventes_troisieme_annee = $f->ventes_troisieme_annee;
            $this->services_premiere_annee = $f->services_premiere_annee;
            $this->services_deuxieme_annee = $f->services_deuxieme_annee;
            $this->services_troisieme_annee = $f->services_troisieme_annee;
            $this->aide_financiere_premiere_annee = $f->aide_financiere_premiere_annee;
            $this->aide_financiere_deuxieme_annee = $f->aide_financiere_deuxieme_annee;
            $this->aide_financiere_troisieme_annee = $f->aide_financiere_troisieme_annee;
            $this->revenus_financiers_premiere_annee = $f->revenus_financiers_premiere_annee;
            $this->revenus_financiers_deuxieme_annee = $f->revenus_financiers_deuxieme_annee;
            $this->revenus_financiers_troisieme_annee = $f->revenus_financiers_troisieme_annee;
            $this->autres_revenus_premiere_annee = $f->autres_revenus_premiere_annee;
            $this->autres_revenus_deuxieme_annee = $f->autres_revenus_deuxieme_annee;
            $this->autres_revenus_troisieme_annee = $f->autres_revenus_troisieme_annee;
            $this->total_revenus_premiere_annee = $f->total_revenus_premiere_annee;
            $this->total_revenus_deuxieme_annee = $f->total_revenus_deuxieme_annee;
            $this->total_revenus_troisieme_annee = $f->total_revenus_troisieme_annee;
            $this->achat_prevue_premiere_annee = $f->achat_prevue_premiere_annee;
            $this->achat_prevue_deuxieme_annee = $f->achat_prevue_deuxieme_annee;
            $this->achat_prevue_troisieme_annee = $f->achat_prevue_troisieme_annee;
            $this->frais_fonctionnement_premiere_annee = $f->frais_fonctionnement_premiere_annee;
            $this->frais_fonctionnement_deuxieme_annee = $f->frais_fonctionnement_deuxieme_annee;
            $this->frais_fonctionnement_troisieme_annee = $f->frais_fonctionnement_troisieme_annee;
            $this->charges_personnel_premiere_annee = $f->charges_personnel_premiere_annee;
            $this->charges_personnel_deuxieme_annee = $f->charges_personnel_deuxieme_annee;
            $this->charges_personnel_troisieme_annee = $f->charges_personnel_troisieme_annee;
            $this->dettes_premiere_annee = $f->dettes_premiere_annee;
            $this->dettes_deuxieme_annee = $f->dettes_deuxieme_annee;
            $this->dettes_troisieme_annee = $f->dettes_troisieme_annee;
            $this->etablissement_bancaire_premiere_annee = $f->etablissement_bancaire_premiere_annee;
            $this->etablissement_bancaire_deuxieme_annee = $f->etablissement_bancaire_deuxieme_annee;
            $this->etablissement_bancaire_troisieme_annee = $f->etablissement_bancaire_troisieme_annee;
            $this->fournisseurs_premiere_annee = $f->fournisseurs_premiere_annee;
            $this->fournisseurs_deuxieme_annee = $f->fournisseurs_deuxieme_annee;
            $this->fournisseurs_troisieme_annee = $f->fournisseurs_troisieme_annee;
            $this->autres_dettes_premiere_annee = $f->autres_dettes_premiere_annee;
            $this->autres_dettes_deuxieme_annee = $f->autres_dettes_deuxieme_annee;
            $this->autres_dettes_troisieme_annee = $f->autres_dettes_troisieme_annee;
            $this->autres_charges_premiere_annee = $f->autres_charges_premiere_annee;
            $this->autres_charges_deuxieme_annee = $f->autres_charges_deuxieme_annee;
            $this->autres_charges_troisieme_annee = $f->autres_charges_troisieme_annee;
            $this->total_frais_premiere_annee = $f->total_frais_premiere_annee;
            $this->total_frais_deuxieme_annee = $f->total_frais_deuxieme_annee;
            $this->total_frais_troisieme_annee = $f->total_frais_troisieme_annee;
            $this->revenus_premiere_annee = $f->revenus_premiere_annee;
            $this->revenus_deuxieme_annee = $f->revenus_deuxieme_annee;
            $this->revenus_troisieme_annee = $f->revenus_troisieme_annee;
            $this->depenses_premiere_annee = $f->depenses_premiere_annee;
            $this->depenses_deuxieme_annee = $f->depenses_deuxieme_annee;
            $this->depenses_troisieme_annee = $f->depenses_troisieme_annee;
            $this->resultat_premiere_annee = $f->resultat_premiere_annee;
            $this->resultat_deuxieme_annee = $f->resultat_deuxieme_annee;
            $this->resultat_troisieme_annee = $f->resultat_troisieme_annee;
        }
    }

    /**
     * Submit the form (one-time only per form type)
     */
    public function submit()
    {
        if ($this->isReadOnly) {
            session()->flash('error', 'Ce formulaire a déjà été soumis et ne peut pas être modifié.');
            return;
        }
        
        try {
            DB::beginTransaction();

            $candidat_id = Auth::guard('candidat')->user()->id;
            
            // Double-check: prevent duplicate submissions
            $alreadySubmitted = Project::where('candidat_id', $candidat_id)
                ->where('form_type', $this->getFormType())
                ->whereIn('status', ['submitted', 'in_review', 'approved', 'rejected'])
                ->exists();
            
            if ($alreadySubmitted) {
                DB::rollBack();
                session()->flash('error', 'Vous avez déjà soumis ce formulaire.');
                return;
            }
            
            $projectData = $this->getProjectData();
            $projectData['candidat_id'] = $candidat_id;
            $projectData['form_type'] = $this->getFormType();
            $projectData['status'] = 'submitted';
            $projectData['current_step'] = $this->step;
            $projectData['submitted_at'] = now();

            if ($this->projectId) {
                $project = Project::findOrFail($this->projectId);
                $project->update($projectData);
            } else {
                $project = Project::create($projectData);
            }

            $this->saveDraftTables($project);

            DB::commit();

            $this->isReadOnly = true;
            $this->existingProject = $project;

            session()->flash('success', 'Formulaire soumis avec succès! Votre demande est en cours de traitement.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Form submission failed: ' . $e->getMessage());
            session()->flash('error', 'Erreur lors de la soumission: ' . $e->getMessage());
        }
    }

    /**
     * Clear all form data
     */
    protected function clearFormData()
    {
        $this->project_name = null;
        $this->description = null;
        $this->registration = null;
        $this->legal_structure = null;
        $this->resume_executif = null;
        $this->public_cible = null;
        $this->concurrent = null;
        $this->volume_produits_locaux = null;
        $this->volume_demande = null;
        $this->demande_offre = null;
        $this->motivations_achat = null;
        $this->raison_choix_client = null;
        $this->méthodes_marketing = null;
        $this->adaptation_methodes = null;
        $this->differenciation_marketing = null;
        $this->plan_affaires = null;
        $this->obtention_financement = null;
        $this->ouverture_proces = null;
        $this->lancement_recrutement = null;
        $this->ouverture_definitive = null;
        $this->duree = null;
        $this->lieu_projet = null;
        $this->adaptation_lieu = null;
        $this->benefices_from_projet = null;
        $this->valeur_projet = null;
        $this->step_8_1 = null;
        $this->step_8_2 = null;
        $this->step_8_3 = null;
        $this->step_8_4 = null;
        $this->couts_creation = null;
        $this->preparation_entreprise = null;
        $this->achat_machines = null;
        $this->achat_matieres_premieres = null;
        $this->autres_couts = null;
        $this->total = null;
        
        $this->table1Rows = [['product_name' => '', 'description' => '']];
        $this->table2Rows = [['item' => '', 'total_employee_1' => 0, 'total_employee_2' => 0]];
        $this->table3Rows = [['product_name_presentation' => '', 'presentation_methode' => '']];
        $this->table4Rows = [['product_name_livraison' => '', 'livraison_methode' => '']];
        $this->table5Rows = array_fill(0, 15, ['equipement' => '', 'reference' => '', 'prix_equipement' => 0]);
        $this->table6Rows = array_fill(0, 15, ['matiere_premiere' => '', 'comment_procurer' => '', 'fournisseur_matiere' => '']);
    }

    /**
     * Navigate to next step with validation and auto-save
     */
    public function next()
    {
        $this->validate();
        
        if (!$this->isReadOnly) {
            $this->saveAsDraft();
        }
        
        $this->step++;
        $this->dispatch('scroll-to-top');
    }

    /**
     * Navigate to previous step
     */
    public function back()
    {
        $this->step--;
        $this->dispatch('scroll-to-top');
    }

    /**
     * Calculate investment total
     */
    public function updatedCoutsCreation() { $this->calculateInvestmentTotal(); }
    public function updatedPreparationEntreprise() { $this->calculateInvestmentTotal(); }
    public function updatedAchatMachines() { $this->calculateInvestmentTotal(); }
    public function updatedAchatMatieresPremieres() { $this->calculateInvestmentTotal(); }
    public function updatedAutresCouts() { $this->calculateInvestmentTotal(); }

    private function calculateInvestmentTotal()
    {
        $this->total = floatval($this->couts_creation ?? 0)
            + floatval($this->preparation_entreprise ?? 0)
            + floatval($this->achat_machines ?? 0)
            + floatval($this->achat_matieres_premieres ?? 0)
            + floatval($this->autres_couts ?? 0);
    }

    /**
     * Calculate net result
     */
    public function updatedRevenusPremiereAnnee() { $this->calculateResultatNet(); }
    public function updatedRevenusDeuxiemeAnnee() { $this->calculateResultatNet(); }
    public function updatedRevenusTroisiemeAnnee() { $this->calculateResultatNet(); }
    public function updatedDepensesPremiereAnnee() { $this->calculateResultatNet(); }
    public function updatedDepensesDeuxiemeAnnee() { $this->calculateResultatNet(); }
    public function updatedDepensesTroisiemeAnnee() { $this->calculateResultatNet(); }

    private function calculateResultatNet()
    {
        $this->resultat_premiere_annee = floatval($this->revenus_premiere_annee ?? 0) - floatval($this->depenses_premiere_annee ?? 0);
        $this->resultat_deuxieme_annee = floatval($this->revenus_deuxieme_annee ?? 0) - floatval($this->depenses_deuxieme_annee ?? 0);
        $this->resultat_troisieme_annee = floatval($this->revenus_troisieme_annee ?? 0) - floatval($this->depenses_troisieme_annee ?? 0);
    }

    /**
     * Go to a specific step (dev only)
     */
    public function goToStep($stepNumber)
    {
        if (!app()->environment('local')) {
            return;
        }
        $this->step = $stepNumber;
    }

    /**
     * Fill form with test data for development
     */
    public function fillTestData()
    {
        if (!app()->environment('local')) {
            return;
        }

        $this->registration = 'TEST-' . rand(1000, 9999);
        $this->project_name = 'Test Project ' . rand(100, 999);
        $this->description = 'This is a test project description with sample data for development purposes.';
        $this->legal_structure = 'SARL';
        $this->resume_executif = 'Test executive summary for development testing.';
        
        $this->public_cible = 'Target audience test data';
        $this->concurrent = 'Competitor analysis test data';
        $this->volume_produits_locaux = '1000';
        $this->volume_demande = '2000';
        $this->demande_offre = 'Supply/demand test data';
        $this->motivations_achat = 'Purchase motivation test data';
        $this->raison_choix_client = 'Client choice reason test data';
        
        $this->méthodes_marketing = 'Marketing methods test data';
        $this->adaptation_methodes = 'Method adaptation test data';
        $this->differenciation_marketing = 'Marketing differentiation test data';
        $this->plan_affaires = 'Business plan timeline test';
        $this->obtention_financement = 'Financing timeline test';
        $this->ouverture_proces = 'Process opening timeline test';
        $this->lancement_recrutement = 'Recruitment timeline test';
        $this->ouverture_definitive = 'Final opening timeline test';
        $this->duree = '12 months';
        
        $this->lieu_projet = 'Project location test';
        $this->adaptation_lieu = 'Location adaptation test';
        $this->benefices_from_projet = 'Project benefits test';
        $this->valeur_projet = '500000';
        
        $this->step_8_1 = 'Capacity 1 test';
        $this->step_8_2 = 'Capacity 2 test';
        $this->step_8_3 = 'Capacity 3 test';
        $this->step_8_4 = 'Capacity 4 test';
        
        $this->couts_creation = 50000;
        $this->preparation_entreprise = 30000;
        $this->achat_machines = 100000;
        $this->achat_matieres_premieres = 20000;
        $this->autres_couts = 10000;
        $this->calculateInvestmentTotal();
        
        $this->ventes_premiere_annee = 200000;
        $this->ventes_deuxieme_annee = 250000;
        $this->ventes_troisieme_annee = 300000;
        $this->services_premiere_annee = 50000;
        $this->services_deuxieme_annee = 60000;
        $this->services_troisieme_annee = 70000;
        
        $this->achat_prevue_premiere_annee = 80000;
        $this->achat_prevue_deuxieme_annee = 90000;
        $this->achat_prevue_troisieme_annee = 100000;
        $this->frais_fonctionnement_premiere_annee = 40000;
        $this->frais_fonctionnement_deuxieme_annee = 45000;
        $this->frais_fonctionnement_troisieme_annee = 50000;
        $this->charges_personnel_premiere_annee = 60000;
        $this->charges_personnel_deuxieme_annee = 65000;
        $this->charges_personnel_troisieme_annee = 70000;
        
        $this->generer_profits = 'Yes';
        $this->projet_durable = 'Yes';
        
        session()->flash('success', 'Test data filled successfully!');
    }
}
