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
        // Add new index first so the FK on dynamic_form_id is always covered
        Schema::table('dynamic_form_submissions', function (Blueprint $table) {
            $table->unique(['dynamic_form_id', 'candidat_id', 'programe_id'], 'dynamic_form_submissions_unique_per_project');
        });
        // Now it's safe to drop the old index
        Schema::table('dynamic_form_submissions', function (Blueprint $table) {
            $table->dropUnique('dynamic_form_submissions_dynamic_form_id_candidat_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_form_submissions', function (Blueprint $table) {
            $table->dropUnique('dynamic_form_submissions_unique_per_project');
            $table->unique(['dynamic_form_id', 'candidat_id'], 'dynamic_form_submissions_dynamic_form_id_candidat_id_unique');
        });
    }
};
