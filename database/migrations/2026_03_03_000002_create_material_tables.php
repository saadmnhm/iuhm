<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Catégories de matériel ──
        Schema::create('material_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // ── Matériel / Inventaire ──
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('material_categories')->nullOnDelete();
            $table->string('reference')->nullable()->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('quantity_min')->default(0); // seuil d'alerte
            $table->decimal('prix_unitaire', 15, 2)->nullable();
            $table->decimal('valeur_totale', 15, 2)->nullable();
            $table->string('emplacement')->nullable(); // localisation physique
            $table->enum('etat', ['neuf', 'bon', 'usage', 'defectueux', 'hors_service'])->default('bon');
            $table->enum('status', ['disponible', 'en_utilisation', 'en_maintenance', 'retire'])->default('disponible');
            $table->string('fournisseur')->nullable();
            $table->date('date_acquisition')->nullable();
            $table->date('date_garantie')->nullable();
            $table->string('numero_serie')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // ── Photos / Pièces jointes matériel ──
        Schema::create('material_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type')->nullable(); // photo, facture, garantie, manuel
            $table->string('mime_type')->nullable();
            $table->boolean('is_primary')->default(false); // photo principale
            $table->timestamps();
        });

        // ── Mouvements de stock (entrée / sortie / transfert) ──
        Schema::create('material_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->enum('type', ['entree', 'sortie', 'transfert', 'ajustement']);
            $table->integer('quantity');
            $table->string('motif')->nullable();
            $table->string('destination')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ── Maintenance / Réparations ──
        Schema::create('material_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->string('type_maintenance')->nullable(); // preventive, corrective
            $table->text('description')->nullable();
            $table->decimal('cout', 15, 2)->nullable();
            $table->date('date_maintenance');
            $table->date('prochaine_maintenance')->nullable();
            $table->string('prestataire')->nullable();
            $table->enum('status', ['planifie', 'en_cours', 'termine'])->default('planifie');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_maintenances');
        Schema::dropIfExists('material_movements');
        Schema::dropIfExists('material_attachments');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('material_categories');
    }
};
