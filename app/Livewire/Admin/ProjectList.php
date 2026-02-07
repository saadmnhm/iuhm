<?php

namespace App\Livewire\Admin;

use App\Models\BusinessPlan;
use Livewire\Component;
use App\Models\Candidat;
use Livewire\WithPagination;

class ProjectList extends Component
{

    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    
    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Only show submitted projects and beyond (exclude drafts)
        $query = BusinessPlan::with(['candidat', 'reviewer'])
            ->whereNotIn('status', ['draft'])
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

        $projects = $query->paginate(15);

        $statistics = [
            'total' => BusinessPlan::whereIn('status', ['submitted', 'in_review', 'approved', 'rejected'])->count(),
            'draft' => BusinessPlan::where('status', 'draft')->count(),
            'submitted' => BusinessPlan::where('status', 'submitted')->count(),
            'in_review' => BusinessPlan::where('status', 'in_review')->count(),
            'approved' => BusinessPlan::where('status', 'approved')->count(),
            'rejected' => BusinessPlan::where('status', 'rejected')->count(),
            'male' => Candidat::where('gender', 'homme')->count(),
            'female' => Candidat::where('gender', 'femme')->count(),
        ];

        return view('livewire.admin.formulaire.project-list', [
            'projects' => $projects,
            'statistics' => $statistics,
        ])->layout('layouts.admin', ['header' => 'Projects Management']);
    }
        public function updateRegistration(Request $request, $id)
    {
        $request->validate([
            'registration' => 'required|string|max:255'
        ]);

        $project = BusinessPlan::findOrFail($id);

        if ($project->status === 'draft') {
            return redirect()->back()->with('error', 'Cannot update registration for draft projects.');
        }

        $project->update(['registration' => $request->registration]);

        return redirect()->back()->with('success', 'Project registration updated successfully!');
    }
}
