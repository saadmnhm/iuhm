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
        Schema::table('candidat', function (Blueprint $table) {
            $table->string('selected_region')->nullable()->after('address');
            $table->string('selected_city')->nullable()->after('selected_region');
            $table->string('selected_prefecture')->nullable()->after('selected_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidat', function (Blueprint $table) {
            $table->dropColumn(['selected_region', 'selected_city', 'selected_prefecture', 'selected_location_id']);
        });
    }
};
