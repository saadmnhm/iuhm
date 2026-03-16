<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidat', function (Blueprint $table) {
            if (!Schema::hasColumn('candidat', 'cin')) {
                $table->string('cin', 30)->nullable()->after('prenom');
            }

            if (!Schema::hasColumn('candidat', 'niveau_etude')) {
                $table->string('niveau_etude', 255)->nullable()->after('date_naissance');
            }

            if (!Schema::hasColumn('candidat', 'specialite')) {
                $table->string('specialite', 255)->nullable()->after('niveau_etude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidat', function (Blueprint $table) {
            if (Schema::hasColumn('candidat', 'specialite')) {
                $table->dropColumn('specialite');
            }

            if (Schema::hasColumn('candidat', 'niveau_etude')) {
                $table->dropColumn('niveau_etude');
            }

            if (Schema::hasColumn('candidat', 'cin')) {
                $table->dropColumn('cin');
            }
        });
    }
};
