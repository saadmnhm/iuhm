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
        Schema::create('candidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
            $table->string('login')->unique();
            $table->string('password');
            $table->string('nom');
            $table->string('prenom');
            $table->string('profile_image')->nullable();
            $table->enum('gender', ['homme', 'femme'])->nullable();
            $table->enum('address', ['Hay Mohamadi', 'Ain Sbaa', 'Roches Noires'])->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('cv_path')->nullable();
            $table->boolean('is_active')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidat');
    }
};
