<?php

namespace App\Livewire\Admin\Candidat;

use App\Models\Candidat;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin', ['header' => 'View Candidat'])]
class ShowCandidat extends Component
{
    public $candidat;

    public function mount($id)
    {
        $this->candidat = Candidat::findOrFail($id);
    }

    public function toggleStatus()
    {
        if (!auth()->user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can change user status.');
            return;
        }

        $this->candidat->update([
            'is_active' => !$this->candidat->is_active
        ]);

        session()->flash('success', 'Candidat status updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.candidat.show-candidat');
    }
}
