<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etude_marches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidat_id');
            
            // Step 1 - Service/Produit
            $table->text('produit_service')->nullable();
            $table->text('description_offre')->nullable();
            $table->text('benefices_clients')->nullable();
            $table->text('prix_marche')->nullable();
            $table->text('controle_prix')->nullable();
            
            // Step 2 - Clientèle cible
            $table->text('type_clients')->nullable();
            $table->text('caracteristiques_clientele')->nullable();
            $table->text('frequence_consommation')->nullable();
            $table->text('localisation_clients')->nullable();
            $table->text('exigences_principales')->nullable();
            
            // Step 3 - Concurrence
            $table->text('nombre_concurrents_directs')->nullable();
            $table->text('concurrents_indirects')->nullable();
            $table->text('taille_concurrents')->nullable();
            $table->text('informations_concurrents')->nullable();
            $table->text('communication_concurrents')->nullable();
            
            // Step 4 - Approvisionnement & Fournisseurs
            $table->text('nombre_fournisseurs')->nullable();
            $table->text('origine_fournisseurs')->nullable();
            $table->text('prix_fournisseurs')->nullable();
            $table->text('delais_livraison')->nullable();
            $table->text('stabilite_marche')->nullable();
            
            $table->enum('status', ['draft', 'submitted', 'in_review', 'approved', 'rejected'])->default('draft');
            $table->integer('current_step')->default(1);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('candidat_id')->references('id')->on('candidat')->onDelete('cascade');
            $table->index(['candidat_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etude_marches');
    }
};
