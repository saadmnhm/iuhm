<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ── System roles ──────────────────────────────────────────────────────
        $systemRoles = [
            [
                'name'             => 'super_admin',
                'label'            => 'Super Admin',
                'color'            => 'purple',
                'is_system'        => true,
                'can_access_admin' => true,
            ],
            [
                'name'             => 'admin',
                'label'            => 'Admin',
                'color'            => 'blue',
                'is_system'        => true,
                'can_access_admin' => true,
            ],
        ];

        foreach ($systemRoles as $data) {
            Role::firstOrCreate(['name' => $data['name']], $data);
        }

        // ── Default module permissions for 'admin' ────────────────────────────
        // super_admin always gets everything (checked in code), no DB entry needed
        $adminModules = [
            'programmes',
            'support',
            'my_submissions',
            'all_submissions',
            'history_audit',
            'programe',
            'candidats',
            'addresses',
            'formulaires',
            'activity_logs',
            'blog',
        ];

        foreach ($adminModules as $module) {
            RolePermission::withTrashed()->updateOrCreate(
                [
                    'role_name'  => 'admin',
                    'module_key' => $module,
                ],
                [
                    'deleted_at' => null,
                ]
            );
        }

        // Clear all cached permissions
        Role::clearPermissionCache();
    }
}
