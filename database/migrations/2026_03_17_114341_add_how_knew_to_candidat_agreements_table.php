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
        Schema::table('candidat_project_agreements', function (Blueprint $table) {
            $table->text('how_knew')->nullable()->after('project_idea');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidat_project_agreements', function (Blueprint $table) {
            $table->dropColumn('how_knew');
        });
    }
};
