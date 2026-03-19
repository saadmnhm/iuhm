<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\DynamicFormSubmission;
use App\Models\DynamicForm;
use App\Models\ProgrameList;
use App\Models\ProjectSubmission;
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
    public $tab = 'formulaire';

    protected $paginationTheme = 'tailwind';

    protected function resetAllPages(): void
    {
        $this->resetPage();
        $this->resetPage('projectPage');
    }

    public function updatingSearch() { $this->resetAllPages(); }
    public function updatingStatusFilter() { $this->resetAllPages(); }
    public function updatingProgrameFilter() { $this->resetAllPages(); }
    public function updatingFormulaireFilter() { $this->resetAllPages(); }
    public function updatingResponsableFilter() { $this->resetAllPages(); }
    public function updatingGenderFilter() { $this->resetAllPages(); }
    public function updatingAddressFilter() { $this->resetAllPages(); }
    public function updatingDateFrom() { $this->resetAllPages(); }
    public function updatingDateTo() { $this->resetAllPages(); }

    public function updatingTab($value): void
    {
        if (!in_array($value, ['formulaire', 'project'], true)) {
            $this->tab = 'formulaire';
        }
        $this->resetAllPages();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'programeFilter', 'formulaireFilter', 'responsableFilter', 'genderFilter', 'addressFilter', 'dateFrom', 'dateTo']);
        $this->resetAllPages();
    }

    /** Click a stat card to toggle/set the status filter */
    public function filterByStatus(string $status): void
    {
        $this->statusFilter = ($this->statusFilter === $status && $status !== 'all') ? 'all' : $status;
        $this->resetAllPages();
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
            $this->resetAllPages();
        }
    }

    /** Inline status change from the table row */
    public function updateStatus(int $id, string $newStatus): void
    {
        $allowed = ['draft', 'submitted', 'in_review', 'approved', 'rejected'];
        if (!in_array($newStatus, $allowed)) return;
        DynamicFormSubmission::findOrFail($id)->update(['status' => $newStatus]);
        session()->flash('toast', 'Statut mis à jour avec succès.');
        $this->dispatch('notify', type: 'success', message: 'Statut mis à jour avec succès.');
    }

    /** Assign or unassign a reviewer inline */
    public function assignResponsable(int $id, ?int $adminId): void
    {
        DynamicFormSubmission::findOrFail($id)->update(['reviewed_by' => $adminId]);
        $msg = $adminId ? 'Responsable assigné.' : 'Responsable retiré.';
        session()->flash('toast', $msg);
        $this->dispatch('notify', type: 'success', message: $msg);
    }

    public function render()
    {
        $query = DynamicFormSubmission::with(['candidat', 'form', 'programe', 'reviewer', 'projectSubmission.reviewer']);

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

        $submissions = $query->latest()->where('is_submitted', true)->paginate(15);

        $projectQuery = ProjectSubmission::with(['candidat', 'project', 'reviewer']);

        if ($this->search) {
            $projectQuery->where(function ($q) {
                $q->whereHas('candidat', function ($c) {
                    $c->where('nom', 'like', "%{$this->search}%")
                      ->orWhere('prenom', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('matricule', 'like', "%{$this->search}%");
                })->orWhereHas('project', function ($p) {
                    $p->where('project_name', 'like', "%{$this->search}%");
                });
            });
        }

        if ($this->statusFilter !== 'all') {
            $projectStatusMap = [
                'submitted' => 'pending',
                'in_review' => 'in_review',
                'approved' => 'approved',
                'rejected' => 'rejected',
            ];

            if (isset($projectStatusMap[$this->statusFilter])) {
                $projectQuery->where('review_status', $projectStatusMap[$this->statusFilter]);
            }
        }

        if ($this->programeFilter !== 'all') {
            $projectQuery->where('programe_id', $this->programeFilter);
        }

        if ($this->responsableFilter !== 'all') {
            if ($this->responsableFilter === 'none') {
                $projectQuery->whereNull('reviewed_by');
            } else {
                $projectQuery->where('reviewed_by', $this->responsableFilter);
            }
        }

        if ($this->genderFilter !== 'all') {
            $projectQuery->whereHas('candidat', function ($q) {
                $q->where('gender', $this->genderFilter);
            });
        }

        if ($this->addressFilter !== 'all') {
            $projectQuery->whereHas('candidat', function ($q) {
                $q->where('address', $this->addressFilter);
            });
        }

        if ($this->dateFrom) {
            $projectQuery->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $projectQuery->whereDate('created_at', '<=', $this->dateTo);
        }

        $projectSubmissions = $projectQuery->latest()->paginate(15, ['*'], 'projectPage');

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
            'submissions', 'projectSubmissions', 'programmes', 'formulaires', 'admins', 'addresses', 'stats'
        ))->layout('layouts.admin', ['header' => 'Toutes les Soumissions']);
    }
}
