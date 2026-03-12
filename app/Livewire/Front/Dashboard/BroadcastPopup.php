<?php

namespace App\Livewire\Front\Dashboard;

use App\Models\AdminBroadcast;
use App\Models\BroadcastRead;
use App\Models\ChatMessage;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class BroadcastPopup extends Component
{
    /** The broadcast currently shown in the popup */
    public ?AdminBroadcast $current = null;

    /** Queue of broadcast IDs not yet dismissed */
    public array $queue = [];

    public int $unreadChatCount = 0;

    public function mount(): void
    {
        $this->loadUnread();
    }

    private function loadUnread(): void
    {
        $candidat = Auth::guard('candidat')->user();
        if (!$candidat) return;

        $broadcasts = AdminBroadcast::unreadForCandidat($candidat->id);

        // Merge newly found IDs into queue (avoid duplicates with current)
        $existingIds = $this->current
            ? array_merge([$this->current->id], $this->queue)
            : $this->queue;
        $newIds = $broadcasts->pluck('id')->diff($existingIds)->values()->toArray();
        $this->queue = array_merge($this->queue, $newIds);

        $this->unreadChatCount = ChatMessage::unreadForCandidat($candidat->id);

        if (!$this->current) {
            $this->showNext();
        }
    }

    /** Called by wire:poll every 15s to pick up newly sent broadcasts */
    public function refresh(): void
    {
        $this->loadUnread();
    }

    private function showNext(): void
    {
        if (empty($this->queue)) {
            $this->current = null;
            return;
        }
        $id = array_shift($this->queue);
        $this->current = AdminBroadcast::find($id);
    }

    /** Mark current broadcast as read and advance to next */
    public function markRead(): void
    {
        $candidat = Auth::guard('candidat')->user();
        if ($this->current && $candidat) {
            BroadcastRead::firstOrCreate([
                'broadcast_id' => $this->current->id,
                'candidat_id'  => $candidat->id,
            ], [
                'read_at' => now(),
            ]);
        }
        $this->showNext();
    }

    public function render()
    {
        return view('livewire.front.dashboard.broadcast-popup');
    }
}
