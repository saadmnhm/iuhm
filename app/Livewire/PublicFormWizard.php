<?php

namespace App\Livewire;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Livewire\Concerns\ManagesTableRows;

#[Layout('layouts.app')]
class PublicFormWizard extends Component
{
    use ManagesTableRows;
    public $step = 5;

    public $rows = [];
    public $project_name, $ceo_name, $description, $legal_structure, $resume_executif;
    public $public_cible, $concurrent, $volume_produits_locaux, $volume_demande, $demande_offre, $motivations_achat, $raison_choix_client;
    public $méthodes_marketing, $adaptation_methodes, $differenciation_marketing, $plan_affaires, $obtention_financement, $ouverture_proces, $lancement_recrutement, $ouverture_definitive, $duree;
    public $lieu_projet, $adaptation_lieu, $benefices_from_projet, $valeur_projet;
    // step 5 variables
    public $step_8_1, $step_8_2, $step_8_3, $step_8_4;
    public $couts_creation, $preparation_entreprise, $achat_machines, $achat_matieres_premieres, $autres_couts, $total;
    public $ventes_premiere_annee, $ventes_deuxieme_annee, $ventes_troisieme_annee;
    public $services_premiere_annee, $services_deuxieme_annee, $services_troisieme_annee;
    public $aide_financiere_premiere_annee, $aide_financiere_deuxieme_annee, $aide_financiere_troisieme_annee;
    public $revenus_financiers_premiere_annee, $revenus_financiers_deuxieme_annee, $revenus_financiers_troisieme_annee;
    public $autres_revenus_premiere_annee, $autres_revenus_deuxieme_annee, $autres_revenus_troisieme_annee;
    public $total_premiere_annee, $total_deuxieme_annee, $total_troisieme_annee;
    // end step 5 variables




    protected function rules()
    {
        return match ($this->step) {
            1 => [
                'project_name' => 'nullable',
                'ceo_name' => 'nullable',
                'description' => 'nullable',
                'legal_structure' => 'nullable',
                'resume_executif' => 'nullable'
            ],
            2 => [
                'table1Rows.*.product_name' => 'nullable|string',
                'table1Rows.*.description' => 'nullable|string',
                'public_cible' => 'nullable',
                'concurrent' => 'nullable',
                'volume_produits_locaux' => 'nullable',
                'volume_demande' => 'nullable',
                'demande_offre' => 'nullable',
                'motivations_achat' => 'nullable',
                'raison_choix_client' => 'nullable'
            ],
            3 => [
                'méthodes_marketing' => 'nullable',
                'adaptation_methodes' => 'nullable',
                'differenciation_marketing' => 'nullable',
                'table2Rows.*.item' => 'nullable|string',
                'table2Rows.*.price_1' => 'nullable|numeric|min:0',
                'table2Rows.*.price_2' => 'nullable|numeric|min:0',
                'plan_affaires' => 'nullable', 
                'obtention_financement' => 'nullable',
                'ouverture_proces' => 'nullable',
                'lancement_recrutement' => 'nullable',
                'ouverture_definitive' => 'nullable',
                'duree' => 'nullable'

            ],
            4 => [
                'lieu_projet' => 'nullable',
                'adaptation_lieu' => 'nullable',
                'table3Rows.*.product_name_presentation' => 'nullable|string',
                'table3Rows.*.presentation_methode' => 'nullable|string',
                'table4Rows.*.product_name_livraison' => 'nullable|string',
                'table4Rows.*.livraison_methode' => 'nullable|string',
                'benefices_from_projet' => 'nullable',
                'valeur_projet' => 'nullable'
            ],
            5 => [
                // Part 8 - Capacités
                'step_8_1' => 'nullable|string',  // Compétences nécessaires
                'step_8_2' => 'nullable|string',  // Matériel et outils
                'step_8_3' => 'nullable|string',  // Expérience nécessaire
                'step_8_4' => 'nullable|string',  // Fonds nécessaires
                
                // Table 9 - Programme d'investissement
                'couts_creation' => 'nullable|numeric|min:0',
                'preparation_entreprise' => 'nullable|numeric|min:0',
                'achat_machines' => 'nullable|numeric|min:0',
                'achat_matieres_premieres' => 'nullable|numeric|min:0',
                'autres_couts' => 'nullable|numeric|min:0',
                'total' => 'nullable|numeric|min:0',
                
                // Table 10 - Prévisions de revenus (3 années)
                'ventes_premiere_annee' => 'nullable|numeric|min:0',
                'ventes_deuxieme_annee' => 'nullable|numeric|min:0',
                'ventes_troisieme_annee' => 'nullable|numeric|min:0',
                'services_premiere_annee' => 'nullable|numeric|min:0',
                'services_deuxieme_annee' => 'nullable|numeric|min:0',
                'services_troisieme_annee' => 'nullable|numeric|min:0',
                'aide_financiere_premiere_annee' => 'nullable|numeric|min:0',
                'aide_financiere_deuxieme_annee' => 'nullable|numeric|min:0',
                'aide_financiere_troisieme_annee' => 'nullable|numeric|min:0',
                'revenus_financiers_premiere_annee' => 'nullable|numeric|min:0',
                'revenus_financiers_deuxieme_annee' => 'nullable|numeric|min:0',
                'revenus_financiers_troisieme_annee' => 'nullable|numeric|min:0',
                'autres_revenus_premiere_annee' => 'nullable|numeric|min:0',
                'autres_revenus_deuxieme_annee' => 'nullable|numeric|min:0',
                'autres_revenus_troisieme_annee' => 'nullable|numeric|min:0',
                'total_premiere_annee' => 'nullable|numeric|min:0',
                'total_deuxieme_annee' => 'nullable|numeric|min:0',
                'total_troisieme_annee' => 'nullable|numeric|min:0',
            ],
            6 => ['country' => 'required'],
            7 => ['notes' => 'nullable'],
            default => [],
        };
    }

    public function next()
    {
        $this->validate();
        $this->step++;
    }

    public function back()
    {
        $this->step--;
    }

    public function submit()
    {
        $this->validate();

        // Save later if you want (DB / email)
        session()->flash('success', 'Form submitted successfully!');

        $this->reset();
        $this->step = 1;
    }

    public function render()
    {
        return view('livewire.public-form-wizard');
    }

    public function save()
    {
        // Handle saving rows to DB
        foreach ($this->rows as $row) {
            // Example: User::create($row);
        }
        session()->flash('message', 'Rows saved successfully!');
    }
}

