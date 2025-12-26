<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = 'all';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showShowModal = false;
    
    public $userId;
    public $name;
    public $email;
    public $password;
    public $role = 'user';
    public $selectedUser = null;
    
    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->userId ?? 'NULL'),
            'role' => 'required|in:user,admin,super_admin',
        ];

        if ($this->showCreateModal) {
            $rules['password'] = 'required|min:6';
        } elseif ($this->password) {
            $rules['password'] = 'min:6';
        }

        return $rules;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            session()->flash('error', 'You do not have permission to create users.');
            return;
        }

        $this->reset(['name', 'email', 'password', 'role', 'userId']);
        $this->role = 'user';
        $this->showCreateModal = true;
    }

    public function openShowModal($userId)
    {
        $this->selectedUser = User::findOrFail($userId);
        $this->showShowModal = true;
    }

    public function openEditModal($userId)
    {
        if (!Auth::user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can edit users.');
            return;
        }

        $user = User::findOrFail($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->showEditModal = true;
    }

    public function openDeleteModal($userId)
    {
        if (!Auth::user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can delete users.');
            return;
        }

        if ($userId == Auth::id()) {
            session()->flash('error', 'You cannot delete yourself.');
            return;
        }

        $this->userId = $userId;
        $this->showDeleteModal = true;
    }

    public function createUser()
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            session()->flash('error', 'You do not have permission to create users.');
            return;
        }

        if (Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin() && $this->role !== 'user') {
            session()->flash('error', 'You can only create regular users.');
            return;
        }

        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        $this->showCreateModal = false;
        session()->flash('success', 'User created successfully!');
        $this->reset(['name', 'email', 'password', 'role']);
    }

    public function updateUser()
    {
        if (!Auth::user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can edit users.');
            return;
        }

        $this->validate();

        $user = User::findOrFail($this->userId);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        $this->showEditModal = false;
        session()->flash('success', 'User updated successfully!');
        $this->reset(['name', 'email', 'password', 'role', 'userId']);
    }

    public function deleteUser()
    {
        if (!Auth::user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can delete users.');
            return;
        }

        if ($this->userId == Auth::id()) {
            session()->flash('error', 'You cannot delete yourself.');
            return;
        }

        User::findOrFail($this->userId)->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'User deleted successfully!');
        $this->userId = null;
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showShowModal = false;
        $this->selectedUser = null;
        $this->reset(['name', 'email', 'password', 'role', 'userId']);
    }

    public function render()
    {
        $query = User::query();

        // Exclude current logged-in user
        $query->where('id', '!=', Auth::id());

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter !== 'all') {
            $query->where('role', $this->roleFilter);
        }

        $users = $query->latest()->paginate(10);

        $statistics = [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'super_admins' => User::where('role', 'super_admin')->count(),
            'regular_users' => User::where('role', 'user')->count(),
        ];

        return view('livewire.admin.users.user-management', [
            'users' => $users,
            'statistics' => $statistics,
        ])->layout('layouts.admin', ['header' => 'User Management']);
    }
}
