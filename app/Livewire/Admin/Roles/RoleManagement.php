<?php

namespace App\Livewire\Admin\Roles;

use App\Models\AdminActivityLog;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\View\View;
use Livewire\Component;

class RoleManagement extends Component
{
    // ── Create / Edit modal ───────────────────────────────────────────────────
    public bool    $showRoleModal   = false;
    public ?int    $editingRoleId   = null;
    public bool    $editingIsSystem = false;
    public string  $roleName        = '';
    public string  $roleLabel       = '';
    public string  $roleColor       = 'blue';
    public bool    $canAccessAdmin  = true;

    // ── Permissions modal ─────────────────────────────────────────────────────
    public bool    $showPermsModal    = false;
    public string  $permsRoleName     = '';
    public string  $permsRoleLabel    = '';
    public array   $selectedPerms     = [];
    public bool    $permsIsSuperAdmin = false;

    // ── Feedback ──────────────────────────────────────────────────────────────
    public ?string $successMsg = null;
    public ?string $errorMsg   = null;

    public array $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'orange', 'pink', 'indigo', 'gray'];

    // ── Role CRUD ─────────────────────────────────────────────────────────────

    public function openCreate(): void
    {
        $this->reset(['editingRoleId', 'roleName', 'roleLabel', 'successMsg', 'errorMsg']);
        $this->editingIsSystem = false;
        $this->roleColor       = 'blue';
        $this->canAccessAdmin  = true;
        $this->showRoleModal   = true;
    }

    public function openEdit(int $id): void
    {
        $role = Role::findOrFail($id);
        $this->editingRoleId   = $id;
        $this->editingIsSystem = $role->is_system;
        $this->roleName        = $role->name;
        $this->roleLabel       = $role->label;
        $this->roleColor       = $role->color;
        $this->canAccessAdmin  = $role->can_access_admin;
        $this->successMsg      = $this->errorMsg = null;
        $this->showRoleModal   = true;
    }

    public function saveRole(): void
    {
        $uniqueRule = 'unique:roles,name' . ($this->editingRoleId ? ",{$this->editingRoleId}" : '');
        $this->validate([
            'roleName'  => ['required', 'regex:/^[a-z0-9_]+$/', 'max:50', $uniqueRule],
            'roleLabel' => ['required', 'string', 'max:100'],
            'roleColor' => ['required'],
        ], [
            'roleName.required' => 'Le nom technique est obligatoire.',
            'roleName.regex'    => 'Uniquement lettres minuscules, chiffres et _ (ex: responsable_rh).',
            'roleName.unique'   => 'Ce nom de rôle existe déjà.',
            'roleLabel.required'=> "Le nom d'affichage est obligatoire.",
        ]);

        if ($this->editingRoleId) {
            $role = Role::findOrFail($this->editingRoleId);
            $data = ['label' => $this->roleLabel, 'color' => $this->roleColor, 'can_access_admin' => $this->canAccessAdmin];
            if (!$role->is_system) {
                $data['name'] = $this->roleName;
            }
            $role->update($data);
            Role::clearPermissionCache($role->name);
            AdminActivityLog::log('role_updated', "Rôle modifié: {$role->label}", Role::class, $role->id);
        } else {
            $role = Role::create([
                'name'             => $this->roleName,
                'label'            => $this->roleLabel,
                'color'            => $this->roleColor,
                'can_access_admin' => $this->canAccessAdmin,
                'is_system'        => false,
            ]);
            AdminActivityLog::log('role_created', "Nouveau rôle créé: {$role->label}", Role::class, $role->id);
        }

        $this->showRoleModal = false;
        $this->successMsg    = 'Rôle enregistré avec succès.';
    }

    public function deleteRole(int $id): void
    {
        $role = Role::findOrFail($id);

        if ($role->is_system) {
            $this->errorMsg = 'Impossible de supprimer un rôle système.';
            return;
        }

        $label = $role->label;
        RolePermission::where('role_name', $role->name)->delete();
        Role::clearPermissionCache($role->name);
        $role->delete();

        AdminActivityLog::log('role_deleted', "Rôle supprimé: {$label}", Role::class, $id);
        $this->successMsg = "Rôle \"{$label}\" supprimé.";
        $this->errorMsg   = null;
    }

    // ── Permissions ───────────────────────────────────────────────────────────

    public function openPermissions(string $roleName): void
    {
        $role = Role::where('name', $roleName)->firstOrFail();
        $this->permsRoleName     = $roleName;
        $this->permsRoleLabel    = $role->label;
        $this->permsIsSuperAdmin = ($roleName === 'super_admin');
        $this->selectedPerms     = RolePermission::where('role_name', $roleName)->pluck('module_key')->toArray();
        $this->successMsg        = $this->errorMsg = null;
        $this->showPermsModal    = true;
    }

    public function savePermissions(): void
    {
        if ($this->permsIsSuperAdmin) {
            $this->showPermsModal = false;
            return;
        }

        RolePermission::where('role_name', $this->permsRoleName)->delete();
        foreach ($this->selectedPerms as $key) {
            RolePermission::create(['role_name' => $this->permsRoleName, 'module_key' => $key]);
        }
        Role::clearPermissionCache($this->permsRoleName);

        AdminActivityLog::log('permissions_updated', "Permissions mises à jour: {$this->permsRoleLabel}", Role::class, null);
        $this->showPermsModal = false;
        $this->successMsg     = 'Permissions mises à jour avec succès.';
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render(): View
    {
        return view('livewire.admin.roles.role-management', [
            'roles'      => Role::withCount('permissions')->orderByDesc('is_system')->orderBy('name')->get(),
            'allModules' => config('modules.definitions', []),
        ])->layout('layouts.admin', ['header' => 'Gestion des Rôles']);
    }
}
