<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dynamic_form_submissions', function (Blueprint $table) {
            $table->foreignId('project_submission_id')
                ->nullable()
                ->after('programe_id')
                ->constrained('project_submissions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dynamic_form_submissions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('project_submission_id');
        });
    }
};
