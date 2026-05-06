<?php

namespace App\Livewire\Admin\User;

use App\Models\Role;
use App\Models\User;
use App\Models\Candidat;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;

class UserManagement extends Component
{
    use WithPagination;

    public ?int $currentUserId = null;
    public string $currentUserRole = '';
    public string $adminSearch = '';
    public string $adminRoleFilter = 'all';
    public string $candidatSearch = '';
    public string $candidatStatusFilter = 'all';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showShowModal = false;
    
    public $userTypeCreate = 'admin'; // 'admin' or 'candidat'
    public $editingUserType = null; // 'admin' or 'candidat' when editing
    public $isEditingUser = false; // True when editing, false when creating
    public $userId;
    public $editId = null; // ID of user being edited
    public $nom = ''; // For candidat
    public $prenom = ''; // For candidat
    public $email;
    public $password;
    public $password_confirmation = '';
    public $phone = '';
    public $role = 'admin';
    public $is_active = true;
    public $selectedUser = null;
    public array $allRoles = [];
    public array $statistics = [];
    public array $stat_section = [];
    
    protected $paginationTheme = 'tailwind';

    public function mount(): void
    {
        $this->loadSharedData();
    }

    private function loadSharedData(): void
    {
        $currentUser = Auth::user();

        $this->currentUserId = Auth::id();
        $this->currentUserRole = $currentUser?->role ?? '';

        $this->allRoles = Role::orderByDesc('is_system')
            ->orderBy('label')
            ->get(['name', 'label', 'color', 'is_system'])
            ->map(fn (Role $role) => [
                'name' => $role->name,
                'label' => $role->label,
                'color' => $role->color,
                'is_system' => (bool) $role->is_system,
            ])
            ->all();

        $this->refreshStatistics();
    }

    private function refreshStatistics(): void
    {
        $this->statistics = [
            'total_users' => User::count() + Candidat::count(),
            'admins' => User::count(),
            'total_candidats' => Candidat::count(),
        ];

        $this->stat_section = [
            [
                'label' => 'Total Utilisateurs',
                'value' => $this->statistics['total_users'],
                'icon' => 'ri-group-line',
            ],
            [
                'label' => 'Admins',
                'value' => $this->statistics['admins'],
                'icon' => 'ri-building-line',
            ],
            [
                'label' => 'Candidats',
                'value' => $this->statistics['total_candidats'],
                'icon' => 'ri-user-community-line',
            ],
        ];
    }


        public function openDeleteModal($userId)
        {
            $currentUser = Auth::user();

            if (!(Role::isDevelopmentAccessLocked() && Role::canBypassDevelopmentLock($currentUser->role)) && !$currentUser->isSuperAdmin()) {
                session()->flash('error', 'Only super admins can delete users.');
                return;
            }

            if ($userId == Auth::id()) {
                session()->flash('error', 'You cannot delete yourself.');
                return;
            }

            $this->userId = $userId;
            $this->selectedUser = User::find($userId);
            $this->showDeleteModal = true;
        }

        public function deleteUser()
        {
            $currentUser = Auth::user();

            if (!(Role::isDevelopmentAccessLocked() && Role::canBypassDevelopmentLock($currentUser->role)) && !$currentUser->isSuperAdmin()) {
                session()->flash('error', 'Only super admins can delete users.');
                return;
            }

            if ($this->userId == Auth::id()) {
                session()->flash('error', 'You cannot delete yourself.');
                return;
            }

            $user = User::findOrFail($this->userId);
            $user->delete();

            AdminActivityLog::log(
                'user_deleted',
                "Deleted user: {$user->nom} {$user->prenom} ({$user->email})",
                User::class,
                $user->id
            );

            $this->showDeleteModal = false;
            $this->selectedUser = null;
            session()->flash('success', 'User deleted successfully!');
            $this->userId = null;
            $this->refreshStatistics();
        }

