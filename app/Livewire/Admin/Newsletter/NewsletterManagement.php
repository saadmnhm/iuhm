<?php

namespace App\Livewire\Admin\Newsletter;

use App\Models\Newsletter;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class NewsletterManagement extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $statusFilter = 'all';
    public bool   $showModal   = false;
    public bool   $editMode    = false;
    public ?int   $newsletterId = null;

    public string $email    = '';
    public bool   $isActive = true;

    protected $paginationTheme = 'tailwind';



    public function updatingSearch(): void
    {
        $this->resetPage();
    }



    public function toggleActive(int $id): void
    {
        $subscriber = Newsletter::findOrFail($id);
        $subscriber->update(['is_active' => ! $subscriber->is_active]);
        $label = $subscriber->is_active ? 'activ�' : 'd�sactiv�';
        AdminActivityLog::log('subscriber_toggled', "Subscriber {$subscriber->email} ? {$label}", Newsletter::class, $subscriber->id);
    }

    public function delete(int $id): void
    {
        $subscriber = Newsletter::findOrFail($id);
        AdminActivityLog::log('subscriber_deleted', "Deleted subscriber {$subscriber->email}", Newsletter::class, $subscriber->id);
        $subscriber->delete();
        session()->flash('success', 'Abonn� supprim� avec succ�s !');
    }


    public function render()
    {
        $query = Newsletter::query();

        if ($this->search) {
            $query->where('email', 'like', "%{$this->search}%");
        }

        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $newsletters = $query->latest()->paginate(15);

        $stats_card = [
            [
                'label' => 'Total abonnés',
                'icon'  => 'ri-group-line',
                'data'  => Newsletter::count(),
            ],
            [
                'label' => 'Abonnés actifs',
                'icon'  => 'ri-checkbox-circle-line',
                'data'  => Newsletter::where('is_active', true)->count(),
            ],
            [
                'label' => 'Désabonnés',
                'icon'  => 'ri-mail-close-line',
                'data'  => Newsletter::where('is_active', false)->count(),
            ],
        ];

        return view('livewire.admin.newsletter.newsletter-management', [
            'newsletters' => $newsletters,
            'stats_card'  => $stats_card,
        ])->layout('layouts.admin', ['header' => 'Newsletter Management']);
    }
}
