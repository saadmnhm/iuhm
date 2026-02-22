<?php

namespace App\Livewire\Admin\Programe;

use Livewire\Component;
use App\Models\ProgrameList;
use App\Models\DynamicFormSubmission;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ProjectSubmissions extends Component
{
    use WithPagination;
    
    public $projectId;
    public $project;
    public $statistics = [];
    public $filterStatus = 'all';
    public $filterFormulaire = 'all';
    public $search = '';
    
    public function mount($id)
    {
        $this->projectId = $id;
        $this->loadProject();
        $this->calculateStatistics();
    }
    
    public function loadProject()
    {
        $this->project = ProgrameList::with('formulaires')->findOrFail($this->projectId);
    }
    
    public function calculateStatistics()
    {
        // Total submissions for this project
        $totalSubmissions = DynamicFormSubmission::where('programe_id', $this->projectId)->count();
        
        // Completed submissions
        $completedSubmissions = DynamicFormSubmission::where('programe_id', $this->projectId)
            ->whereIn('status', ['submitted', 'in_review', 'approved'])
            ->count();
        
        // Draft submissions
        $draftSubmissions = DynamicFormSubmission::where('programe_id', $this->projectId)
            ->where('status', 'draft')
            ->count();
        
        // Unique users who submitted (candidats + users)
        $uniqueCandidats = DynamicFormSubmission::where('programe_id', $this->projectId)
            ->whereNotNull('candidat_id')
            ->distinct('candidat_id')
            ->count('candidat_id');
        
        $uniqueUsers = DynamicFormSubmission::where('programe_id', $this->projectId)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');
        
        $totalUniqueUsers = $uniqueCandidats + $uniqueUsers;
        
        // Submissions by formulaire
        $submissionsByFormulaire = [];
        foreach ($this->project->formulaires as $formulaire) {
            $count = DynamicFormSubmission::where('programe_id', $this->projectId)
                ->where('dynamic_form_id', $formulaire->id)
                ->count();
            
            $completed = DynamicFormSubmission::where('programe_id', $this->projectId)
                ->where('dynamic_form_id', $formulaire->id)
                ->whereIn('status', ['submitted', 'in_review', 'approved'])
                ->count();
            
            $submissionsByFormulaire[] = [
                'id' => $formulaire->id,
                'title' => $formulaire->title,
                'total' => $count,
                'completed' => $completed,
                'draft' => $count - $completed,
                'order' => $formulaire->pivot->order,
            ];
        }
        
        // Recent submissions (last 7 days)
        $recentSubmissions = DynamicFormSubmission::where('programe_id', $this->projectId)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        
        // Average completion time (in days)
        $avgCompletionTime = DynamicFormSubmission::where('programe_id', $this->projectId)
            ->whereIn('status', ['submitted', 'in_review', 'approved'])
            ->whereNotNull('submitted_at')
            ->selectRaw('AVG(DATEDIFF(submitted_at, created_at)) as avg_days')
            ->value('avg_days');
        
        $this->statistics = [
            'total' => $totalSubmissions,
            'completed' => $completedSubmissions,
            'draft' => $draftSubmissions,
            'unique_users' => $totalUniqueUsers,
            'recent' => $recentSubmissions,
            'avg_completion_days' => round($avgCompletionTime ?? 0, 1),
            'by_formulaire' => $submissionsByFormulaire,
            'completion_rate' => $totalSubmissions > 0 ? round(($completedSubmissions / $totalSubmissions) * 100, 1) : 0,
        ];
    }
    
    public function updatedFilterStatus()
    {
        $this->resetPage();
    }
    
    public function updatedFilterFormulaire()
    {
        $this->resetPage();
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function viewSubmission($submissionId)
    {
        // Navigate to candidat submissions page if candidat, otherwise just show flash
        $submission = DynamicFormSubmission::find($submissionId);
        if ($submission && $submission->candidat_id) {
            return redirect()->route('admin.candidat.submissions', $submission->candidat_id);
        }
        session()->flash('info', 'Submission details viewed.');
    }
    
    public function deleteSubmission($submissionId)
    {
        $submission = DynamicFormSubmission::findOrFail($submissionId);
        $submission->delete();
        
        $this->calculateStatistics();
        session()->flash('message', 'Submission deleted successfully.');
    }
    
    public function render()
    {
        // Get submissions grouped by candidat_id (primary) and user_id (fallback)
        $submissions = DynamicFormSubmission::where('programe_id', $this->projectId);
        
        // Apply status filter
        if ($this->filterStatus !== 'all') {
            if ($this->filterStatus === 'completed') {
                $submissions->whereIn('status', ['submitted', 'in_review', 'approved']);
            } else {
                $submissions->where('status', $this->filterStatus);
            }
        }
        
        // Apply formulaire filter
        if ($this->filterFormulaire !== 'all') {
            $submissions->where('dynamic_form_id', $this->filterFormulaire);
        }
        
        if ($this->search) {
            $submissions->where(function($q) {
                $q->whereHas('candidat', function($cq) {
                    $cq->where('nom', 'like', '%' . $this->search . '%')
                       ->orWhere('prenom', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                })->orWhereHas('user', function($uq) {
                    $uq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            });
        }
        
        $allSubmissions = $submissions->get();
        
        // Group by user (candidat or user)
        $grouped = [];
        foreach ($allSubmissions as $sub) {
            // Determine unique key: prefer candidat_id
            if ($sub->candidat_id) {
                $key = 'candidat_' . $sub->candidat_id;
            } elseif ($sub->user_id) {
                $key = 'user_' . $sub->user_id;
            } else {
                continue;
            }
            
            if (!isset($grouped[$key])) {
                $person = null;
                $personId = null;
                if ($sub->candidat_id) {
                    $person = \App\Models\Candidat::find($sub->candidat_id);
                    $personId = $sub->candidat_id;
                } elseif ($sub->user_id) {
                    $person = \App\Models\User::find($sub->user_id);
                    $personId = $sub->user_id;
                }
                
                if (!$person) continue;
                
                $grouped[$key] = [
                    'user' => $person,
                    'person_id' => $personId,
                    'is_candidat' => (bool) $sub->candidat_id,
                    'total' => 0,
                    'completed' => 0,
                    'draft' => 0,
                    'last_activity' => $sub->updated_at,
                ];
            }
            
            $grouped[$key]['total']++;
            if (in_array($sub->status, ['submitted', 'in_review', 'approved'])) {
                $grouped[$key]['completed']++;
            } else {
                $grouped[$key]['draft']++;
            }
            if ($sub->updated_at > $grouped[$key]['last_activity']) {
                $grouped[$key]['last_activity'] = $sub->updated_at;
            }
        }
        
        $userSubmissionsCollection = collect(array_values($grouped))->sortByDesc('last_activity');
        
        // Paginate manually
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 15;
        $paginatedSubmissions = new \Illuminate\Pagination\LengthAwarePaginator(
            $userSubmissionsCollection->forPage($currentPage, $perPage)->values(),
            $userSubmissionsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        
        return view('livewire.admin.programe.project-submissions', [
            'userSubmissions' => $paginatedSubmissions,
        ])->layout('layouts.admin', ['header' => $this->project->project_name]);
    }
}
