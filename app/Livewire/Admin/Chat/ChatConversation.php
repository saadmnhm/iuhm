<?php

namespace App\Livewire\Admin\Chat;

use App\Models\Candidat;
use App\Models\ChatMessage;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatConversation extends Component
{
    public int $candidatId;
    public string $newMessage = '';
    public ?Candidat $candidat = null;

    public function mount(int $candidatId): void
    {
        $this->candidatId = $candidatId;
        $this->candidat   = Candidat::withTrashed()->findOrFail($candidatId);

        // Mark all candidat messages as read when admin opens conversation
        ChatMessage::markCandidatMessagesRead($candidatId);
    }

    public function sendMessage(): void
    {
        $this->validate([
            'newMessage' => 'required|string|max:2000',
        ]);

        ChatMessage::create([
            'candidat_id' => $this->candidatId,
            'sender_type' => 'admin',
            'sender_id'   => Auth::id(),
            'message'     => trim($this->newMessage),
            'is_read'     => false,
        ]);

        $this->newMessage = '';
        $this->dispatch('messageSent');
    }

    public function refreshMessages(): void
    {
        // Mark new incoming messages as read on every poll
        ChatMessage::markCandidatMessagesRead($this->candidatId);
    }

    public function render()
    {
        $messages = ChatMessage::where('candidat_id', $this->candidatId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read on every render
        ChatMessage::markCandidatMessagesRead($this->candidatId);

        return view('livewire.admin.chat.chat-conversation', [
            'messages'  => $messages,
            'candidat'  => $this->candidat,
        ])->layout('layouts.admin', ['title' => 'Conversation avec ' . $this->candidat->full_name]);
    }
}
