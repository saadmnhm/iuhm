<?php

namespace App\Livewire\Admin\Support;

use App\Models\AdminActivityLog;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SupportTickets extends Component
{
    use WithPagination;

    /** Active source tab: candidats | admin */
    public $tab = 'candidats';

    // Filters
    public $search = '';
    public $statusFilter = 'all';
    public $priorityFilter = 'all';
    public $categoryFilter = 'all';
    public $assignedFilter = 'all';

    // Ticket detail modal
    public $showTicketModal = false;
    public $selectedTicketId = null;
    public $selectedTicket = null;
    public $adminResponse = '';
    public $newStatus = 'open';

    // Delete confirmation
    public $showDeleteModal = false;
    public $deleteTicketId = null;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingPriorityFilter() { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }
    public function updatingAssignedFilter() { $this->resetPage(); }

    public function setTab($tab)
    {
        if (! in_array($tab, ['candidats', 'admin'], true)) {
            return;
        }

        $this->tab = $tab;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'priorityFilter', 'categoryFilter', 'assignedFilter']);
        $this->resetPage();
    }

    public function openTicketModal($ticketId)
    {
        $ticket = SupportTicket::with(['candidat', 'assignedAdmin'])->findOrFail($ticketId);

        $this->selectedTicketId = $ticket->id;
        $this->selectedTicket = $ticket;
        $this->adminResponse = $ticket->admin_response ?? '';
        $this->newStatus = $ticket->status;
        $this->showTicketModal = true;
    }

    public function closeTicketModal()
    {
        $this->showTicketModal = false;
        $this->selectedTicketId = null;
        $this->selectedTicket = null;
        $this->adminResponse = '';
        $this->resetValidation();
    }

    public function updateStatus()
    {
        $this->validate([
            'newStatus' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket = SupportTicket::findOrFail($this->selectedTicketId);
        $oldStatus = $ticket->status;

        $ticket->update([
            'status' => $this->newStatus,
            'assigned_to' => $ticket->assigned_to ?? Auth::id(),
        ]);

        AdminActivityLog::log(
            'support_ticket_status_changed',
            "Ticket #{$ticket->id} status changed from {$oldStatus} to {$this->newStatus}",
            SupportTicket::class,
            $ticket->id,
            ['old_status' => $oldStatus, 'new_status' => $this->newStatus]
        );

        $this->refreshSelectedTicket();
        session()->flash('success', 'Statut du ticket mis à jour.');
    }

    public function respondToTicket()
    {
        $this->validate([
            'adminResponse' => 'required|string|min:2',
        ]);

        $ticket = SupportTicket::findOrFail($this->selectedTicketId);
        $oldStatus = $ticket->status;

        // Use the status chosen in the modal, but auto-advance a still-open
        // ticket to "in progress" once the administration replies to it.
        $status = in_array($this->newStatus, ['open', 'in_progress', 'resolved', 'closed'], true)
            ? $this->newStatus
            : $oldStatus;

        if ($status === 'open' && $oldStatus === 'open') {
            $status = 'in_progress';
        }

        $ticket->update([
            'admin_response' => $this->adminResponse,
            'status' => $status,
            'assigned_to' => Auth::id(),
            'responded_at' => now(),
        ]);

        AdminActivityLog::log(
            'support_ticket_responded',
            "Responded to support ticket #{$ticket->id}: status {$oldStatus} → {$status}",
            SupportTicket::class,
            $ticket->id,
            ['old_status' => $oldStatus, 'status' => $status, 'response_length' => strlen($this->adminResponse)]
        );

        $this->adminResponse = '';
        $this->refreshSelectedTicket();
        session()->flash('success', 'Réponse envoyée avec succès.');
    }

    public function changeStatus($ticketId, $status)
    {
        $allowed = ['open', 'in_progress', 'resolved', 'closed'];
        if (! in_array($status, $allowed, true)) {
            return;
        }

        $ticket = SupportTicket::findOrFail($ticketId);
        $oldStatus = $ticket->status;

        $ticket->update([
            'status' => $status,
            'assigned_to' => $ticket->assigned_to ?? Auth::id(),
        ]);

        AdminActivityLog::log(
            'support_ticket_status_changed',
            "Ticket #{$ticket->id} status changed from {$oldStatus} to {$status}",
            SupportTicket::class,
            $ticket->id,
            ['old_status' => $oldStatus, 'new_status' => $status]
        );

        if ($this->selectedTicketId === $ticket->id) {
            $this->refreshSelectedTicket();
        }

        session()->flash('success', 'Statut du ticket mis à jour.');
    }

    public function openDeleteModal($ticketId)
    {
        $this->deleteTicketId = $ticketId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteTicketId = null;
    }

    public function deleteTicket()
    {
        $ticket = SupportTicket::findOrFail($this->deleteTicketId);
        $ticketId = $ticket->id;
        $ticket->delete();

        AdminActivityLog::log(
            'support_ticket_deleted',
            "Deleted support ticket #{$ticketId}",
            SupportTicket::class,
            $ticketId
        );

        if ($this->selectedTicketId === $ticketId) {
            $this->closeTicketModal();
        }

        $this->closeDeleteModal();
        session()->flash('success', 'Ticket supprimé avec succès.');
    }

    protected function refreshSelectedTicket(): void
    {
        if (! $this->selectedTicketId) {
            return;
        }

        $ticket = SupportTicket::with(['candidat', 'assignedAdmin'])->find($this->selectedTicketId);

        if (! $ticket) {
            $this->closeTicketModal();

            return;
        }

        $this->selectedTicket = $ticket;
        $this->newStatus = $ticket->status;
    }

    public function render()
    {
        $base = SupportTicket::query()
            ->when($this->tab === 'candidats', fn ($q) => $q->whereNotNull('candidat_id'))
            ->when($this->tab === 'admin', fn ($q) => $q->whereNull('candidat_id'));

        $query = (clone $base)->with(['candidat', 'assignedAdmin'])->latest();

        if ($this->search !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%')
                    ->orWhereHas('candidat', function ($candidateQuery) use ($search) {
                        $candidateQuery->where('nom', 'like', '%' . $search . '%')
                            ->orWhere('prenom', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('assignedAdmin', function ($adminQuery) use ($search) {
                        $adminQuery->where('nom', 'like', '%' . $search . '%')
                            ->orWhere('prenom', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->priorityFilter !== 'all') {
            $query->where('priority', $this->priorityFilter);
        }

        if ($this->categoryFilter !== 'all') {
            $query->where('category', $this->categoryFilter);
        }

        if ($this->assignedFilter === 'unassigned') {
            $query->whereNull('assigned_to');
        } elseif ($this->assignedFilter !== 'all') {
            $query->where('assigned_to', $this->assignedFilter);
        }

        $tickets = $query->paginate(15);

        $statistics = [
            'total' => (clone $base)->count(),
            'open' => (clone $base)->where('status', 'open')->count(),
            'in_progress' => (clone $base)->where('status', 'in_progress')->count(),
            'resolved' => (clone $base)->where('status', 'resolved')->count(),
        ];

        $candidateCount = SupportTicket::whereNotNull('candidat_id')->count();
        $adminCount = SupportTicket::whereNull('candidat_id')->count();

        $admins = User::whereIn('role', ['admin', 'super_admin'])
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get(['id', 'nom', 'prenom']);

        return view('livewire.admin.support.tickets', [
            'tickets' => $tickets,
            'statistics' => $statistics,
            'candidateCount' => $candidateCount,
            'adminCount' => $adminCount,
            'admins' => $admins,
        ])->layout('layouts.admin', ['header' => 'Support - Tickets']);
    }
}
