<?php

namespace App\Livewire\Admin\Contact;

use App\Models\Contact;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class ContactManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public bool $showModal = false;
    public ?Contact $selected = null;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openDetail(int $id): void
    {
        $this->selected = Contact::findOrFail($id);

        if (!$this->selected->is_read) {
            $this->selected->markAsRead();
            AdminActivityLog::log('contact_read', "Marked contact #{$id} as read", Contact::class, $id);
        }

        $this->showModal = true;
    }

    public function markRead(int $id): void
    {
        $contact = Contact::findOrFail($id);
        $contact->markAsRead();
        AdminActivityLog::log('contact_read', "Marked contact #{$id} as read", Contact::class, $id);
        session()->flash('success', 'Message marqué comme lu.');
    }

    public function delete(int $id): void
    {
        $contact = Contact::findOrFail($id);
        AdminActivityLog::log('contact_deleted', "Deleted contact #{$id} from {$contact->email}", Contact::class, $id);
        $contact->delete();
        $this->showModal = false;
        session()->flash('success', 'Message supprimé avec succès!');
    }

    public function render()
    {
        $query = Contact::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('subject', 'like', "%{$this->search}%")
                  ->orWhere('message', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter === 'unread') {
            $query->where('is_read', false);
        } elseif ($this->statusFilter === 'read') {
            $query->where('is_read', true);
        }

        $contacts = $query->latest()->paginate(15);

        $stats_card = [
            [
                'label' => 'TOTAL MESSAGES',
                'icon'  => 'ri-mail-line',
                'data'  => Contact::count(),
            ],
            [
                'label' => 'NON LUS',
                'icon'  => 'ri-mail-unread-line',
                'data'  => Contact::where('is_read', false)->count(),
            ],
            [
                'label' => 'LUS',
                'icon'  => 'ri-mail-check-line',
                'data'  => Contact::where('is_read', true)->count(),
            ],
        ];

        return view('livewire.admin.contact.contact-management', [
            'contacts'   => $contacts,
            'stats_card' => $stats_card,
        ])->layout('layouts.admin', ['header' => 'Messages Contact']);
    }
}
