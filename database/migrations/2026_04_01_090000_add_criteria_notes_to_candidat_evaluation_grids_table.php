<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidat_evaluation_grids', function (Blueprint $table) {
            if (!Schema::hasColumn('candidat_evaluation_grids', 'criteria_notes')) {
                $table->json('criteria_notes')->nullable()->after('date_entretien');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidat_evaluation_grids', function (Blueprint $table) {
            if (Schema::hasColumn('candidat_evaluation_grids', 'criteria_notes')) {
                $table->dropColumn('criteria_notes');
            }
        });
    }
};
