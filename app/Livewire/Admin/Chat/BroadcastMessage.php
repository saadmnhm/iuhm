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
    public ?int   $singleId    = null;
    public array  $selectedIds = [];

    public bool $showForm = false;
    public string $search = '';

    protected $rules = [
        'title'        => 'required|string|max:255',
        'message'      => 'required|string|max:2000',
        'targetType'   => 'required|in:all,selected,single',
        'singleId'     => 'nullable|required_if:targetType,single|exists:candidat,id',
        'selectedIds'  => 'nullable|required_if:targetType,selected|array|min:1',
        'selectedIds.*'=> 'exists:candidat,id',
    ];

    public function openForm(): void
    {
        $this->reset(['title', 'message', 'targetType', 'singleId', 'selectedIds']);
        $this->showForm = true;
    }

    public function sendBroadcast(): void
    {
        $this->validate();

        AdminBroadcast::create([
            'admin_id'            => Auth::id(),
            'title'               => $this->title,
            'message'             => $this->message,
            'target_type'         => $this->targetType,
            'target_candidat_ids' => $this->targetType === 'selected' ? $this->selectedIds : null,
            'target_candidat_id'  => $this->targetType === 'single'   ? $this->singleId   : null,
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
