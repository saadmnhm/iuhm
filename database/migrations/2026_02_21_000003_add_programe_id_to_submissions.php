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
        Schema::table('dynamic_form_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('dynamic_form_submissions', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('candidat_id')->constrained('users')->onDelete('cascade');
            }
            $table->foreignId('programe_id')->nullable()->after('user_id')->constrained('programe_list')->onDelete('cascade');
            $table->index('programe_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_form_submissions', function (Blueprint $table) {
            $table->dropForeign(['programe_id']);
            $table->dropColumn('programe_id');
            if (Schema::hasColumn('dynamic_form_submissions', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
