<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidat_evaluation_grids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidat')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('programe_list')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('motivation_score')->default(0);
            $table->unsignedTinyInteger('profile_score')->default(0);
            $table->unsignedTinyInteger('viability_score')->default(0);
            $table->unsignedSmallInteger('total_score')->default(0);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidat_evaluation_grids');
    }
};
