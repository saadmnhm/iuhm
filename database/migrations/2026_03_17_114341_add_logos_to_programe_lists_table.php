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
        Schema::table('programe_list', function (Blueprint $table) {
            $table->string('logo1')->nullable();
            $table->string('logo2')->nullable();
            $table->string('logo3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programe_list', function (Blueprint $table) {
            $table->dropColumn(['logo1', 'logo2', 'logo3']);
        });
    }
};
