<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Add new columns
        Schema::table('users', function (Blueprint $table) {
            $table->string('nom')->nullable()->after('id');
            $table->string('prenom')->nullable()->after('nom');
        });

        // 2. Split existing "name" into prenom + nom
        DB::table('users')->get()->each(function ($user) {
            $parts = explode(' ', $user->name, 2);

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'prenom' => $parts[0] ?? null,
                    'nom' => $parts[1] ?? null,
                ]);
        });

        // 3. Drop old column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        // Reverse: recreate name
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();
        });

        // Merge prenom + nom back into name
        DB::table('users')->get()->each(function ($user) {
            $fullName = trim(($user->prenom ?? '') . ' ' . ($user->nom ?? ''));

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'name' => $fullName,
                ]);
        });

        // Drop prenom & nom
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nom', 'prenom']);
        });
    }
};
