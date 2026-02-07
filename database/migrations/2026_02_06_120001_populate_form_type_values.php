<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update etude_marches records
        DB::table('etude_marches')
            ->whereNull('form_type')
            ->update(['form_type' => 'etude_marche']);

        // Update evaluation_idees records
        DB::table('evaluation_idees')
            ->whereNull('form_type')
            ->update(['form_type' => 'evaluation_idee']);

        // Update bmcs records
        DB::table('bmcs')
            ->whereNull('form_type')
            ->update(['form_type' => 'bmc']);

        // Update bilan_competences records
        DB::table('bilan_competences')
            ->whereNull('form_type')
            ->update(['form_type' => 'bilan_competence']);
            
        // Update business_plans records
        DB::table('business_plans')
            ->whereNull('form_type')
            ->update(['form_type' => 'business_plan']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set form_type to null for rollback
        $tables = ['etude_marches', 'evaluation_idees', 'bmcs', 'bilan_competences', 'business_plans'];
        
        foreach ($tables as $table) {
            DB::table($table)->update(['form_type' => null]);
        }
    }
};
