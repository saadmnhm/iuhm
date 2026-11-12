<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@admin.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'Super',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // Create Regular Admin
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'Regular',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}
