<?php

namespace App\Livewire\Admin\Chat;

use App\Models\AdminBroadcast;
use App\Models\Candidat;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class BroadcastMessage extends Component
{
    use WithPagination;

    // Form fields
    public string $title       = '';
    public string $message     = '';
    public string $targetType  = 'all';     // all | selected | single
    public $singleId = null;
    public array  $selectedIds = [];

    public bool $showForm = false;
    public string $search = '';

    protected $rules = [
        'title'        => 'required|string|max:255',
        'message'      => 'required|string|max:2000',
        'targetType'   => 'required|in:all,selected,single',
    ];

    public function updatedTargetType(string $value): void
    {
        if ($value === 'all') {
            $this->singleId = null;
            $this->selectedIds = [];
            return;
        }

        if ($value === 'single') {
            $this->selectedIds = [];
            return;
        }

        if ($value === 'selected') {
            $this->singleId = null;
        }
    }

    public function openForm(): void
    {
        $this->reset(['title', 'message', 'targetType', 'singleId', 'selectedIds']);
        $this->showForm = true;
    }

    public function sendBroadcast(): void
    {
        $rules = $this->rules;

        if ($this->targetType === 'single') {
            $rules['singleId'] = 'required|integer|exists:candidat,id';
        }

        if ($this->targetType === 'selected') {
            $rules['selectedIds'] = 'required|array|min:1';
            $rules['selectedIds.*'] = 'integer|exists:candidat,id';
        }

        $this->validate($rules);

        $singleId = $this->targetType === 'single' ? (int) $this->singleId : null;
        $selectedIds = $this->targetType === 'selected'
            ? array_values(array_map('intval', $this->selectedIds))
            : null;

        AdminBroadcast::create([
            'admin_id'            => Auth::id(),
            'title'               => $this->title,
            'message'             => $this->message,
            'target_type'         => $this->targetType,
            'target_candidat_ids' => $selectedIds,
            'target_candidat_id'  => $singleId,
            'is_active'           => true,
        ]);

        $this->showForm = false;
        $this->reset(['title', 'message', 'targetType', 'singleId', 'selectedIds']);
        session()->flash('broadcast_success', 'Message diffusé avec succès!');
    }

    public function deactivate(int $id): void
    {
        AdminBroadcast::findOrFail($id)->update(['is_active' => false]);
        session()->flash('broadcast_success', 'Broadcast désactivé.');
    }

    public function delete(int $id): void
    {
        AdminBroadcast::findOrFail($id)->delete();
        session()->flash('broadcast_success', 'Broadcast supprimé.');
    }

    public function render()
    {
        $broadcasts = AdminBroadcast::with('admin')
            ->latest()
            ->paginate(15);

        $candidats = Candidat::select('id', 'nom', 'prenom', 'email', 'matricule')
            ->when($this->search, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('nom',       'like', '%' . $this->search . '%')
                       ->orWhere('prenom',  'like', '%' . $this->search . '%')
                       ->orWhere('email',   'like', '%' . $this->search . '%')
                       ->orWhere('matricule','like', '%' . $this->search . '%')
                )
            )
            ->orderBy('nom')
            ->limit(50)
            ->get();

        return view('livewire.admin.chat.broadcast-message', [
            'broadcasts' => $broadcasts,
            'candidats'  => $candidats,
        ])->layout('layouts.admin', ['title' => 'Diffusion de messages']);
    }
}
