<?php

namespace App\Livewire\Front\Dashboard;

use App\Models\AdminBroadcast;
use App\Models\BroadcastRead;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class BroadcastHistory extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter = 'all'; // all | read | unread

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $candidat = Auth::guard('candidat')->user();

        $readIds = BroadcastRead::where('candidat_id', $candidat->id)
            ->pluck('broadcast_id')
            ->toArray();

        $broadcasts = AdminBroadcast::where('is_active', true)
            ->where(function ($q) use ($candidat) {
                $q->where('target_type', 'all')
                  ->orWhere(function ($q2) use ($candidat) {
                      $q2->where('target_type', 'single')
                         ->where('target_candidat_id', $candidat->id);
                  })
                  ->orWhere(function ($q3) use ($candidat) {
                      $q3->where('target_type', 'selected')
                         ->whereJsonContains('target_candidat_ids', (int) $candidat->id);
                  });
            })
            ->when($this->search, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('title',   'like', '%' . $this->search . '%')
                       ->orWhere('message','like', '%' . $this->search . '%')
                )
            )
            ->when($this->filter === 'read',   fn($q) => $q->whereIn('id', $readIds))
            ->when($this->filter === 'unread', fn($q) => empty($readIds) ? $q : $q->whereNotIn('id', $readIds))
            ->latest()
            ->paginate(10);

        return view('livewire.front.dashboard.broadcast-history', [
            'broadcasts' => $broadcasts,
            'readIds'    => $readIds,
        ])->layout('layouts.app', ['pageTitle' => 'Historique des messages']);
    }
}
