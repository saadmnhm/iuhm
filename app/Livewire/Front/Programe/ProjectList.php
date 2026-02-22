<?php

namespace App\Livewire\Front\Programe;

use Livewire\Component;
use App\Models\ProgrameList;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ProjectList extends Component
{
    use WithPagination;
    
    public $search = '';
    
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
        
        // Check age eligibility for candidat
        if (Auth::guard('candidat')->check()) {
            $candidat = Auth::guard('candidat')->user();
            $userAge = $candidat->age ?? 0;
            if ($userAge > 0) {
                $query->where(function($q) use ($userAge) {
                    $q->where('min_age', '<=', $userAge)
                      ->where('max_age', '>=', $userAge);
                });
            }
        }
        
        $projects = $query->paginate(12);
        
        return view('livewire.front.programe.project-list', [
            'projects' => $projects,
        ])->layout('layouts.app', ['title' => 'Available Projects']);
    }
}
