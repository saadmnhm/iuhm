<?php

namespace App\Livewire\Admin\Rh;

use App\Models\RhEmployee;
use Livewire\Component;

class RhShow extends Component
{
    public $employee;

    public function mount($id)
    {
        $this->employee = RhEmployee::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.rh.rh-show')
            ->layout('layouts.admin', ['header' => 'Détail Employé']);
    }
}
