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
        Schema::table('project_submissions', function (Blueprint $table) {
            $table->boolean('require_formation_review')->default(false); $table->integer('formation_review_rating')->nullable(); $table->text('formation_review_feedback')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_submissions', function (Blueprint $table) {
            $table->boolean('require_formation_review')->default(false); $table->integer('formation_review_rating')->nullable(); $table->text('formation_review_feedback')->nullable();
        });
    }
};
