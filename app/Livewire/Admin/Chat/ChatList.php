<?php

namespace App\Livewire\Admin\Chat;

use App\Models\Candidat;
use App\Models\ChatMessage;
use Livewire\Component;
use Livewire\WithPagination;

class ChatList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        // Get distinct candidat_ids that have at least one message
        $candidatIdsWithMessages = ChatMessage::select('candidat_id')
            ->distinct()
            ->pluck('candidat_id');

        // Build candidat list with stats (merge all candidats + those with messages)
        $candidats = Candidat::withTrashed()
            ->when($this->search, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('nom', 'like', '%' . $this->search . '%')
                       ->orWhere('prenom', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%')
                       ->orWhere('matricule', 'like', '%' . $this->search . '%')
                )
            )
            ->whereIn('id', $candidatIdsWithMessages)
            ->orderByDesc(
                ChatMessage::select('created_at')
                    ->whereColumn('candidat_id', 'candidat.id')
                    ->latest()
                    ->limit(1)
            )
            ->paginate(20);

        // Attach unread count and last message
        $candidats->getCollection()->transform(function ($candidat) {
            $candidat->unread_count = ChatMessage::where('candidat_id', $candidat->id)
                ->where('sender_type', 'candidat')
                ->where('is_read', false)
                ->count();

            $candidat->last_message = ChatMessage::where('candidat_id', $candidat->id)
                ->latest()
                ->first();

            return $candidat;
        });

        $totalUnread = ChatMessage::totalUnreadForAdmin();

        return view('livewire.admin.chat.chat-list', [
            'candidats'    => $candidats,
            'totalUnread'  => $totalUnread,
        ])->layout('layouts.admin', ['title' => 'Conversations']);
    }
}
