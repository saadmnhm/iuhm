<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin', ['header' => 'View User'])]
class ShowUser extends Component
{
    public $user;

    public function mount($id)
    {
        $this->user = User::findOrFail($id);
    }

    public function toggleStatus()
    {
        if (!auth()->user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can change user status.');
            return;
        }

        if ($this->user->id === auth()->id()) {
            session()->flash('error', 'You cannot disable your own account.');
            return;
        }

        $this->user->update([
            'is_active' => !$this->user->is_active
        ]);

        session()->flash('success', 'User status updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.users.show-user');
    }
}
