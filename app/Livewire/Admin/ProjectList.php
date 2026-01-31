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
    
    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Project::with('user')->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('project_title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $projects = $query->paginate(15);

        $statistics = [
            'total' => Project::count(),
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
