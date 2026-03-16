<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidat')->cascadeOnDelete();
            $table->foreignId('programe_id')->constrained('programe_list')->cascadeOnDelete();
            $table->foreignId('assigned_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('review_status', ['pending', 'in_review', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();

            $table->unique(['candidat_id', 'programe_id']);
            $table->index(['programe_id', 'review_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_submissions');
    }
};
