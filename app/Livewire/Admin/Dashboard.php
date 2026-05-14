<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Candidat;
use App\Models\DynamicFormSubmission;
use App\Models\ProjectsSubmission;
use App\Models\ProjectsList;
use Livewire\Component;

class Dashboard extends Component
{
    public $projectId;


    public function render()
    {

        $matricule_null = Candidat::whereNull('matricule')->count();

        $latestSubmission = DynamicFormSubmission::latest()->first();
        $this->projectId = $latestSubmission?->programe_id;

        $allSubmissions = DynamicFormSubmission::with(['candidat', 'programe'])->latest()->get();
        $programe_list = ProjectsList::all();

        $ProjectsSubmissions = ProjectsSubmission::latest('updated_at')->get();


        
        // Dynamic address distribution (top 6)
        $addressDistribution = Candidat::whereNotNull('selected_prefecture')
            ->selectRaw('selected_prefecture as selected_prefecture , COUNT(*) as count')
            ->groupBy('selected_prefecture')
            ->orderByDesc('count')
            // ->limit(6)
            ->pluck('count', 'selected_prefecture')
            ->toArray();

        // Submission status counts
        $submissionStats = [
            'submitted' => DynamicFormSubmission::where('status', 'submitted')->count(),
            'in_review' => DynamicFormSubmission::where('status', 'in_review')->count(),
            'approved'  => DynamicFormSubmission::where('status', 'approved')->count(),
            'rejected'  => DynamicFormSubmission::where('status', 'rejected')->count(),
        ];

        $statistics = [
            'total_users' => User::count(),
            'total_candidats' => Candidat::count(),
            'total_submissions' => ProjectsSubmission::count(),
            'recent_projects' => $allSubmissions->take(20),
            'total_projects' => $programe_list->count(),
            'total_candidats' => Candidat::count(),
            'male_count' => Candidat::where('gender', 'homme')->count(),
            'female_count' => Candidat::where('gender', 'femme')->count(),
            'address_labels' => array_keys($addressDistribution),
            'address_values' => array_values($addressDistribution),
            'submission_stats' => $submissionStats,
        ];

        

        $monthlyData = [];
        foreach ($allSubmissions as $submission) {
            $month = $submission->created_at->month;
            $monthlyData[$month] = ($monthlyData[$month] ?? 0) + 1;
        }

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyData[$i] ?? 0;
        }
            
        $menu = [
            ['label' => 'Admin Console', 'icon' => 'ri-dashboard-line', 'route' =>  route('admin.users.index')],
            ['label' => 'Gestion des Projets', 'icon' => 'ri-network-line', 'route' => route('admin.programe')],
            ['label' => 'Gestion des Submissions', 'icon' => 'ri-briefcase-line', 'route' => route('admin.all.submissions')],
            ['label' => 'Media Management', 'icon' => 'ri-image-2-line', 'route' => route('admin.media.index')],
        ];

        return view('livewire.admin.dashboard', [
            'statistics' => $statistics,
            'chartData' => $chartData,
            'userSubmissions' => $ProjectsSubmissions,
            'matricule_null' => $matricule_null,
            'admins' => User::whereIn('role', ['admin', 'super_admin'])->orderBy('nom')->get(['id', 'nom']),
            'menu' => $menu,
        ])->layout('layouts.admin', ['header' => 'Dashboard']);
    }
    
}
