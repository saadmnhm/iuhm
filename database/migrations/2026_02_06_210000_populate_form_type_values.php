<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Define table-form type mappings
        $tableMappings = [
            'etude_marches' => 'etude_marche',
            'evaluation_idees' => 'evaluation_idee',
            'bmcs' => 'bmc',
            'bilan_competences' => 'bilan_competence',
            'business_plans' => 'business_plan',
        ];
        
        // Update each table only if it exists
        foreach ($tableMappings as $table => $formType) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'form_type')) {
                DB::table($table)
                    ->whereNull('form_type')
                    ->update(['form_type' => $formType]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set form_type to null for rollback
        $tables = ['etude_marches', 'evaluation_idees', 'bmcs', 'bilan_competences', 'business_plans'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'form_type')) {
                DB::table($table)->update(['form_type' => null]);
            }
        }
    }
};
