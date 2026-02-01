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
        Schema::table('projects', function (Blueprint $table) {
            // Update status enum to include more states
            $table->enum('status', ['draft', 'submitted', 'in_review', 'approved', 'rejected'])->default('draft')->change();
            
            // Add submitted_at timestamp
            $table->timestamp('submitted_at')->nullable()->after('current_step');
            
            // Add reviewed_by admin user
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('submitted_at');
            
            // Add review notes
            $table->text('review_notes')->nullable()->after('reviewed_by');
            
            // Add reviewed_at timestamp
            $table->timestamp('reviewed_at')->nullable()->after('review_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['submitted_at', 'reviewed_by', 'review_notes', 'reviewed_at']);
            
            // Revert status enum
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft')->change();
        });
    }
};
