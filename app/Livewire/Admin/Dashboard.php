<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Candidat;
use App\Models\DynamicFormSubmission;
use App\Services\FormSubmissionService;
use App\Models\ProgrameList;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $formService = app(FormSubmissionService::class);
        $allSubmissions = $formService->getAllSubmissions();
        $programe_list = ProgrameList::all();
        
        // Dynamic address distribution (top 6)
        $addressDistribution = Candidat::whereNotNull('address')
            ->selectRaw('address, COUNT(*) as count')
            ->groupBy('address')
            ->orderByDesc('count')
            ->limit(6)
            ->pluck('count', 'address')
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
            'recent_projects' => $allSubmissions->take(20),
            'total_projects' => $programe_list->count(),
            'total_candidats' => Candidat::count(),
            'male_count' => Candidat::where('gender', 'homme')->count(),
            'female_count' => Candidat::where('gender', 'femme')->count(),
            'address_labels' => array_keys($addressDistribution),
            'address_values' => array_values($addressDistribution),
            'submission_stats' => $submissionStats,
        ];

        $programe_list = ProgrameList::all();
        // Get monthly data for all forms
        $monthlyData = [];
        foreach ($allSubmissions as $submission) {
            $month = $submission->created_at->month;
            $monthlyData[$month] = ($monthlyData[$month] ?? 0) + 1;
        }

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyData[$i] ?? 0;
        }
        // echo "<pre>";
        // print_r($programe_list);
        // echo "</pre>";
        // exit;

        return view('livewire.admin.dashboard', [
            'statistics' => $statistics,
            'chartData' => $chartData,
            'programe_list' => $programe_list,
        ])->layout('layouts.admin', ['header' => 'Dashboard']);
    }
    
}
