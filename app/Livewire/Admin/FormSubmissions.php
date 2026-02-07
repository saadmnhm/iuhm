<?php

namespace App\Livewire\Admin;

use App\Models\BusinessPlan;
use App\Models\Candidat;
use App\Models\SupportTicket;
use Livewire\Component;
use Livewire\WithPagination;

class FormSubmissions extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $formTypeFilter = 'all';
    
    protected $paginationTheme = 'tailwind';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingFormTypeFilter() { $this->resetPage(); }

    public function render()
    {
        $query = BusinessPlan::with(['candidat'])
            ->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('project_name', 'like', '%' . $this->search . '%')
                  ->orWhere('registration', 'like', '%' . $this->search . '%')
                  ->orWhereHas('candidat', function($q) {
                      $q->where('nom', 'like', '%' . $this->search . '%')
                        ->orWhere('prenom', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->formTypeFilter !== 'all') {
            $query->where('form_type', $this->formTypeFilter);
        }

        $projects = $query->paginate(15);

        $statistics = [
            'total' => BusinessPlan::count(),
            'draft' => BusinessPlan::where('status', 'draft')->count(),
            'submitted' => BusinessPlan::where('status', 'submitted')->count(),
            'approved' => BusinessPlan::where('status', 'approved')->count(),
            'rejected' => BusinessPlan::where('status', 'rejected')->count(),
            'business_plan' => BusinessPlan::where('form_type', 'business_plan')->whereNotIn('status', ['draft'])->count(),
            'etude_marche' => BusinessPlan::where('form_type', 'etude_marche')->whereNotIn('status', ['draft'])->count(),
            'evaluation_idee' => BusinessPlan::where('form_type', 'evaluation_idee')->whereNotIn('status', ['draft'])->count(),
            'bmc' => BusinessPlan::where('form_type', 'bmc')->whereNotIn('status', ['draft'])->count(),
            'bilan_competence' => BusinessPlan::where('form_type', 'bilan_competence')->whereNotIn('status', ['draft'])->count(),
        ];

        $formTypes = BusinessPlan::formTypes();

        return view('livewire.admin.formulaire.form-submissions', [
            'projects' => $projects,
            'statistics' => $statistics,
            'formTypes' => $formTypes,
        ])->layout('layouts.admin', ['header' => 'Gestion des Formulaires']);
    }
}
