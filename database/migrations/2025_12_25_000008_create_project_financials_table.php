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
        Schema::create('project_financials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            
            // Revenue Projections (Step 5 - Table 10)
            $table->decimal('ventes_premiere_annee', 12, 2)->nullable();
            $table->decimal('ventes_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('ventes_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('services_premiere_annee', 12, 2)->nullable();
            $table->decimal('services_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('services_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('aide_financiere_premiere_annee', 12, 2)->nullable();
            $table->decimal('aide_financiere_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('aide_financiere_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('revenus_financiers_premiere_annee', 12, 2)->nullable();
            $table->decimal('revenus_financiers_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('revenus_financiers_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('autres_revenus_premiere_annee', 12, 2)->nullable();
            $table->decimal('autres_revenus_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('autres_revenus_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('total_revenus_premiere_annee', 12, 2)->nullable();
            $table->decimal('total_revenus_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('total_revenus_troisieme_annee', 12, 2)->nullable();
            
            // Expected Expenses (Step 6 - Table 11)
            $table->decimal('achat_prevue_premiere_annee', 12, 2)->nullable();
            $table->decimal('achat_prevue_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('achat_prevue_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('frais_fonctionnement_premiere_annee', 12, 2)->nullable();
            $table->decimal('frais_fonctionnement_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('frais_fonctionnement_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('charges_personnel_premiere_annee', 12, 2)->nullable();
            $table->decimal('charges_personnel_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('charges_personnel_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('dettes_premiere_annee', 12, 2)->nullable();
            $table->decimal('dettes_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('dettes_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('etablissement_bancaire_premiere_annee', 12, 2)->nullable();
            $table->decimal('etablissement_bancaire_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('etablissement_bancaire_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('fournisseurs_premiere_annee', 12, 2)->nullable();
            $table->decimal('fournisseurs_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('fournisseurs_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('autres_dettes_premiere_annee', 12, 2)->nullable();
            $table->decimal('autres_dettes_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('autres_dettes_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('autres_charges_premiere_annee', 12, 2)->nullable();
            $table->decimal('autres_charges_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('autres_charges_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('total_frais_premiere_annee', 12, 2)->nullable();
            $table->decimal('total_frais_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('total_frais_troisieme_annee', 12, 2)->nullable();
            
            // Results (Step 6 - Table 12)
            $table->decimal('revenus_premiere_annee', 12, 2)->nullable();
            $table->decimal('revenus_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('revenus_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('depenses_premiere_annee', 12, 2)->nullable();
            $table->decimal('depenses_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('depenses_troisieme_annee', 12, 2)->nullable();
            
            $table->decimal('resultat_premiere_annee', 12, 2)->nullable();
            $table->decimal('resultat_deuxieme_annee', 12, 2)->nullable();
            $table->decimal('resultat_troisieme_annee', 12, 2)->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_financials');
    }
};
