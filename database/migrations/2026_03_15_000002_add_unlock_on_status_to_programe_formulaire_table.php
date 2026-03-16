<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programe_formulaire', function (Blueprint $table) {
            if (!Schema::hasColumn('programe_formulaire', 'unlock_on_status')) {
                $table->enum('unlock_on_status', ['submitted', 'in_review', 'approved'])
                    ->default('approved')
                    ->after('is_required');
            }
        });
    }

    public function down(): void
    {
        Schema::table('programe_formulaire', function (Blueprint $table) {
            if (Schema::hasColumn('programe_formulaire', 'unlock_on_status')) {
                $table->dropColumn('unlock_on_status');
            }
        });
    }
};
