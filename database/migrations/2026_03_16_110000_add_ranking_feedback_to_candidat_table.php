<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidat', function (Blueprint $table) {
            if (!Schema::hasColumn('candidat', 'ranking_feedback_status')) {
                $table->string('ranking_feedback_status', 20)->default('pending')->after('formation_ranking');
            }
            if (!Schema::hasColumn('candidat', 'ranking_feedback_note')) {
                $table->text('ranking_feedback_note')->nullable()->after('ranking_feedback_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidat', function (Blueprint $table) {
            if (Schema::hasColumn('candidat', 'ranking_feedback_note')) {
                $table->dropColumn('ranking_feedback_note');
            }
            if (Schema::hasColumn('candidat', 'ranking_feedback_status')) {
                $table->dropColumn('ranking_feedback_status');
            }
        });
    }
};
