<?php

namespace App\Livewire\Concerns;

use Livewire\Attributes\Computed;

trait ManagesTableRows
{
    public $table1Rows = [];
    public $table2Rows = [];
    public $table3Rows = [];
    public $table4Rows = [];
    

    // ***********************************************
    // Initialization of table rows
    // ***********************************************

    public function initializeManagesTableRows()
    {
        $this->table1Rows = [
            ['product_name' => '', 'description' => '']
        ];

        $this->table2Rows = [
            ['item' => '', 'price_1' => 0, 'price_2' => 0]
        ];
        $this->table3Rows = [
            ['product_name_presentation' => '', 'presentation_methode' => '']
        ];
        $this->table4Rows = [
            ['product_name_livraison' => '', 'livraison_methode' => '', 'timeline' => '']
        ];
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
        $this->table2Rows[] = ['item' => '', 'price_1' => 0, 'price_2' => 0];
    }
    public function addTable3Row()
    {
        $this->table3Rows[] = ['product_name_presentation' => '', 'presentation_methode' => ''];
    }
    public function addTable4Row()
    {
        $this->table4Rows[] = ['activity' => '', 'responsible_person' => '', 'timeline' => ''];
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

    // ***********************************************
    // Computed properties for totals
    // ***********************************************

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

}
