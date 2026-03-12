<?php

namespace App\Livewire\Front\Dashboard;

use App\Models\ChatMessage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class Chat extends Component
{
    public string $newMessage = '';

    public function mount(): void
    {
        // Mark all admin messages as read when candidat opens chat
        ChatMessage::markAdminMessagesRead(Auth::guard('candidat')->id());
    }

    public function sendMessage(): void
    {
        $this->validate([
            'newMessage' => 'required|string|max:2000',
        ]);

        ChatMessage::create([
            'candidat_id' => Auth::guard('candidat')->id(),
            'sender_type' => 'candidat',
            'sender_id'   => null,
            'message'     => trim($this->newMessage),
            'is_read'     => false,
        ]);

        $this->newMessage = '';
        $this->dispatch('messageSent');
    }

    public function refreshMessages(): void
    {
        // Mark new admin messages as read whenever we poll
        ChatMessage::markAdminMessagesRead(Auth::guard('candidat')->id());
    }

    public function render()
    {
        $messages = ChatMessage::where('candidat_id', Auth::guard('candidat')->id())
            ->orderBy('created_at', 'asc')
            ->get();

        // Also mark as read on every render (polling)
        ChatMessage::markAdminMessagesRead(Auth::guard('candidat')->id());

        return view('livewire.front.dashboard.chat', [
            'messages' => $messages,
            'candidat' => Auth::guard('candidat')->user(),
        ]);
    }
}
