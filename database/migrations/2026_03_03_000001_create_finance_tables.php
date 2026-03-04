<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Caisse / Treasury ──
        Schema::create('finance_caisse', function (Blueprint $table) {
            $table->id();
            $table->string('label')->default('Caisse Principale');
            $table->decimal('solde_initial', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ── Catégories de dépenses ──
        Schema::create('finance_categories', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['revenue', 'depense']);
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // ── Transactions (revenue & dépense) ──
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caisse_id')->constrained('finance_caisse')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('finance_categories')->nullOnDelete();
            $table->enum('type', ['revenue', 'depense']);
            $table->string('reference')->nullable();
            $table->string('label');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('date_transaction');
            $table->string('beneficiaire')->nullable();
            $table->string('mode_paiement')->nullable(); // espece, cheque, virement, carte
            $table->enum('status', ['en_attente', 'valide', 'annule'])->default('valide');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // ── Pièces jointes (reçu, bilan, facture, photo, etc.) ──
        Schema::create('finance_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('finance_transactions')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type')->nullable(); // recu, facture, bilan, photo, autre
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ── Charges récurrentes (eau, wifi, loyer, etc.) ──
        Schema::create('finance_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caisse_id')->constrained('finance_caisse')->cascadeOnDelete();
            $table->string('label'); // Eau, Wifi, Electricité, Loyer, Internet
            $table->decimal('montant', 15, 2);
            $table->enum('frequence', ['mensuel', 'trimestriel', 'annuel', 'ponctuel'])->default('mensuel');
            $table->string('fournisseur')->nullable();
            $table->date('date_echeance')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_attachments');
        Schema::dropIfExists('finance_charges');
        Schema::dropIfExists('finance_transactions');
        Schema::dropIfExists('finance_categories');
        Schema::dropIfExists('finance_caisse');
    }
};
