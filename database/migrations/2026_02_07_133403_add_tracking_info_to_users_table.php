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
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_ip_address', 45)->nullable()->after('remember_token');
            $table->text('last_user_agent')->nullable()->after('last_ip_address');
            $table->string('last_browser')->nullable()->after('last_user_agent');
            $table->string('last_platform')->nullable()->after('last_browser');
            $table->string('last_device')->nullable()->after('last_platform');
            $table->timestamp('last_login_at')->nullable()->after('last_device');
            $table->integer('login_count')->default(0)->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
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
