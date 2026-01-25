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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            
            // Step 0 - Personal Info
            $table->string('profile_image')->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', ['homme', 'femme'])->nullable();
            $table->enum('address', ['Hay Mohamadi', 'Ain Sbaa', 'Roches Noires'])->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            
            // Step 1 - Project Info
            $table->string('project_name')->nullable();
            $table->string('ceo_name')->nullable();
            $table->text('description')->nullable();
            $table->string('legal_structure')->nullable();
            $table->text('resume_executif')->nullable();
            
            // Step 2 - Market Analysis
            $table->text('public_cible')->nullable();
            $table->text('concurrent')->nullable();
            $table->text('volume_produits_locaux')->nullable();
            $table->text('volume_demande')->nullable();
            $table->text('demande_offre')->nullable();
            $table->text('motivations_achat')->nullable();
            $table->text('raison_choix_client')->nullable();
            
            // Step 3 - Marketing & Timeline
            $table->text('méthodes_marketing')->nullable();
            $table->text('adaptation_methodes')->nullable();
            $table->text('differenciation_marketing')->nullable();
            $table->text('plan_affaires')->nullable();
            $table->text('obtention_financement')->nullable();
            $table->text('ouverture_proces')->nullable();
            $table->text('lancement_recrutement')->nullable();
            $table->text('ouverture_definitive')->nullable();
            $table->string('duree')->nullable();
            
            // Step 4 - Location & Distribution
            $table->text('lieu_projet')->nullable();
            $table->text('adaptation_lieu')->nullable();
            $table->text('benefices_from_projet')->nullable();
            $table->text('valeur_projet')->nullable();
            
            // Step 5 - Capacities
            $table->text('step_8_1')->nullable(); // Compétences nécessaires
            $table->text('step_8_2')->nullable(); // Matériel et outils
            $table->text('step_8_3')->nullable(); // Expérience nécessaire
            $table->text('step_8_4')->nullable(); // Fonds nécessaires
            
            // Step 5 - Investment Program
            $table->decimal('couts_creation', 12, 2)->nullable();
            $table->decimal('preparation_entreprise', 12, 2)->nullable();
            $table->decimal('achat_machines', 12, 2)->nullable();
            $table->decimal('achat_matieres_premieres', 12, 2)->nullable();
            $table->decimal('autres_couts', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            
            // Step 6 - Financial Questions
            $table->text('generer_profits')->nullable();
            $table->text('projet_durable')->nullable();
            
            // Status & Tracking
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->integer('current_step')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('status');
            $table->index('email');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
