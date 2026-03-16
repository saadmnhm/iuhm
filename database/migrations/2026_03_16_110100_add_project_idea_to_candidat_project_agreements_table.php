<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidat_project_agreements', function (Blueprint $table) {
            if (!Schema::hasColumn('candidat_project_agreements', 'project_idea')) {
                $table->text('project_idea')->nullable()->after('agreed_ip');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidat_project_agreements', function (Blueprint $table) {
            if (Schema::hasColumn('candidat_project_agreements', 'project_idea')) {
                $table->dropColumn('project_idea');
            }
        });
    }
};
