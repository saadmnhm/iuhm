<?php

namespace App\Livewire\Admin;

use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogs extends Component
{
    use WithPagination;

    public $search = '';
    public $actionFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';
    
    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingActionFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = AdminActivityLog::with('user')->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('action', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->actionFilter !== 'all') {
            $query->where('action', $this->actionFilter);
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $logs = $query->paginate(20);
        
        $actions = AdminActivityLog::select('action')
            ->groupBy('action')
            ->pluck('action');

        return view('livewire.admin.activity-logs', [
            'logs' => $logs,
            'actions' => $actions,
        ])->layout('layouts.admin', ['header' => 'Activity Logs']);
    }
}
