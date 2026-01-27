<?php

namespace App\Livewire;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Concerns\ManagesTableRows;
use App\Livewire\Concerns\HasValidationRules;
use App\Models\Project;
use App\Models\ProjectDelivery;
use App\Models\ProjectEmployee;
use App\Models\ProjectPresentation;
use App\Models\ProjectProduct;
use App\Models\ProjectEquipment;
use App\Models\ProjectRawMaterial;
use App\Models\ProjectFinancial;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class PublicFormWizard extends Component
{
    use ManagesTableRows, HasValidationRules, WithFileUploads;
    
    public $step = 1;

    public $rows = [];

    public function mount()
    {
        // Initialize tables if empty
        $this->mountManagesTableRows();
    }
        // Step 1 - Project Info
    public $profile_image, $age,$registration, $gender, $address_house, $email, $phone, $project_name, $ceo_name, $description, $legal_structure, $resume_executif;
    
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
    
    // Step 5 - Revenue Projections (renamed to avoid conflicts with step 6)
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




    protected function rules()
    {
        return match ($this->step) {
            1 => $this->step1Rules(),
            2 => $this->step2Rules(),
            3 => $this->step3Rules(),
            4 => $this->step4Rules(),
            5 => $this->step5Rules(),
            6 => $this->step6Rules(),
            7 => $this->step7Rules(),
            8 => $this->step8Rules(),
            default => [],
        };
    }

    public function next()
    {
        $this->validate();
        $this->step++;
        $this->dispatch('scroll-to-top');
    }

    public function back()
    {
            $this->step--;
            $this->dispatch('scroll-to-top');
    }

    public function submit()
    {
        \Log::info('Submit method called');
        
        $this->validate();

        \Log::info('Validation passed');

        try {
            DB::beginTransaction();

            \Log::info('Transaction started');

            // Upload profile image if exists
            $imagePath = null;
            if ($this->profile_image) {
                $imagePath = $this->profile_image->store('profile-images', 'uploads');
                \Log::info('Image uploaded: ' . $imagePath);
            }

            // Create main project
            $project = Project::create([
                'profile_image' => $imagePath,
                'age' => $this->age,
                'registration' => $this->registration,
                'gender' => $this->gender,
                'address' => $this->address_house,
                'email' => $this->email,
                'phone' => $this->phone,
                'project_name' => $this->project_name,
                'ceo_name' => $this->ceo_name,
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
                'status' => 'submitted',
                'current_step' => 8,
            ]);

            \Log::info('Project created with ID: ' . $project->id);

            // Save table1Rows - Products
            foreach ($this->table1Rows as $index => $row) {
                if (!empty($row['product_name']) || !empty($row['description'])) {
                    $project->products()->create([
                        'product_name' => $row['product_name'],
                        'description' => $row['description'],
                        'sort_order' => $index,
                    ]);
                }
            }

            // Save table2Rows - Employees
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

            // Save table3Rows - Presentations
            foreach ($this->table3Rows as $index => $row) {
                if (!empty($row['product_name_presentation'])) {
                    $project->presentations()->create([
                        'product_name_presentation' => $row['product_name_presentation'],
                        'presentation_methode' => $row['presentation_methode'],
                        'sort_order' => $index,
                    ]);
                }
            }

            // Save table4Rows - Deliveries
            foreach ($this->table4Rows as $index => $row) {
                if (!empty($row['product_name_livraison'])) {
                    $project->deliveries()->create([
                        'product_name_livraison' => $row['product_name_livraison'],
                        'livraison_methode' => $row['livraison_methode'],
                        'sort_order' => $index,
                    ]);
                }
            }

            // Save table5Rows - Equipment
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

            // Save table6Rows - Raw Materials
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

            // Save Financial Data
            $project->financials()->create([
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
            ]);

            DB::commit();

            session()->flash('success', 'Project submitted successfully! Project ID: ' . $project->id);

            // Clear form data but keep table structure
            $this->clearFormData();
            $this->step = 1;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Project submission failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Error saving project: ' . $e->getMessage());
        }
    }

    protected function clearFormData()
    {
        // Clear all fields except tables
        $this->profile_image = null;
        $this->age = null;
        $this->gender = null;
        $this->address_house = null;
        $this->email = null;
        $this->phone = null;
        $this->project_name = null;
        $this->ceo_name = null;
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
        
        // Reinitialize tables with empty structure
        $this->table1Rows = [['product_name' => '', 'description' => '']];
        $this->table2Rows = [['item' => '', 'total_employee_1' => 0, 'total_employee_2' => 0]];
        $this->table3Rows = [['product_name_presentation' => '', 'presentation_methode' => '']];
        $this->table4Rows = [['product_name_livraison' => '', 'livraison_methode' => '']];
        $this->table5Rows = array_fill(0, 15, ['equipement' => '', 'reference' => '', 'prix_equipement' => 0]);
        $this->table6Rows = array_fill(0, 15, ['matiere_premiere' => '', 'comment_procurer' => '', 'fournisseur_matiere' => '']);
    }

    public function render()
    {
        return view('livewire.front.steps.public-form-wizard');
    }

    public function save()
    {
        // Handle saving rows to DB
        foreach ($this->rows as $row) {
            // Example: User::create($row);
        }
        session()->flash('message', 'Rows saved successfully!');
    }

    public function fillTestData()
    {
        // Only allow in development
        if (!app()->environment('local')) {
            return;
        }

        // Step 0 - Personal Info
        $this->age = 25;
        $this->gender = 'homme';
        $this->address_house = 'Hay Mohamadi';
        $this->email = 'test@example.com';
        $this->phone = '0612345678';
        
        // Step 1 - Project Info
        $this->project_name = 'Mon Projet Test';
        $this->ceo_name = 'Ahmed Hassan';
        $this->description = 'Description du projet de test';
        $this->legal_structure = 'SARL';
        $this->resume_executif = 'Résumé exécutif du projet';
        
        // Step 2 - Market Analysis
        $this->public_cible = 'Jeunes entrepreneurs';
        $this->concurrent = 'Concurrent A, Concurrent B';
        $this->volume_produits_locaux = 'Volume moyen';
        $this->volume_demande = 'Forte demande';
        $this->demande_offre = 'Équilibrée';
        $this->motivations_achat = 'Qualité et prix';
        $this->raison_choix_client = 'Meilleur rapport qualité-prix';
        
        // Step 3 - Marketing
        $this->méthodes_marketing = 'Réseaux sociaux, publicité locale';
        $this->adaptation_methodes = 'Adaptation selon le budget';
        $this->differenciation_marketing = 'Prix compétitifs';
        $this->plan_affaires = 'Janvier 2026';
        $this->obtention_financement = 'Février 2026';
        $this->ouverture_proces = 'Mars 2026';
        $this->lancement_recrutement = 'Avril 2026';
        $this->ouverture_definitive = 'Mai 2026';
        $this->duree = '6 mois';
        
        // Step 4 - Location
        $this->lieu_projet = 'Casablanca, Hay Mohamadi';
        $this->adaptation_lieu = 'Oui, très adapté';
        $this->benefices_from_projet = 'Revenus mensuels stables';
        $this->valeur_projet = 'Bénéfices + expérience + réseau';
        
        // Step 5 - Capacities & Investment
        $this->step_8_1 = 'Oui, compétences acquises';
        $this->step_8_2 = 'Oui, matériel disponible';
        $this->step_8_3 = 'Oui, 5 ans d\'expérience';
        $this->step_8_4 = 'Oui, fonds disponibles';
        $this->couts_creation = 10000;
        $this->preparation_entreprise = 5000;
        $this->achat_machines = 20000;
        $this->achat_matieres_premieres = 8000;
        $this->autres_couts = 3000;
        
        // Step 5 - Revenue Projections
        $this->ventes_premiere_annee = 50000;
        $this->ventes_deuxieme_annee = 75000;
        $this->ventes_troisieme_annee = 100000;
        $this->services_premiere_annee = 20000;
        $this->services_deuxieme_annee = 30000;
        $this->services_troisieme_annee = 40000;
        $this->aide_financiere_premiere_annee = 10000;
        $this->aide_financiere_deuxieme_annee = 5000;
        $this->aide_financiere_troisieme_annee = 0;
        $this->revenus_financiers_premiere_annee = 2000;
        $this->revenus_financiers_deuxieme_annee = 3000;
        $this->revenus_financiers_troisieme_annee = 4000;
        $this->autres_revenus_premiere_annee = 1000;
        $this->autres_revenus_deuxieme_annee = 2000;
        $this->autres_revenus_troisieme_annee = 3000;
        $this->total_revenus_premiere_annee = 83000;
        $this->total_revenus_deuxieme_annee = 115000;
        $this->total_revenus_troisieme_annee = 147000;
        
        // Step 6 - Expenses
        $this->achat_prevue_premiere_annee = 30000;
        $this->achat_prevue_deuxieme_annee = 40000;
        $this->achat_prevue_troisieme_annee = 50000;
        $this->frais_fonctionnement_premiere_annee = 12000;
        $this->frais_fonctionnement_deuxieme_annee = 15000;
        $this->frais_fonctionnement_troisieme_annee = 18000;
        $this->charges_personnel_premiere_annee = 24000;
        $this->charges_personnel_deuxieme_annee = 30000;
        $this->charges_personnel_troisieme_annee = 36000;
        $this->dettes_premiere_annee = 5000;
        $this->dettes_deuxieme_annee = 3000;
        $this->dettes_troisieme_annee = 1000;
        $this->etablissement_bancaire_premiere_annee = 2000;
        $this->etablissement_bancaire_deuxieme_annee = 2000;
        $this->etablissement_bancaire_troisieme_annee = 2000;
        $this->fournisseurs_premiere_annee = 8000;
        $this->fournisseurs_deuxieme_annee = 10000;
        $this->fournisseurs_troisieme_annee = 12000;
        $this->autres_dettes_premiere_annee = 1000;
        $this->autres_dettes_deuxieme_annee = 500;
        $this->autres_dettes_troisieme_annee = 0;
        $this->autres_charges_premiere_annee = 3000;
        $this->autres_charges_deuxieme_annee = 4000;
        $this->autres_charges_troisieme_annee = 5000;
        $this->total_frais_premiere_annee = 85000;
        $this->total_frais_deuxieme_annee = 104500;
        $this->total_frais_troisieme_annee = 124000;
        
        // Step 6 - Results
        $this->revenus_premiere_annee = 83000;
        $this->revenus_deuxieme_annee = 115000;
        $this->revenus_troisieme_annee = 147000;
        $this->depenses_premiere_annee = 85000;
        $this->depenses_deuxieme_annee = 104500;
        $this->depenses_troisieme_annee = 124000;
        $this->generer_profits = 'Le projet générera des profits à partir de la deuxième année';
        $this->projet_durable = 'Le projet est durable grâce à la croissance constante';
        
        // Fill dynamic tables with test data
        $this->table1Rows = [
            ['product_name' => 'Produit A', 'description' => 'Description produit A'],
            ['product_name' => 'Produit B', 'description' => 'Description produit B'],
        ];
        
        $this->table2Rows = [
            ['item' => 'Directeur', 'total_employee_1' => 5000, 'total_employee_2' => 6000],
            ['item' => 'Employé', 'total_employee_1' => 3000, 'total_employee_2' => 3500],
        ];
        
        $this->table3Rows = [
            ['product_name_presentation' => 'Produit A', 'presentation_methode' => 'En magasin'],
            ['product_name_presentation' => 'Produit B', 'presentation_methode' => 'En ligne'],
        ];
        
        $this->table4Rows = [
            ['product_name_livraison' => 'Produit A', 'livraison_methode' => 'Livraison à domicile'],
            ['product_name_livraison' => 'Produit B', 'livraison_methode' => 'Retrait en magasin'],
        ];
        
        $this->table5Rows = [
            ['equipement' => 'Machine A', 'reference' => 'REF001', 'prix_equipement' => 15000],
            ['equipement' => 'Machine B', 'reference' => 'REF002', 'prix_equipement' => 8000],
        ];
        
        $this->table6Rows = [
            ['matiere_premiere' => 'Matière A', 'comment_procurer' => 'Fournisseur local', 'fournisseur_matiere' => 'Fournisseur 1'],
            ['matiere_premiere' => 'Matière B', 'comment_procurer' => 'Import', 'fournisseur_matiere' => 'Fournisseur 2'],
        ];
        
        session()->flash('success', 'Test data filled successfully!');
    }

    public function goToStep($stepNumber)
    {
        if (!app()->environment('local')) {
            return;
        }
        
        $this->step = $stepNumber;
    }

    public function testDatabaseSave()
    {
        if (!app()->environment('local')) {
            return;
        }

        try {
            $project = Project::create([
                'project_name' => 'Test Project',
                'email' => 'test@test.com',
                'phone' => '0612345678',
                'status' => 'draft',
            ]);

            session()->flash('success', 'Test project created with ID: ' . $project->id);
        } catch (\Exception $e) {
            session()->flash('error', 'Test failed: ' . $e->getMessage());
        }
    }

    public function updatedCoutsCreation()
    {
        $this->calculateInvestmentTotal();
    }

    public function updatedPreparationEntreprise()
    {
        $this->calculateInvestmentTotal();
    }

    public function updatedAchatMachines()
    {
        $this->calculateInvestmentTotal();
    }

    public function updatedAchatMatieresPremieres()
    {
        $this->calculateInvestmentTotal();
    }

    public function updatedAutresCouts()
    {
        $this->calculateInvestmentTotal();
    }

    private function calculateInvestmentTotal()
    {
        $this->total = 
            ($this->couts_creation ?? 0) +
            ($this->preparation_entreprise ?? 0) +
            ($this->achat_machines ?? 0) +
            ($this->achat_matieres_premieres ?? 0) +
            ($this->autres_couts ?? 0);
    }

    private function calculateResultatNet()
    {
        $this->resultat_premiere_annee = ($this->revenus_premiere_annee ?? 0) - ($this->depenses_premiere_annee ?? 0);
        $this->resultat_deuxieme_annee = ($this->revenus_deuxieme_annee ?? 0) - ($this->depenses_deuxieme_annee ?? 0);
        $this->resultat_troisieme_annee = ($this->revenus_troisieme_annee ?? 0) - ($this->depenses_troisieme_annee ?? 0);
    }

    public function updatedRevenusPremiereAnnee()
    {
        $this->calculateResultatNet();
    }

    public function updatedRevenusDeuxiemeAnnee()
    {
        $this->calculateResultatNet();
    }

    public function updatedRevenusTroisiemeAnnee()
    {
        $this->calculateResultatNet();
    }

    public function updatedDepensesPremiereAnnee()
    {
        $this->calculateResultatNet();
    }

    public function updatedDepensesDeuxiemeAnnee()
    {
        $this->calculateResultatNet();
    }

    public function updatedDepensesTroisiemeAnnee()
    {
        $this->calculateResultatNet();
    }





}

