<?php

namespace App\Livewire\Admin;

use App\Models\Project;
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
        $query = Project::with(['candidat', 'reviewer'])->latest();

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
            'total' => Project::count(),
            'draft' => Project::where('status', 'draft')->count(),
            'submitted' => Project::where('status', 'submitted')->count(),
            'in_review' => Project::where('status', 'in_review')->count(),
            'approved' => Project::where('status', 'approved')->count(),
            'rejected' => Project::where('status', 'rejected')->count(),
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

        $project = Project::findOrFail($id);
        $project->update(['registration' => $request->registration]);

        return redirect()->back()->with('success', 'Project registration updated successfully!');
    }
}
