<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidat_formulaire_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidat')->cascadeOnDelete();
            $table->foreignId('programe_id')->constrained('programe_list')->cascadeOnDelete();
            $table->foreignId('formulaire_id')->constrained('dynamic_forms')->cascadeOnDelete();
            $table->unsignedInteger('order')->default(1);
            $table->timestamps();

            $table->unique(['candidat_id', 'programe_id', 'formulaire_id'], 'cfo_unique_user_project_form');
            $table->index(['candidat_id', 'programe_id'], 'cfo_user_project_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidat_formulaire_orders');
    }
};
