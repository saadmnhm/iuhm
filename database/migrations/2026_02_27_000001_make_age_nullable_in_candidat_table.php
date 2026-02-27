<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidat', function (Blueprint $table) {
            if (Schema::hasColumn('candidat', 'age')) {
                $table->integer('age')->nullable()->default(null)->change();
            } else {
                $table->integer('age')->nullable()->default(null)->after('prenom');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidat', function (Blueprint $table) {
            if (Schema::hasColumn('candidat', 'age')) {
                $table->integer('age')->nullable(false)->change();
            }
        });
    }
};
