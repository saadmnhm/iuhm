<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\Candidat;
use App\Models\DynamicFormSubmission;
use Livewire\Component;
use Livewire\WithPagination;

class MyAssignedSubmissions extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $tab = 'candidats'; // candidats | submissions

    protected $paginationTheme = 'tailwind';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingTab() { $this->resetPage(); }

    public function render()
    {
        $adminId = auth()->id();

        // Statistics
        $stats = [
            'candidats_assigned' => Candidat::where('reviewed_by', $adminId)->count(),
            'candidats_approved' => Candidat::where('reviewed_by', $adminId)->where('review_status', 'approved')->count(),
            'candidats_rejected' => Candidat::where('reviewed_by', $adminId)->where('review_status', 'rejected')->count(),
            'candidats_in_review' => Candidat::where('reviewed_by', $adminId)->where('review_status', 'in_review')->count(),
            'submissions_total'  => DynamicFormSubmission::where('reviewed_by', $adminId)->count(),
            'submissions_approved' => DynamicFormSubmission::where('reviewed_by', $adminId)->where('status', 'approved')->count(),
            'submissions_rejected' => DynamicFormSubmission::where('reviewed_by', $adminId)->where('status', 'rejected')->count(),
        ];

        if ($this->tab === 'candidats') {
            $query = Candidat::where('reviewed_by', $adminId)->with('reviewer');

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
        } else {
            $query = DynamicFormSubmission::where('reviewed_by', $adminId)
                ->with(['candidat', 'form', 'programe', 'reviewer']);

            if ($this->search) {
                $query->whereHas('candidat', function ($q) {
                    $q->where('nom', 'like', "%{$this->search}%")
                      ->orWhere('prenom', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
                });
            }

            if ($this->statusFilter !== 'all') {
                $query->where('status', $this->statusFilter);
            }

            $items = $query->latest('reviewed_at')->paginate(12);
        }

        return view('livewire.admin.submissions.my-assigned-submissions', compact('stats', 'items'))
            ->layout('layouts.admin', ['header' => 'Mes Assignations']);
    }
}
