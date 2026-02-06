<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_idees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidat')->onDelete('cascade');

            // Q1: L'idée de mon projet
            $table->text('idee_projet')->nullable();
            // Q2: Résumes ton idée en une phrase
            $table->text('resume_idee')->nullable();
            // Q3: A quel besoin précis répond mon idée de projet ?
            $table->text('besoin_projet')->nullable();
            // Q4: Quels sont les produits ou services que vous proposez
            $table->text('produits_services')->nullable();
            // Q5: Ai-je identifié qui pourraient être mes clients ?
            $table->text('clients_identifies')->nullable();
            // Q6: Mon idée existe-t-elle déjà sur le marché ?
            $table->text('idee_existe_marche')->nullable();
            // Q7: Quelle est la valeur ajoutée de l'idée ?
            $table->text('valeur_ajoutee')->nullable();
            // Q8: Quelles sont les résultats prévues
            $table->text('resultats_prevus')->nullable();
            // Q9: Mes proches comprennent-ils mon idée ? (oui/non)
            $table->string('proches_comprennent')->nullable();
            // Q10: Leurs réactions sont-elles positives ? (oui/non)
            $table->string('reactions_positives')->nullable();

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
        Schema::dropIfExists('evaluation_idees');
    }
};
