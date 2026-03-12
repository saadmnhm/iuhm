<?php

namespace App\Livewire\Front\Programe;

use Livewire\Component;
use App\Models\ProgrameList;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Services\ProjectEligibilityService;

class ProjectList extends Component
{
    use WithPagination;
    
    public $search = '';

    protected ProjectEligibilityService $eligibilityService;

    public function boot(ProjectEligibilityService $eligibilityService): void
    {
        $this->eligibilityService = $eligibilityService;
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $query = ProgrameList::with('formulaires')
            ->where('is_active', true);
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('project_name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        
        $projects = $query->paginate(12);
        $eligibilityMap = [];

        if (Auth::guard('candidat')->check()) {
            $candidat = Auth::guard('candidat')->user();
            foreach ($projects as $project) {
                $check = $this->eligibilityService->evaluate($candidat, $project);
                $eligibilityMap[$project->id] = $check;
            }
        }
        
        return view('livewire.front.programe.project-list', [
            'projects' => $projects,
            'eligibilityMap' => $eligibilityMap,
        ])->layout('layouts.app', ['title' => 'Available Projects']);
    }
}
