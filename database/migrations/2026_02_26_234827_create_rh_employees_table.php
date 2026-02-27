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
        Schema::create('rh_employees', function (Blueprint $table) {
            $table->id();
            $table->string('matricule', 50)->nullable()->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('cin', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('poste')->nullable();
            $table->string('departement')->nullable();
            $table->enum('contrat_type', ['CDI', 'CDD', 'Stage', 'Freelance', 'Autre'])->default('CDI');
            $table->date('date_embauche')->nullable();
            $table->date('date_fin_contrat')->nullable();
            $table->decimal('salaire', 10, 2)->nullable();
            $table->string('address')->nullable();
            $table->enum('gender', ['homme', 'femme'])->nullable();
            $table->date('date_naissance')->nullable();
            $table->enum('status', ['active', 'inactive', 'en_conge', 'quitte'])->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rh_employees');
    }
};
