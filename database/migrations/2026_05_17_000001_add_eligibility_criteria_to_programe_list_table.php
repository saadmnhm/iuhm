<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programe_list', function (Blueprint $table) {
            $table->json('eligibility_criteria')->nullable()->after('candidature_types');
        });
    }

    public function down(): void
    {
        Schema::table('programe_list', function (Blueprint $table) {
            $table->dropColumn('eligibility_criteria');
        });
    }
};
