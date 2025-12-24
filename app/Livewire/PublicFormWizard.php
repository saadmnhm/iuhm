<?php

namespace App\Livewire;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;

#[Layout('layouts.app')]
class PublicFormWizard extends Component
{
    public $step = 4;

    public $project_name, $ceo_name, $description, $legal_structure, $resume_executif;
    public $email, $phone, $address, $city, $country, $notes;
    public $public_cible, $concurrent, $volume_produits_locaux, $volume_demande, $demande_offre, $motivations_achat, $raison_choix_client;
    public $méthodes_marketing, $adaptation_methodes, $differenciation_marketing, $plan_affaires, $obtention_financement, $ouverture_proces, $lancement_recrutement, $ouverture_definitive, $duree;
    public $rows = [];
    public $table1Rows = [];
    public $table2Rows = [];



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
            4 => ['address' => 'required'],
            5 => ['city' => 'required'],
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



    // **************************
    // Dynamic Rows
    // ***************************
    public function mount()
    {
        $this->table1Rows = [
            ['product_name' => '', 'description' => '']
        ];

        $this->table2Rows = [
            ['item' => '', 'price_1' => 0, 'price_2' => 0]
        ];
    }

    public function addTable1Row()
    {
        $this->table1Rows[] = ['product_name' => '', 'description' => ''];
    }

    public function removeTable1Row($index)
    {
        unset($this->table1Rows[$index]);
        $this->table1Rows = array_values($this->table1Rows);
    }

    public function addTable2Row()
    {
        $this->table2Rows[] = ['item' => '', 'price_1' => 0, 'price_2' => 0];
    }

    public function removeTable2Row($index)
    {
        unset($this->table2Rows[$index]);
        $this->table2Rows = array_values($this->table2Rows);
    }

    #[Computed]
    public function total1()
    {
        return collect($this->table2Rows)->sum(function ($row) {
            return floatval($row['price_1'] ?? 0);
        });
    }

    #[Computed]
    public function total2()
    {
        return collect($this->table2Rows)->sum(function ($row) {
            return floatval($row['price_2'] ?? 0);
        });
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