        public function closeModals()
        {
            $this->showCreateModal = false;
            $this->showEditModal = false;
            $this->showDeleteModal = false;
            $this->showShowModal = false;
            $this->isEditingUser = false;
            $this->editingUserType = null;
            $this->userTypeCreate = 'admin';
            $this->selectedUser = null;
            $this->resetFormFields();
            $this->userId = null;
        }

        public function updatingAdminSearch(): void
        {
            $this->resetPage('adminsPage');
        }

        public function updatingAdminRoleFilter(): void
        {
            $this->resetPage('adminsPage');
        }

        public function updatingCandidatSearch(): void
        {
            $this->resetPage('candidatsPage');
        }

        public function updatingCandidatStatusFilter(): void
        {
            $this->resetPage('candidatsPage');
        }

        public function openCreateModal(): void
        {
            $this->isEditingUser = false;
            $this->editingUserType = null;
            $this->showCreateModal = true;
                $this->userTypeCreate = 'admin';
                $this->role = 'admin';
            $this->resetFormFields();
        }

        public function closeCreateModal(): void
        {
            $this->closeModals();
        }

        private function resetFormFields(): void
        {
            $this->nom = '';
            $this->prenom = '';
            $this->email = '';
            $this->password = '';
            $this->password_confirmation = '';
            $this->phone = '';
            $this->role = 'admin';
            $this->is_active = true;
            $this->editId = null;
        }

        public function openEditModal($id, $type = 'admin'): void
        {
            $this->editId = $id;
            $this->isEditingUser = true;
            $this->editingUserType = $type;
            $this->resetFormFields();
            $this->showCreateModal = true;


            if ($type === 'admin') {
                $user = User::find($id);
                if ($user) {
                    $this->nom = $user->nom;
                    $this->prenom = $user->prenom;
                    $this->email = $user->email;
                    $this->phone = $user->phone ?? '';
                    $this->role = $user->role;
                    $this->is_active = (bool) $user->is_active;
                }
            } else {
                $candidat = Candidat::find($id);
                if ($candidat) {
                    $this->nom = $candidat->nom;
                    $this->prenom = $candidat->prenom;
                    $this->email = $candidat->email;
                    $this->phone = $candidat->phone;
                    $this->is_active = (bool) $candidat->is_active;
                }
            }

        }

        public function createUser(): void
        {
            if ($this->isEditingUser) {
                $this->updateUser();
            } else {
                if ($this->userTypeCreate === 'admin') {
                    $this->createAdminUser();
                } else {
                    $this->createCandidatUser();
                }
            }
        }

        private function updateUser(): void
        {
            if ($this->editingUserType === 'admin') {
                $this->updateAdminUser();
            } else {
                $this->updateCandidatUser();
            }
        }

