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
        // First, add the new unique constraint with programe_id
        // For NULL programe_id (standalone forms), MySQL will allow multiple rows
        \Illuminate\Support\Facades\DB::statement('
            ALTER TABLE dynamic_form_submissions 
            ADD UNIQUE KEY dynamic_form_submissions_unique_per_project (dynamic_form_id, candidat_id, programe_id)
        ');
        
        // Now drop the old constraint
        \Illuminate\Support\Facades\DB::statement('
            ALTER TABLE dynamic_form_submissions 
            DROP INDEX dynamic_form_submissions_dynamic_form_id_candidat_id_unique
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement('
            ALTER TABLE dynamic_form_submissions 
            ADD UNIQUE KEY dynamic_form_submissions_dynamic_form_id_candidat_id_unique (dynamic_form_id, candidat_id)
        ');
        
        \Illuminate\Support\Facades\DB::statement('
            ALTER TABLE dynamic_form_submissions 
            DROP INDEX dynamic_form_submissions_unique_per_project
        ');
    }
};
