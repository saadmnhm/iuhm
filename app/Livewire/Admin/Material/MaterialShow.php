<?php

namespace App\Livewire\Admin\Material;

use App\Models\Material;
use Livewire\Component;

class MaterialShow extends Component
{
    public $material;

    public function mount($id)
    {
        $this->material = Material::with(['category', 'attachments', 'movements.creator', 'maintenances', 'creator'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.material.material-show')
            ->layout('layouts.admin', ['header' => 'Détail Matériel']);
    }
}
