<?php

namespace App\Livewire\Admin\Roles;

use App\Models\AdminActivityLog;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class RoleManagement extends Component
{
    use WithPagination;

    // ── Page state ────────────────────────────────────────────────────────────
    public string $tab = 'roles';
    public string $logsSearch = '';
    public string $logsActionFilter = 'all';
    public string $logsDateFrom = '';
    public string $logsDateTo = '';

    // ── Create / Edit modal ───────────────────────────────────────────────────
    public bool    $showRoleModal   = false;
    public ?int    $editingRoleId   = null;
    public bool    $editingIsSystem = false;
    public string  $roleName        = '';
    public string  $roleLabel       = '';
    public string  $roleColor       = 'blue';
    public bool    $canAccessAdmin  = true;
    public int     $roleModalStep   = 1;
    public array   $roleWizardPerms = [];

    // ── Delete modal ─────────────────────────────────────────────────────────
    public bool    $showDeleteModal    = false;
    public ?int    $deletingRoleId     = null;
    public string  $deletingRoleLabel  = '';
    public bool    $deletingRoleSystem = false;

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

    public function updatingLogsSearch(): void
    {
        $this->resetPage('logsPage');
    }

    public function updatingLogsActionFilter(): void
    {
        $this->resetPage('logsPage');
    }

    public function updatingLogsDateFrom(): void
    {
        $this->resetPage('logsPage');
    }

    public function updatingLogsDateTo(): void
    {
        $this->resetPage('logsPage');
    }

    public function updatingTab(): void
    {
        $this->resetPage('rolesPage');
        $this->resetPage('logsPage');
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function openCreate(): void
    {
        $this->reset(['editingRoleId', 'roleName', 'roleLabel', 'successMsg', 'errorMsg', 'roleWizardPerms']);
        $this->resetValidation();
        $this->editingIsSystem = false;
        $this->roleColor       = 'blue';
        $this->canAccessAdmin  = true;
        $this->roleModalStep   = 1;
        $this->showRoleModal   = true;
    }

    public function updatedRoleLabel(): void
    {
        if ($this->editingIsSystem && $this->editingRoleId) {
            return;
        }

        $this->roleName = Str::slug($this->roleLabel, '_');
    }

    public function openEdit(int $id): void
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->editingRoleId   = $id;
        $this->editingIsSystem = $role->is_system;
        $this->roleName        = $role->name;
        $this->roleLabel       = $role->label;
        $this->roleColor       = $role->color;
        $this->canAccessAdmin  = $role->can_access_admin;
        $this->roleWizardPerms = $role->permissions->pluck('module_key')->toArray();
        $this->roleModalStep   = 1;
        $this->successMsg      = $this->errorMsg = null;
        $this->resetValidation();
        $this->showRoleModal   = true;
    }

    public function nextRoleStep(): void
    {
        if ($this->roleModalStep === 1) {
            $this->prepareTechnicalName();
            $this->validateRoleWizardStep();
            $this->roleModalStep = 2;
            return;
        }

        if ($this->roleModalStep === 2) {
            $this->roleModalStep = 3;
        }
    }

    public function previousRoleStep(): void
    {
        $this->roleModalStep = max(1, $this->roleModalStep - 1);
    }

    private function prepareTechnicalName(): void
    {
        if (empty(trim($this->roleName)) && !empty(trim($this->roleLabel))) {
            $this->roleName = Str::slug($this->roleLabel, '_');
        }

        $original = $this->roleName;
        $i = 1;
        while (Role::where('name', $this->roleName)
            ->when($this->editingRoleId, fn($q) => $q->where('id', '!=', $this->editingRoleId))
            ->exists()) {
            $this->roleName = $original . '_' . $i;
            $i++;
        }
    }

    private function validateRoleWizardStep(): void
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
    }

    public function saveRole(): void
    {
        $this->prepareTechnicalName();
        $this->validateRoleWizardStep();

        DB::transaction(function () {
            if ($this->editingRoleId) {
                $role = Role::with('permissions')->findOrFail($this->editingRoleId);
                $previousName = $role->name;
                $data = ['label' => $this->roleLabel, 'color' => $this->roleColor, 'can_access_admin' => $this->canAccessAdmin];

                if (!$role->is_system) {
                    $data['name'] = $this->roleName;
                }

                $role->update($data);

                if (!$role->is_system && $previousName !== $this->roleName) {
                    RolePermission::withTrashed()->where('role_name', $previousName)->update(['role_name' => $this->roleName]);
                    Role::clearPermissionCache($previousName);
                }

                RolePermission::withTrashed()->where('role_name', $this->roleName)->forceDelete();
                foreach ($this->roleWizardPerms as $key) {
                    RolePermission::create(['role_name' => $this->roleName, 'module_key' => $key]);
                }

                Role::clearPermissionCache($this->roleName);
                AdminActivityLog::log('role_updated', "Rôle modifié: {$role->label}", Role::class, $role->id);
            } else {
                $role = Role::create([
                    'name'             => $this->roleName,
                    'label'            => $this->roleLabel,
                    'color'            => $this->roleColor,
                    'can_access_admin' => $this->canAccessAdmin,
                    'is_system'        => false,
                ]);

                foreach ($this->roleWizardPerms as $key) {
                    RolePermission::create(['role_name' => $this->roleName, 'module_key' => $key]);
                }

                Role::clearPermissionCache($this->roleName);
                AdminActivityLog::log('role_created', "Nouveau rôle créé: {$role->label}", Role::class, $role->id);
            }
        });

        $this->showRoleModal = false;
        $this->roleModalStep = 1;
        $this->successMsg    = 'Rôle enregistré avec succès.';
        $this->errorMsg      = null;
    }

    public function openDeleteModal(int $id): void
    {
        $role = Role::findOrFail($id);
        $this->deletingRoleId     = $role->id;
        $this->deletingRoleLabel  = $role->label;
        $this->deletingRoleSystem = $role->is_system;
        $this->showDeleteModal    = true;
        $this->errorMsg           = null;
        $this->successMsg         = null;
    }

    public function deleteRole(): void
    {
        if (!$this->deletingRoleId) {
            return;
        }

        $role = Role::findOrFail($this->deletingRoleId);

        if ($role->is_system) {
            $this->errorMsg = 'Impossible de supprimer un rôle système.';
            $this->showDeleteModal = false;
            return;
        }

        $label = $role->label;
        RolePermission::withTrashed()->where('role_name', $role->name)->forceDelete();
        Role::clearPermissionCache($role->name);
        $role->delete();

        AdminActivityLog::log('role_deleted', "Rôle supprimé: {$label}", Role::class, $role->id);
        $this->showDeleteModal    = false;
        $this->deletingRoleId     = null;
        $this->deletingRoleLabel  = '';
        $this->deletingRoleSystem = false;
        $this->successMsg         = "Rôle \"{$label}\" supprimé.";
        $this->errorMsg           = null;
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

        RolePermission::withTrashed()->where('role_name', $this->permsRoleName)->forceDelete();
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
        $roles = Role::withCount('permissions')
            ->with('permissions')
            ->orderByDesc('is_system')
            ->orderBy('name')
            ->paginate(10, ['*'], 'rolesPage');

        $logs = null;

        if ($this->tab === 'logs') {
            $logsQuery = AdminActivityLog::with('user')->latest();

            if ($this->logsSearch !== '') {
                $logsQuery->where(function ($query) {
                    $query->where('description', 'like', '%' . $this->logsSearch . '%')
                        ->orWhere('action', 'like', '%' . $this->logsSearch . '%')
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'like', '%' . $this->logsSearch . '%')
                                ->orWhere('email', 'like', '%' . $this->logsSearch . '%');
                        });
                });
            }

            if ($this->logsActionFilter !== 'all') {
                $logsQuery->where('action', $this->logsActionFilter);
            }

            if ($this->logsDateFrom !== '') {
                $logsQuery->whereDate('created_at', '>=', $this->logsDateFrom);
            }

            if ($this->logsDateTo !== '') {
                $logsQuery->whereDate('created_at', '<=', $this->logsDateTo);
            }

            $logs = $logsQuery->paginate(10, ['*'], 'logsPage');
        }

        return view('livewire.admin.roles.role-management', [
            'roles'      => $roles,
            'logs'       => $logs,
            'actions'    => AdminActivityLog::select('action')->groupBy('action')->pluck('action'),
            'allModules' => config('modules.definitions', []),
        ])->layout('layouts.admin', ['header' => 'Gestion des Rôles']);
    }
}