        private function updateAdminUser(): void
        {
            $validated = $this->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $this->editId,
                'phone' => 'nullable|string|max:20',
                'role' => 'required|exists:roles,name',
                'is_active' => 'sometimes|boolean',
            ]);

            try {
                $user = User::findOrFail($this->editId);
                $user->update([
                    'nom' => $validated['nom'],
                    'prenom' => $validated['prenom'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? $user->phone,
                    'role' => $validated['role'],
                    'is_active' => (bool) $this->is_active,
                ]);

                if ($this->password) {
                    $user->update(['password' => Hash::make($this->password)]);
                }

                AdminActivityLog::log(
                    'user_updated',
                    "Updated user: {$user->nom} {$user->prenom} ({$user->email})",
                    User::class,
                    $user->id
                );

                session()->flash('success', 'Admin mise à jour avec succès.');
                $this->closeCreateModal();
                $this->refreshStatistics();
            } catch (\Exception $e) {
                session()->flash('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
            }
        }

        private function updateCandidatUser(): void
        {
            $validated = $this->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:candidats,email,' . $this->editId,
                'phone' => 'required|string|max:20',
                'is_active' => 'sometimes|boolean',
            ]);

            try {
                $candidat = Candidat::findOrFail($this->editId);
                $candidat->update([
                    'nom' => $validated['nom'],
                    'prenom' => $validated['prenom'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'is_active' => (bool) $this->is_active,
                ]);

                if ($this->password) {
                    $candidat->update(['password' => Hash::make($this->password)]);
                }

                session()->flash('success', 'Bénéficiaire mise à jour avec succès.');
                $this->closeCreateModal();
                $this->refreshStatistics();
            } catch (\Exception $e) {
                session()->flash('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
            }
        }

        private function createAdminUser(): void
        {
            $validated = $this->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'required|exists:roles,name',
            ]);

            try {
                User::create([
                    'nom' => $validated['nom'],
                    'prenom' => $validated['prenom'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => $validated['role'],
                        'is_active' => (bool) $this->is_active,
                ]);

                session()->flash('success', 'Admin créé avec succès.');
                $this->closeCreateModal();
                $this->refreshStatistics();
            } catch (\Exception $e) {
                session()->flash('error', 'Erreur lors de la création: ' . $e->getMessage());
            }
        }

        private function createCandidatUser(): void
        {
            $validated = $this->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:candidats,email',
                'phone' => 'required|string|max:20',
                'password' => 'required|string|min:6|confirmed',
            ]);

            try {
                Candidat::create([
                    'nom' => $validated['nom'],
                    'prenom' => $validated['prenom'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'password' => Hash::make($validated['password']),
                    'is_active' => (bool) $this->is_active,
                ]);

                session()->flash('success', 'Bénéficiaire créé avec succès.');
                $this->closeCreateModal();
                $this->refreshStatistics();
            } catch (\Exception $e) {
                session()->flash('error', 'Erreur lors de la création: ' . $e->getMessage());
            }
        }

    public function render()
    {
            $queryAdmin = User::query()
                ->where('id', '!=', $this->currentUserId);

            if ($this->adminSearch !== '') {
                $queryAdmin->where(function ($q) {
                                        $q->where('nom', 'like', '%' . $this->adminSearch . '%')
                                            ->orWhere('prenom', 'like', '%' . $this->adminSearch . '%')
                                            ->orWhere('email', 'like', '%' . $this->adminSearch . '%');
                });
            }

            if ($this->adminRoleFilter !== 'all') {
                $queryAdmin->where('role', $this->adminRoleFilter);
            }

            $users = $queryAdmin
                ->select(['id', 'nom', 'prenom', 'email', 'role', 'is_active', 'created_at', 'updated_at'])
                ->orderBy('id')
                ->paginate(10, ['*'], 'adminsPage');

            $queryCandidat = Candidat::query();

            if ($this->candidatSearch !== '') {
                $queryCandidat->where(function ($q) {
                    $q->where('nom', 'like', '%' . $this->candidatSearch . '%')
                      ->orWhere('prenom', 'like', '%' . $this->candidatSearch . '%')
                      ->orWhere('email', 'like', '%' . $this->candidatSearch . '%')
                      ->orWhere('phone', 'like', '%' . $this->candidatSearch . '%');
                });
            }

            if ($this->candidatStatusFilter !== 'all') {
                $queryCandidat->where('is_active', $this->candidatStatusFilter === 'active');
            }

            $candidat = $queryCandidat
                ->select(['id', 'nom', 'prenom', 'email', 'phone', 'matricule', 'is_active', 'created_at', 'updated_at'])
                ->orderBy('id')
                ->paginate(10, ['*'], 'candidatsPage');

        return view('livewire.admin.users.user-management', [
            'users'      => $users,
                'statistics' => $this->statistics,
                'allRoles'   => $this->allRoles,
                'stat_section' => $this->stat_section,
            'candidat' => $candidat,
        ])->layout('layouts.admin', ['header' => 'Admin Management']);
    }
}
