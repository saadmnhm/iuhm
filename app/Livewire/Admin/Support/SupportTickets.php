<?php

namespace App\Livewire\Admin\Support;

use App\Models\SupportTicket;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class SupportTickets extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $priorityFilter = 'all';
    
    // Response modal
    public $showResponseModal = false;
    public $selectedTicketId = null;
    public $adminResponse = '';
    public $newStatus = '';
    
    protected $paginationTheme = 'tailwind';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function openResponseModal($ticketId)
    {
        $ticket = SupportTicket::findOrFail($ticketId);
        $this->selectedTicketId = $ticket->id;
        $this->adminResponse = $ticket->admin_response ?? '';
        $this->newStatus = $ticket->status;
        $this->showResponseModal = true;
    }

    public function respondToTicket()
    {
        $this->validate([
            'adminResponse' => 'required|string|min:5',
            'newStatus' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket = SupportTicket::findOrFail($this->selectedTicketId);
        $ticket->update([
            'admin_response' => $this->adminResponse,
            'status' => $this->newStatus,
            'assigned_to' => Auth::id(),
            'responded_at' => now(),
        ]);

        AdminActivityLog::log(
            'support_ticket_responded',
            "Responded to support ticket #{$ticket->id}",
            SupportTicket::class,
            $ticket->id,
            ['status' => $this->newStatus]
        );

        $this->showResponseModal = false;
        $this->selectedTicketId = null;
        $this->adminResponse = '';
        session()->flash('success', 'Réponse envoyée avec succès!');
    }

    public function closeModal()
    {
        $this->showResponseModal = false;
        $this->selectedTicketId = null;
    }

    public function render()
    {
        $query = SupportTicket::with(['candidat', 'assignedAdmin'])->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('subject', 'like', '%' . $this->search . '%')
                  ->orWhereHas('candidat', function($q) {
                      $q->where('nom', 'like', '%' . $this->search . '%')
                        ->orWhere('prenom', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->priorityFilter !== 'all') {
            $query->where('priority', $this->priorityFilter);
        }

        $tickets = $query->paginate(15);

        $statistics = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
        ];

        return view('livewire.admin.support.tickets', [
            'tickets' => $tickets,
            'statistics' => $statistics,
        ])->layout('layouts.admin', ['header' => 'Support - Tickets']);
    }
}
