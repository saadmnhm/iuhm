<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dynamic_form_fields', function (Blueprint $table) {
            if (!Schema::hasColumn('dynamic_form_fields', 'allow_multiple_files')) {
                $table->boolean('allow_multiple_files')->default(false)->after('options');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dynamic_form_fields', function (Blueprint $table) {
            if (Schema::hasColumn('dynamic_form_fields', 'allow_multiple_files')) {
                $table->dropColumn('allow_multiple_files');
            }
        });
    }
};
