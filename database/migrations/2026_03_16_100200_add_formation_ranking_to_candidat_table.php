<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidat', function (Blueprint $table) {
            if (!Schema::hasColumn('candidat', 'formation_ranking')) {
                $table->text('formation_ranking')->nullable()->after('review_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidat', function (Blueprint $table) {
            if (Schema::hasColumn('candidat', 'formation_ranking')) {
                $table->dropColumn('formation_ranking');
            }
        });
    }
};
