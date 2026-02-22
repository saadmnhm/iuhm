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
        Schema::create('programe_formulaire', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programe_id')->constrained('programe_list')->onDelete('cascade');
            $table->foreignId('formulaire_id')->constrained('dynamic_forms')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->boolean('is_required')->default(true);
            $table->timestamps();
            
            // Ensure unique combination
            $table->unique(['programe_id', 'formulaire_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programe_formulaire');
    }
};
