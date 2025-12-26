<?php

namespace App\Livewire\Concerns;

use Livewire\Attributes\Computed;

trait ManagesTableRows
{
    public $table1Rows = [];
    public $table2Rows = [];
    public $table3Rows = [];
    public $table4Rows = [];
    public $table5Rows = [];    
    public $table6Rows = []; 
    // ***********************************************
    // Initialization of table rows
    // ***********************************************

    public function mountManagesTableRows()
    {
        // Only initialize if tables are empty
        if (empty($this->table1Rows)) {
            $this->table1Rows = [
                ['product_name' => '', 'description' => '']
            ];
        }

        if (empty($this->table2Rows)) {
            $this->table2Rows = [
                ['item' => '', 'total_employee_1' => 0, 'total_employee_2' => 0]
            ];
        }

        if (empty($this->table3Rows)) {
            $this->table3Rows = [
                ['product_name_presentation' => '', 'presentation_methode' => '']
            ];
        }

        if (empty($this->table4Rows)) {
            $this->table4Rows = [
                ['product_name_livraison' => '', 'livraison_methode' => '' ]
            ];
        }

        if (empty($this->table5Rows)) {
            $this->table5Rows = array_fill(0, 15, [
                'equipement' => '', 
                'reference' => '', 
                'prix_equipement' => 0
            ]);
        }

        if (empty($this->table6Rows)) {
            $this->table6Rows = array_fill(0, 15, [
                'matiere_premiere' => '', 
                'comment_procurer' => '', 
                'fournisseur_matiere' => ''
            ]);
        }
    }

    // ***********************************************
    // Methods to add new rows
    // ***********************************************

    public function addTable1Row()
    {
        $this->table1Rows[] = ['product_name' => '', 'description' => ''];
    }

    public function addTable2Row()
    {
        $this->table2Rows[] = ['item' => '', 'total_employee_1' => 0, 'total_employee_2' => 0];
    }
    public function addTable3Row()
    {
        $this->table3Rows[] = ['product_name_presentation' => '', 'presentation_methode' => ''];
    }
    public function addTable4Row()
    {
        $this->table4Rows[] = ['activity' => '', 'responsible_person' => ''];
    }
    public function addTable5Row()
    {
        $this->table5Rows[] = ['equipement' => '', 'reference' => '', 'prix_equipement' => 0];
    }
    public function addTable6Row()
    {
        $this->table6Rows[] = ['matiere_premiere' => '', 'comment_procurer' => '', 'fournisseur_matiere' => ''];
    }

    // ***********************************************
    // Methods to remove rows by index
    // ***********************************************

    public function removeTable1Row($index)
    {
        unset($this->table1Rows[$index]);
        $this->table1Rows = array_values($this->table1Rows);
    }

    public function removeTable2Row($index)
    {
        unset($this->table2Rows[$index]);
        $this->table2Rows = array_values($this->table2Rows);
    }
    public function removeTable3Row($index)
    {
        unset($this->table3Rows[$index]);
        $this->table3Rows = array_values($this->table3Rows);
    }
    public function removeTable4Row($index)
    {
        unset($this->table4Rows[$index]);
        $this->table4Rows = array_values($this->table4Rows);
    }
    public function removeTable5Row($index)
    {
        unset($this->table5Rows[$index]);
        $this->table5Rows = array_values($this->table5Rows);
    }
    public function removeTable6Row($index)
    {
        unset($this->table6Rows[$index]);
        $this->table6Rows = array_values($this->table6Rows);
    }

    // ***********************************************
    // Computed properties for totals
    // ***********************************************

    #[Computed]
    public function total1()
    {
        return collect($this->table2Rows)->sum(function ($row) {
            return floatval($row['total_employee_1'] ?? 0);
        });
    }

    #[Computed]
    public function total2()
    {
        return collect($this->table2Rows)->sum(function ($row) {
            return floatval($row['total_employee_2'] ?? 0);
        });
    }


}
