<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bilan_competences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidat')->onDelete('cascade');

            // Step 1 - Axe Personnel
            $table->json('qualites_defauts')->nullable();
            $table->text('qualites_contribution')->nullable();
            $table->text('defauts_freins')->nullable();
            $table->text('loisirs')->nullable();

            // Step 2 - Axe de Formation
            $table->text('niveau_etude')->nullable();
            $table->text('diplomes_obtenus')->nullable();
            $table->text('annee_obtention')->nullable();
            $table->text('etablissement_obtention')->nullable();
            $table->json('competences_formation')->nullable();
            $table->string('besoin_formations')->nullable();
            $table->text('type_formations')->nullable();

            // Step 3 - Axe Professionnel
            $table->json('environnement_professionnel')->nullable();
            $table->json('secteurs_activite')->nullable();

            // Step 4 - Fonctions Envisagées
            $table->json('fonctions_envisagees')->nullable();
            $table->json('representation_travail')->nullable();

            // Step 5 - Contraintes & Exigences
            $table->json('contraintes_acceptees')->nullable();
            $table->json('exigences')->nullable();
            $table->text('reflexions_personnelles')->nullable();

            // Step 6 - Stages
            $table->text('stage_societe')->nullable();
            $table->text('stage_lieu')->nullable();
            $table->text('stage_secteur')->nullable();
            $table->text('stage_duree')->nullable();
            $table->text('stage_responsabilites')->nullable();
            $table->text('stage_competences')->nullable();
            $table->text('stage_obstacles')->nullable();
            $table->text('stage_reflexions')->nullable();
            $table->text('stage_plu')->nullable();
            $table->text('stage_deplu')->nullable();
            $table->text('stage_appris')->nullable();

            // Step 7 - Expériences Professionnelles
            $table->text('exp_societe')->nullable();
            $table->text('exp_lieu')->nullable();
            $table->text('exp_secteur')->nullable();
            $table->text('exp_duree')->nullable();
            $table->text('exp_responsabilites')->nullable();
            $table->text('exp_competences')->nullable();
            $table->text('exp_obstacles')->nullable();
            $table->text('exp_integration')->nullable();
            $table->text('exp_depart')->nullable();
            $table->text('exp_reflexions')->nullable();

            $table->string('status')->default('draft');
            $table->integer('current_step')->default(1);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bilan_competences');
    }
};
