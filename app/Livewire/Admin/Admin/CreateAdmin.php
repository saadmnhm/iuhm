<?php

namespace App\Livewire\Admin\Admin;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.admin', ['header' => 'Create Admin'])]
class CreateAdmin extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role = 'user';
    public $is_active = true;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => ['required', 'string', 'in:' . implode(',', Role::pluck('name')->toArray())],
            'is_active' => 'boolean',
        ];
    }

    public function createUser()
    {
        $currentUser = auth()->user();

        if (Role::isDevelopmentAccessLocked() && Role::canBypassDevelopmentLock($currentUser->role)) {
            // Whitelisted dev-access roles may manage users while the lock is active.
        } elseif (!$currentUser->isSuperAdmin() && !$currentUser->isAdmin()) {
            session()->flash('error', 'You do not have permission to create users.');
            return redirect()->route('admin.users.index');
        }

        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'User created successfully!');
        return redirect()->route('admin.users.show', $user->id);
    }

    public function render()
    {
        return view('livewire.admin.users.create-user');
    }
}
