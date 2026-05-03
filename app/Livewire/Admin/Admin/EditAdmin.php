<?php

namespace App\Livewire\Admin\Admin;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.admin', ['header' => 'Edit Admin'])]
class EditAdmin extends Component
{
    public $userId;
    public $nom;
    public $prenom;
    public $email;
    public $password;
    public $role;
    public $is_active;

    public function mount($id)
    {
        $currentUser = auth()->user();

        if (!(Role::isDevelopmentAccessLocked() && Role::canBypassDevelopmentLock($currentUser->role)) && !$currentUser->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can edit users.');
            return redirect()->route('admin.users.index');
        }

        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->nom = $user->nom;
        $this->prenom = $user->prenom;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->is_active = $user->is_active ?? true;
    }

    protected function rules()
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => ['required', 'string', 'in:' . implode(',', Role::pluck('nom')->toArray())],
            'is_active' => 'boolean',
        ];

        if ($this->password) {
            $rules['password'] = 'min:6';
        }

        return $rules;
    }

    public function updateUser()
    {
        $currentUser = auth()->user();

        if (!(Role::isDevelopmentAccessLocked() && Role::canBypassDevelopmentLock($currentUser->role)) && !$currentUser->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can edit users.');
            return redirect()->route('admin.users.index');
        }

        $this->validate();

        $user = User::findOrFail($this->userId);

        $data = [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
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
