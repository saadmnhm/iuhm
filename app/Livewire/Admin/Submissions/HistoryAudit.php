<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\SubmissionHistory;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class HistoryAudit extends Component
{
    use WithPagination;

    public $search = '';
    public $actionFilter = 'all';
    public $tab = 'history'; // history | activity

    protected $paginationTheme = 'tailwind';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingActionFilter() { $this->resetPage(); }
    public function updatingTab() { $this->resetPage(); }

    public function render()
    {
        if ($this->tab === 'history') {
            $query = SubmissionHistory::with('changedByUser')->latest();

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('action', 'like', "%{$this->search}%")
                      ->orWhere('old_value', 'like', "%{$this->search}%")
                      ->orWhere('new_value', 'like', "%{$this->search}%")
                      ->orWhere('notes', 'like', "%{$this->search}%");
                });
            }

            if ($this->actionFilter !== 'all') {
                $query->where('action', $this->actionFilter);
            }

            $items = $query->paginate(20);
            $actions = SubmissionHistory::select('action')->distinct()->pluck('action');
        } else {
            $query = AdminActivityLog::with('user')->latest();

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('action', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
                });
            }

            if ($this->actionFilter !== 'all') {
                $query->where('action', $this->actionFilter);
            }

            $items = $query->paginate(20);
            $actions = AdminActivityLog::select('action')->distinct()->pluck('action');
        }

        return view('livewire.admin.submissions.history-audit', compact('items', 'actions'))
            ->layout('layouts.admin', ['header' => 'Historique & Audit']);
    }
}
