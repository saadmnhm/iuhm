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
        Schema::create('project_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('item')->nullable(); // Titre d'emploi
            $table->decimal('total_employee_1', 12, 2)->nullable(); // Première année
            $table->decimal('total_employee_2', 12, 2)->nullable(); // Deuxième année
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index('project_id');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_employees');
    }
};
