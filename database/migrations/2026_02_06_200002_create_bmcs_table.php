<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bmcs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidat')->onDelete('cascade');

            // 9 BMC Canvas blocks
            $table->text('partenaires_cles')->nullable();
            $table->text('activites_cles')->nullable();
            $table->text('proposition_valeur')->nullable();
            $table->text('relations_clients')->nullable();
            $table->text('segments_clientele')->nullable();
            $table->text('ressources_cles')->nullable();
            $table->text('canaux')->nullable();
            $table->text('structure_couts')->nullable();
            $table->text('flux_revenus')->nullable();

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
        Schema::dropIfExists('bmcs');
    }
};
