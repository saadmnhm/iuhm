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
            // Drop the foreign key and change to JSON for multiple addresses
            $table->dropForeign(['allowed_address']);
            $table->dropColumn('allowed_address');
            
            // Drop the foreign key for form_attached and rename
            $table->dropForeign(['form_attached']);
            $table->dropColumn('form_attached');
        });

        Schema::table('programe_list', function (Blueprint $table) {
            // Add JSON column for multiple addresses
            $table->json('allowed_address_id')->nullable()->after('max_age');
            
            // Add form_attached_id
            $table->foreignId('form_attached_id')->nullable()->constrained('dynamic_forms')->nullOnDelete()->after('allowed_address_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programe_list', function (Blueprint $table) {
            $table->dropColumn(['allowed_address_id', 'form_attached_id']);
            $table->foreignId('allowed_address')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('form_attached')->nullable()->constrained('dynamic_forms')->nullOnDelete();
        });
    }
};
