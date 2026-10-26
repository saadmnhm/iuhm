<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Some installations already have this column (it is referenced by the
        // admin dashboard ticket form). Only add it when it is missing.
        if (! Schema::hasColumn('support_tickets', 'image')) {
            Schema::table('support_tickets', function (Blueprint $table) {
                $table->string('image')->nullable()->after('message');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('support_tickets', 'image')) {
            Schema::table('support_tickets', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
