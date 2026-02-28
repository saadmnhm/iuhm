<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change enum to varchar so any text can be stored
        DB::statement("ALTER TABLE `candidat` MODIFY `address` VARCHAR(500) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `candidat` MODIFY `address` ENUM('Hay Mohamadi','Ain Sbaa','Roches Noires') NULL");
    }
};
