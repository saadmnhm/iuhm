<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'form_type')) {
                $table->string('form_type')->default('business_plan')->after('candidat_id');
            }
            
            if (!Schema::hasIndex('projects', 'projects_form_type_index')) {
                $table->index('form_type');
            }
        });
        
        // Add composite index separately to avoid issues
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'candidat_id') && Schema::hasColumn('projects', 'form_type')) {
                try {
                    $table->index(['candidat_id', 'form_type'], 'projects_candidat_form_type_index');
                } catch (\Exception $e) {
                    // Index may already exist
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            try {
                $table->dropIndex('projects_candidat_form_type_index');
            } catch (\Exception $e) {}
            
            try {
                $table->dropIndex('projects_form_type_index');
            } catch (\Exception $e) {}
            
            if (Schema::hasColumn('projects', 'form_type')) {
                $table->dropColumn('form_type');
            }
        });
    }
};
