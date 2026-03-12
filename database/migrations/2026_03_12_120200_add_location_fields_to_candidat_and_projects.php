<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidat', function (Blueprint $table) {
            $table->foreignId('morocco_location_id')
                ->nullable()
                ->after('address')
                ->constrained('morocco_locations')
                ->nullOnDelete();

            $table->string('address_detail', 500)->nullable()->after('morocco_location_id');
        });

        Schema::table('programe_list', function (Blueprint $table) {
            $table->json('allowed_location_ids')->nullable()->after('allowed_address_id');
        });
    }

    public function down(): void
    {
        Schema::table('programe_list', function (Blueprint $table) {
            $table->dropColumn('allowed_location_ids');
        });

        Schema::table('candidat', function (Blueprint $table) {
            $table->dropForeign(['morocco_location_id']);
            $table->dropColumn(['morocco_location_id', 'address_detail']);
        });
    }
};
