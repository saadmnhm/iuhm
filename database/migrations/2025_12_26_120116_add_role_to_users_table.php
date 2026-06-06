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

            // existing change
            $table->dropColumn('is_admin');
            $table->enum('role', ['user', 'admin', 'super_admin'])
                ->default('user')
                ->after('password');

            

            $table->unsignedBigInteger('address_id')->nullable()->after('role');
            $table->string('address_other')->nullable()->after('address_id');




        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // remove added fields
            $table->dropColumn([
                'role',
                'address_id',
                'address_other',
                'last_ip_address',
                'last_user_agent',
                'last_browser',
                'last_platform',
                'last_device',
                'last_login_at',
                'login_count',
            ]);

            $table->dropSoftDeletes();

            // restore old column
            $table->boolean('is_admin')->default(false)->after('password');
        });
    }
};
