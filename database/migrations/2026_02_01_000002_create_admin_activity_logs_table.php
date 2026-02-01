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
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Admin user
            $table->string('action'); // e.g., 'project_reviewed', 'project_approved', 'project_rejected', 'user_created', etc.
            $table->string('subject_type')->nullable(); // Model type (Project, User, etc.)
            $table->unsignedBigInteger('subject_id')->nullable(); // Model ID
            $table->text('description')->nullable(); // Human-readable description
            $table->json('properties')->nullable(); // Additional data (old values, new values, etc.)
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('action');
            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};
