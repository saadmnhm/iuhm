<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\DynamicFormSubmission;
use App\Models\DynamicForm;
use App\Models\ProgrameList;
use App\Models\User;
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

        $submissions = $query->latest()->paginate(15);

        // Filter options
        $programmes = ProgrameList::orderBy('project_name')->get(['id', 'project_name']);
        $formulaires = DynamicForm::orderBy('title')->get(['id', 'title']);
        $admins = User::whereIn('role', ['admin', 'super_admin'])->orderBy('name')->get(['id', 'name']);
        $addresses = \App\Models\Candidat::whereNotNull('address')
            ->select('address')->distinct()->orderBy('address')->pluck('address');

        // Stats
        $stats = [
            'total'     => DynamicFormSubmission::count(),
            'submitted' => DynamicFormSubmission::where('status', 'submitted')->count(),
            'approved'  => DynamicFormSubmission::where('status', 'approved')->count(),
            'rejected'  => DynamicFormSubmission::where('status', 'rejected')->count(),
            'in_review' => DynamicFormSubmission::where('status', 'in_review')->count(),
            'draft'     => DynamicFormSubmission::where('status', 'draft')->count(),
        ];

        return view('livewire.admin.submissions.all-submissions', compact(
            'submissions', 'programmes', 'formulaires', 'admins', 'addresses', 'stats'
        ))->layout('layouts.admin', ['header' => 'Toutes les Soumissions']);
    }
}
