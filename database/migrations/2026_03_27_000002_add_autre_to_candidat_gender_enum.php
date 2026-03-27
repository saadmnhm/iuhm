<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE candidat MODIFY gender ENUM('homme','femme','autre') NULL");
    }

    public function down(): void
    {
        DB::statement("UPDATE candidat SET gender = 'homme' WHERE gender = 'autre'");
        DB::statement("ALTER TABLE candidat MODIFY gender ENUM('homme','femme') NULL");
    }
};
