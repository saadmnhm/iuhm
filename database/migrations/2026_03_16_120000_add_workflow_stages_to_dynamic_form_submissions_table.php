<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dynamic_form_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('dynamic_form_submissions', 'workflow_stages')) {
                $table->json('workflow_stages')->nullable()->after('review_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dynamic_form_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('dynamic_form_submissions', 'workflow_stages')) {
                $table->dropColumn('workflow_stages');
            }
        });
    }
};
