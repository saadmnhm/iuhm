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
        $candidat = Auth::guard('candidat')->user();
        if (!$candidat) return;

        $broadcasts = AdminBroadcast::unreadForCandidat($candidat->id);
        $this->queue = $broadcasts->pluck('id')->toArray();

        $this->unreadChatCount = ChatMessage::unreadForCandidat($candidat->id);

        $this->showNext();
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

    public function dismiss(): void
    {
        $candidat = Auth::guard('candidat')->user();
        if ($this->current && $candidat) {
            BroadcastRead::firstOrCreate([
                'broadcast_id' => $this->current->id,
                'candidat_id'  => $candidat->id,
            ]);
        }
        $this->showNext();
    }

    public function render()
    {
        return view('livewire.front.dashboard.broadcast-popup');
    }
}
