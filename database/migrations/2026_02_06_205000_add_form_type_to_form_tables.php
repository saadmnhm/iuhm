<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add form_type column to all form tables if not exists
        $tables = ['etude_marches', 'evaluation_idees', 'bmcs', 'bilan_competences'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'form_type')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->string('form_type')->nullable()->after('candidat_id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['etude_marches', 'evaluation_idees', 'bmcs', 'bilan_competences'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'form_type')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('form_type');
                });
            }
        }
    }
};
