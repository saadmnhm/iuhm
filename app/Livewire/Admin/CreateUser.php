<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.admin', ['header' => 'Create User'])]
class CreateUser extends Component
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
            'role' => 'required|in:user,admin,super_admin',
            'is_active' => 'boolean',
        ];
    }

    public function createUser()
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin()) {
            session()->flash('error', 'You do not have permission to create users.');
            return redirect()->route('admin.users.index');
        }

        // Regular admins can only create users, not other admins
        if (auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin() && $this->role !== 'user') {
            session()->flash('error', 'You can only create regular users.');
            return;
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
