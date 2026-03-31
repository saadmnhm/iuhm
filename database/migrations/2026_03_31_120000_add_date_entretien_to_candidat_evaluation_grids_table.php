<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidat_evaluation_grids', function (Blueprint $table) {
            if (!Schema::hasColumn('candidat_evaluation_grids', 'date_entretien')) {
                $table->date('date_entretien')->nullable()->after('admin_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidat_evaluation_grids', function (Blueprint $table) {
            if (Schema::hasColumn('candidat_evaluation_grids', 'date_entretien')) {
                $table->dropColumn('date_entretien');
            }
        });
    }
};
