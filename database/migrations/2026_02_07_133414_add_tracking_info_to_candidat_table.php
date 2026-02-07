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
            $table->string('last_ip_address', 45)->nullable();
            $table->text('last_user_agent')->nullable();
            $table->string('last_browser')->nullable();
            $table->string('last_platform')->nullable();
            $table->string('last_device')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->integer('login_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidat', function (Blueprint $table) {
            $table->dropColumn([
                'last_ip_address',
                'last_user_agent',
                'last_browser',
                'last_platform',
                'last_device',
                'last_login_at',
                'login_count'
            ]);
        });
    }
};
