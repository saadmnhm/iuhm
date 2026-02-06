<?php

namespace App\Livewire\Front\Dashboard;

use App\Models\SupportTicket;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class Support extends Component
{
    use WithPagination;

    public $subject = '';
    public $message = '';
    public $category = 'general';
    public $priority = 'medium';
    public $showCreateModal = false;

    protected $rules = [
        'subject' => 'required|string|min:5|max:255',
        'message' => 'required|string|min:10|max:2000',
        'category' => 'required|in:general,technical,account,form,other',
        'priority' => 'required|in:low,medium,high,urgent',
    ];

    public function openCreateModal()
    {
        $this->reset(['subject', 'message', 'category', 'priority']);
        $this->showCreateModal = true;
    }

    public function createTicket()
    {
        $this->validate();

        SupportTicket::create([
            'candidat_id' => Auth::guard('candidat')->id(),
            'subject' => $this->subject,
            'message' => $this->message,
            'category' => $this->category,
            'priority' => $this->priority,
            'status' => 'open',
        ]);

        $this->showCreateModal = false;
        $this->reset(['subject', 'message', 'category', 'priority']);
        session()->flash('success', 'Ticket créé avec succès! Notre équipe va vous répondre bientôt.');
    }

    public function render()
    {
        $tickets = SupportTicket::where('candidat_id', Auth::guard('candidat')->id())
            ->latest()
            ->paginate(10);

        return view('livewire.front.dashboard.support', [
            'tickets' => $tickets,
        ]);
    }
}
