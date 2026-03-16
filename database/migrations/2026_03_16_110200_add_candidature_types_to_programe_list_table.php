<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programe_list', function (Blueprint $table) {
            if (!Schema::hasColumn('programe_list', 'candidature_types')) {
                $table->json('candidature_types')->nullable()->after('allowed_location_ids');
            }
        });
    }

    public function down(): void
    {
        Schema::table('programe_list', function (Blueprint $table) {
            if (Schema::hasColumn('programe_list', 'candidature_types')) {
                $table->dropColumn('candidature_types');
            }
        });
    }
};
