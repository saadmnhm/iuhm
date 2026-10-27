<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\DynamicFormSubmission;
use App\Models\DynamicForm;
use App\Models\ProjectsList;
use App\Models\ProjectsSubmission;
use App\Models\User;
use App\Models\Candidat;
use Illuminate\Database\Eloquent\Builder;
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
        $submissions = $this->formulaireQuery()->paginate(15);
        $ProjectsSubmissions = $this->projectQuery()->paginate(15, ['*'], 'projectPage');

        $programmes  = ProjectsList::orderBy('project_name')->get(['id', 'project_name']);
        $formulaires = DynamicForm::orderBy('title')->get(['id', 'title']);
        $admins      = User::whereIn('role', ['admin', 'super_admin'])->orderBy('nom')->get(['id', 'nom', 'prenom']);
        $addresses   = Candidat::whereNotNull('address')
                           ->select('address')->distinct()->orderBy('address')->pluck('address');

        $weekStart = now()->startOfWeek();
        $stats = [
            'total'          => DynamicFormSubmission::where('is_submitted', true)->count(),
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
            $statCards = [
                ['key' => 'all',       'label' => 'TOTAL SOUMISSIONS',   'value' => $stats['total'],     'icon' => 'ri-file-list-3-line',     'dot' => 'bg-blue-500'],
                ['key' => 'submitted', 'label' => 'SOUMISES',            'value' => $stats['submitted'], 'icon' => 'ri-send-plane-line',      'dot' => 'bg-indigo-500'],
                ['key' => 'in_review', 'label' => 'EN RÉVISION',         'value' => $stats['in_review'], 'icon' => 'ri-time-line',            'dot' => 'bg-amber-500'],
                ['key' => 'approved',  'label' => 'SOUMISSIONS APPROUVÉ','value' => $stats['approved'],  'icon' => 'ri-checkbox-circle-line', 'dot' => 'bg-green-500'],
                ['key' => 'rejected',  'label' => 'REJETÉES',            'value' => $stats['rejected'],  'icon' => 'ri-close-circle-line',    'dot' => 'bg-red-500'],
            ];

        return view('livewire.admin.submissions.all-submissions', compact(
            'submissions', 'ProjectsSubmissions', 'programmes', 'formulaires', 'admins', 'addresses', 'stats', 'statCards'
        ))->layout('layouts.admin', ['header' => 'Toutes les Soumissions']);
    }

    protected function formulaireQuery(): Builder
    {
        $query = DynamicFormSubmission::with(['candidat', 'form', 'programe', 'reviewer', 'ProjectsSubmission.reviewer'])
            ->where('is_submitted', true);

        if ($this->search) {
            $search = $this->search;

            $query->where(function (Builder $q) use ($search) {
                $q->whereHas('candidat', function (Builder $c) use ($search) {
                    $c->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('matricule', 'like', "%{$search}%");
                })->orWhereHas('programe', function (Builder $p) use ($search) {
                    $p->where('project_name', 'like', "%{$search}%");
                })->orWhereHas('form', function (Builder $f) use ($search) {
                    $f->where('title', 'like', "%{$search}%");
                });
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->programeFilter !== 'all') {
            $query->where('programe_id', $this->programeFilter);
        }

        if ($this->formulaireFilter !== 'all') {
            $query->where('dynamic_form_id', $this->formulaireFilter);
        }

        if ($this->responsableFilter !== 'all') {
            if ($this->responsableFilter === 'none') {
                $query->whereNull('reviewed_by');
            } else {
                $query->where('reviewed_by', $this->responsableFilter);
            }
        }

        $this->applyCommonFilters($query);

        return $query->latest();
    }

    protected function projectQuery(): Builder
    {
        $query = ProjectsSubmission::with(['candidat', 'project', 'reviewer'])
            ->where('is_finished', true);

        if ($this->search) {
            $search = $this->search;

            $query->where(function (Builder $q) use ($search) {
                $q->whereHas('candidat', function (Builder $c) use ($search) {
                    $c->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('matricule', 'like', "%{$search}%");
                })->orWhereHas('project', function (Builder $p) use ($search) {
                    $p->where('project_name', 'like', "%{$search}%");
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
                $query->where('review_status', $projectStatusMap[$this->statusFilter]);
            }
        }

        if ($this->programeFilter !== 'all') {
            $query->where('programe_id', $this->programeFilter);
        }

        if ($this->formulaireFilter !== 'all') {
            $query->whereIn('id', DynamicFormSubmission::query()
                ->where('dynamic_form_id', $this->formulaireFilter)
                ->whereNotNull('project_submission_id')
                ->select('project_submission_id'));
        }

        if ($this->responsableFilter !== 'all') {
            if ($this->responsableFilter === 'none') {
                $query->whereNull('reviewed_by');
            } else {
                $query->where('reviewed_by', $this->responsableFilter);
            }
        }

        $this->applyCommonFilters($query);

        return $query->latest();
    }

    protected function applyCommonFilters(Builder $query): void
    {
        if ($this->genderFilter !== 'all') {
            $query->whereHas('candidat', function (Builder $q) {
                $q->where('gender', $this->genderFilter);
            });
        }

        if ($this->addressFilter !== 'all') {
            $query->whereHas('candidat', function (Builder $q) {
                $q->where('address', $this->addressFilter);
            });
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }
    }
}
