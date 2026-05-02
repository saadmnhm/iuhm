<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'label', 'color', 'is_system', 'can_access_admin'];

    protected $casts = [
        'is_system'        => 'boolean',
        'can_access_admin' => 'boolean',
    ];

    // ─── Relations ────────────────────────────────────────────────────────────

    public function permissions(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'role_name', 'name');
    }

    // ─── Access Checks ────────────────────────────────────────────────────────

    /**
     * Check if a role can access a given module.
     * super_admin always has full unrestricted access.
     */
    public static function canAccess(string $roleName, string $moduleKey): bool
    {
        if ($roleName === 'super_admin') {
            return true;
        }

        return in_array($moduleKey, static::getPermissionsForRole($roleName));
    }

    /**
     * Return all permitted module keys for a role (cached 1 hour).
     */
    public static function getPermissionsForRole(string $roleName): array
    {
        return Cache::remember("role_module_perms.{$roleName}", 3600, function () use ($roleName) {
            return RolePermission::where('role_name', $roleName)->pluck('module_key')->toArray();
        });
    }

    /**
     * Check if a role has admin-panel access (cached 1 hour).
     */
    public static function hasAdminAccess(string $roleName): bool
    {
        if (in_array($roleName, ['admin', 'super_admin'])) {
            return true;
        }

        return Cache::remember("role_admin_access.{$roleName}", 3600, function () use ($roleName) {
            return static::where('name', $roleName)->where('can_access_admin', true)->exists();
        });
    }

    /**
     * Clear permission/admin-access cache for one role or all roles.
     */
    public static function clearPermissionCache(?string $roleName = null): void
    {
        if ($roleName) {
            Cache::forget("role_module_perms.{$roleName}");
            Cache::forget("role_admin_access.{$roleName}");
        } else {
            foreach (static::pluck('name') as $name) {
                Cache::forget("role_module_perms.{$name}");
                Cache::forget("role_admin_access.{$name}");
            }
        }
    }

    /**
     * Return the current development access lock settings.
     * super_admin is always kept as a safety bypass.
     */
    public static function developmentAccessSettings(): array
    {
        return Cache::remember('dev_access.settings', 3600, function () {
            $allowedRoles = static::decodeAccessRoles(
                AssociationParameter::get('dev_access_allowed_roles', '["super_admin"]')
            );

            if (!in_array('super_admin', $allowedRoles, true)) {
                array_unshift($allowedRoles, 'super_admin');
            }

            return [
                'enabled' => filter_var(AssociationParameter::get('dev_access_lock_enabled', '0'), FILTER_VALIDATE_BOOLEAN),
                'allowed_roles' => array_values(array_unique($allowedRoles)),
            ];
        });
    }

    public static function isDevelopmentAccessLocked(): bool
    {
        return (bool) static::developmentAccessSettings()['enabled'];
    }

    public static function developmentAccessAllowedRoles(): array
    {
        return static::developmentAccessSettings()['allowed_roles'];
    }

    public static function canBypassDevelopmentLock(?string $roleName): bool
    {
        if (!$roleName) {
            return false;
        }

        if ($roleName === 'super_admin') {
            return true;
        }

        return in_array($roleName, static::developmentAccessAllowedRoles(), true);
    }

    public static function setDevelopmentAccessSettings(bool $enabled, array $allowedRoles): void
    {
        $allowedRoles = array_values(array_unique(array_filter($allowedRoles)));

        if (!in_array('super_admin', $allowedRoles, true)) {
            $allowedRoles[] = 'super_admin';
        }

        AssociationParameter::updateOrCreate(
            ['key' => 'dev_access_lock_enabled'],
            [
                'category' => 'security',
                'label' => 'Development access lock',
                'value' => $enabled ? '1' : '0',
                'type' => 'boolean',
                'sort_order' => 10,
                'updated_by' => auth()->id(),
            ]
        );

        AssociationParameter::updateOrCreate(
            ['key' => 'dev_access_allowed_roles'],
            [
                'category' => 'security',
                'label' => 'Allowed roles for development access',
                'value' => json_encode($allowedRoles, JSON_UNESCAPED_UNICODE),
                'type' => 'textarea',
                'sort_order' => 11,
                'updated_by' => auth()->id(),
            ]
        );

        Cache::forget('dev_access.settings');
    }

    protected static function decodeAccessRoles(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter($value));
        }

        if (!is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter($decoded));
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    // ─── UI Helpers ───────────────────────────────────────────────────────────

    /**
     * Return Tailwind color classes for a role color string.
     * ['bg' => '...', 'badge' => '...', 'bar' => '...']
     */
    public static function colorClasses(string $color): array
    {
        return match ($color) {
            'blue'   => ['bg' => 'bg-blue-500',   'badge' => 'bg-blue-100 text-blue-800',   'bar' => 'bg-blue-500'],
            'green'  => ['bg' => 'bg-green-500',  'badge' => 'bg-green-100 text-green-800', 'bar' => 'bg-green-500'],
            'red'    => ['bg' => 'bg-red-500',    'badge' => 'bg-red-100 text-red-800',     'bar' => 'bg-red-500'],
            'yellow' => ['bg' => 'bg-yellow-500', 'badge' => 'bg-yellow-100 text-yellow-800','bar'=> 'bg-yellow-500'],
            'purple' => ['bg' => 'bg-purple-500', 'badge' => 'bg-purple-100 text-purple-800','bar'=> 'bg-purple-500'],
            'orange' => ['bg' => 'bg-orange-500', 'badge' => 'bg-orange-100 text-orange-800','bar'=> 'bg-orange-500'],
            'pink'   => ['bg' => 'bg-pink-500',   'badge' => 'bg-pink-100 text-pink-800',   'bar' => 'bg-pink-500'],
            'indigo' => ['bg' => 'bg-indigo-500', 'badge' => 'bg-indigo-100 text-indigo-800','bar'=> 'bg-indigo-500'],
            default  => ['bg' => 'bg-gray-500',   'badge' => 'bg-gray-100 text-gray-800',   'bar' => 'bg-gray-500'],
        };
    }
}
