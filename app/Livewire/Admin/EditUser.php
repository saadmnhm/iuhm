<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.admin', ['header' => 'Edit User'])]
class EditUser extends Component
{
    public $userId;
    public $name;
    public $email;
    public $password;
    public $role;
    public $is_active;

    public function mount($id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can edit users.');
            return redirect()->route('admin.users.index');
        }

        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->is_active = $user->is_active ?? true;
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|in:user,admin,super_admin',
            'is_active' => 'boolean',
        ];

        if ($this->password) {
            $rules['password'] = 'min:6';
        }

        return $rules;
    }

    public function updateUser()
    {
        if (!auth()->user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can edit users.');
            return redirect()->route('admin.users.index');
        }

        $this->validate();

        $user = User::findOrFail($this->userId);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        session()->flash('success', 'User updated successfully!');
        return redirect()->route('admin.users.show', $user->id);
    }

    public function render()
    {
        return view('livewire.admin.users.edit-user');
    }
}
