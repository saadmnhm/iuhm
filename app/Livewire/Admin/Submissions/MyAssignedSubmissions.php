<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\Candidat;
use App\Models\ProjectSubmission;
use App\Models\DynamicFormSubmission;
use Livewire\Component;
use Livewire\WithPagination;

class MyAssignedSubmissions extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $tab = 'candidats'; 

    protected $paginationTheme = 'tailwind';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingTab() { $this->resetPage(); }

    public function render()
    {
        $adminId = auth()->id();

        // Statistics
        $stats = [
            'candidats_assigned' => ProjectSubmission::where('reviewed_by', $adminId)->count(),
            'candidats_approved' => ProjectSubmission::where('reviewed_by', $adminId)->where('review_status', 'approved')->count(),
            'candidats_rejected' => ProjectSubmission::where('reviewed_by', $adminId)->where('review_status', 'rejected')->count(),
            'candidats_in_review' => ProjectSubmission::where('reviewed_by', $adminId)->where('review_status', 'in_review')->count(),
        ];

        if ($this->tab === 'candidats') {
            $query = ProjectSubmission::where('reviewed_by', $adminId)->with('reviewer');

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('nom', 'like', "%{$this->search}%")
                      ->orWhere('prenom', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('matricule', 'like', "%{$this->search}%");
                });
            }

            if ($this->statusFilter !== 'all') {
                $query->where('review_status', $this->statusFilter);
            }

            $items = $query->latest('reviewed_at')->paginate(12);
        }

        return view('livewire.admin.submissions.my-assigned-submissions', compact('stats', 'items'))
            ->layout('layouts.admin', ['header' => 'Mes Assignations']);
    }
}
