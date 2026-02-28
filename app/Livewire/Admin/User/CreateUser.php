<?php

namespace App\Livewire\Admin\User;

use App\Models\Address;
use App\Models\Role;
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
    public $address_id = '';
    public $address_other = '';

    protected function rules()
    {
        return [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6|confirmed',
            'role'          => ['required', 'string', 'in:' . implode(',', Role::pluck('name')->toArray())],
            'is_active'     => 'boolean',
            'address_id'    => 'nullable',
            'address_other' => 'nullable|string|max:255|required_if:address_id,other',
        ];
    }

    public function createUser()
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin()) {
            session()->flash('error', 'You do not have permission to create users.');
            return redirect()->route('admin.users.index');
        }

        $this->validate();

        $user = User::create([
            'name'          => $this->name,
            'email'         => $this->email,
            'password'      => Hash::make($this->password),
            'role'          => $this->role,
            'is_active'     => $this->is_active,
            'address_id'    => $this->address_id === 'other' || $this->address_id === '' ? null : $this->address_id,
            'address_other' => $this->address_id === 'other' ? $this->address_other : null,
        ]);

        session()->flash('success', 'User created successfully!');
        return redirect()->route('admin.users.show', $user->id);
    }

    public function render()
    {
        return view('livewire.admin.users.create-user', [
            'addresses' => Address::orderBy('city')->orderBy('address_line1')->get(),
        ]);
    }
}
