<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\DynamicFormSubmission;
use App\Models\DynamicForm;
use App\Models\ProgrameList;
use App\Models\User;
use App\Models\Candidat;
use Livewire\Component;
use Livewire\WithPagination;

class AllSubmissions extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $programeFilter = 'all';
    public $formulaireFilter = 'all';
    public $responsableFilter = 'all';
    public $genderFilter = 'all';
    public $addressFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingProgrameFilter() { $this->resetPage(); }
    public function updatingFormulaireFilter() { $this->resetPage(); }
    public function updatingResponsableFilter() { $this->resetPage(); }
    public function updatingGenderFilter() { $this->resetPage(); }
    public function updatingAddressFilter() { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'programeFilter', 'formulaireFilter', 'responsableFilter', 'genderFilter', 'addressFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    /** Click a stat card to toggle/set the status filter */
    public function filterByStatus(string $status): void
    {
        $this->statusFilter = ($this->statusFilter === $status && $status !== 'all') ? 'all' : $status;
        $this->resetPage();
    }

    /** Clear a single active filter chip */
    public function clearFilter(string $field): void
    {
        $defaults = [
            'search'            => '',
            'statusFilter'      => 'all',
            'programeFilter'    => 'all',
            'formulaireFilter'  => 'all',
            'responsableFilter' => 'all',
            'genderFilter'      => 'all',
            'addressFilter'     => 'all',
            'dateFrom'          => '',
            'dateTo'            => '',
        ];
        if (array_key_exists($field, $defaults)) {
            $this->$field = $defaults[$field];
            $this->resetPage();
        }
    }

    /** Inline status change from the table row */
    public function updateStatus(int $id, string $newStatus): void
    {
        $allowed = ['draft', 'submitted', 'in_review', 'approved', 'rejected'];
        if (!in_array($newStatus, $allowed)) return;
        DynamicFormSubmission::findOrFail($id)->update(['status' => $newStatus]);
        session()->flash('toast', 'Statut mis à jour avec succès.');
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Statut mis à jour avec succès.']);
    }

    /** Assign or unassign a reviewer inline */
    public function assignResponsable(int $id, ?int $adminId): void
    {
        DynamicFormSubmission::findOrFail($id)->update(['reviewed_by' => $adminId]);
        $msg = $adminId ? 'Responsable assigné.' : 'Responsable retiré.';
        session()->flash('toast', $msg);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => $msg]);
    }

    public function render()
    {
        $query = DynamicFormSubmission::with(['candidat', 'form', 'programe', 'reviewer']);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('candidat', function ($c) {
                    $c->where('nom', 'like', "%{$this->search}%")
                      ->orWhere('prenom', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('matricule', 'like', "%{$this->search}%");
                })->orWhereHas('form', function ($f) {
                    $f->where('title', 'like', "%{$this->search}%");
                });
            });
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Programme filter
        if ($this->programeFilter !== 'all') {
            $query->where('programe_id', $this->programeFilter);
        }

        // Formulaire filter
        if ($this->formulaireFilter !== 'all') {
            $query->where('dynamic_form_id', $this->formulaireFilter);
        }

        // Responsable filter
        if ($this->responsableFilter !== 'all') {
            if ($this->responsableFilter === 'none') {
                $query->whereNull('reviewed_by');
            } else {
                $query->where('reviewed_by', $this->responsableFilter);
            }
        }

        // Gender filter
        if ($this->genderFilter !== 'all') {
            $query->whereHas('candidat', function ($q) {
                $q->where('gender', $this->genderFilter);
            });
        }

        // Address filter
        if ($this->addressFilter !== 'all') {
            $query->whereHas('candidat', function ($q) {
                $q->where('address', $this->addressFilter);
            });
        }

        // Date range
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $submissions = $query->latest()->where('status', 'submitted')->paginate(15);

        $programmes  = ProgrameList::orderBy('project_name')->get(['id', 'project_name']);
        $formulaires = DynamicForm::orderBy('title')->get(['id', 'title']);
        $admins      = User::whereIn('role', ['admin', 'super_admin'])->orderBy('name')->get(['id', 'name']);
        $addresses   = Candidat::whereNotNull('address')
                           ->select('address')->distinct()->orderBy('address')->pluck('address');

        $weekStart = now()->startOfWeek();
        $stats = [
            'total'          => DynamicFormSubmission::count(),
            'draft'          => DynamicFormSubmission::where('status', 'draft')->count(),
            'submitted'      => DynamicFormSubmission::where('status', 'submitted')->count(),
            'in_review'      => DynamicFormSubmission::where('status', 'in_review')->count(),
            'approved'       => DynamicFormSubmission::where('status', 'approved')->count(),
            'rejected'       => DynamicFormSubmission::where('status', 'rejected')->count(),
            'submitted_week' => DynamicFormSubmission::where('status', 'submitted')->where('created_at', '>=', $weekStart)->count(),
            'approved_week'  => DynamicFormSubmission::where('status', 'approved')->where('updated_at', '>=', $weekStart)->count(),
            'rejected_week'  => DynamicFormSubmission::where('status', 'rejected')->where('updated_at', '>=', $weekStart)->count(),
            'in_review_week' => DynamicFormSubmission::where('status', 'in_review')->where('updated_at', '>=', $weekStart)->count(),
        ];

        return view('livewire.admin.submissions.all-submissions', compact(
            'submissions', 'programmes', 'formulaires', 'admins', 'addresses', 'stats'
        ))->layout('layouts.admin', ['header' => 'Toutes les Soumissions']);
    }
}
