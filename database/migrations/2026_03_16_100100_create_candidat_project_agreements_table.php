<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidat_project_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidat')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('programe_list')->cascadeOnDelete();
            $table->timestamp('agreed_at')->nullable();
            $table->string('agreed_ip', 45)->nullable();
            $table->timestamps();

            $table->unique(['candidat_id', 'project_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidat_project_agreements');
    }
};
