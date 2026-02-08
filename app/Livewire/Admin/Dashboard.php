<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Candidat;
use App\Services\FormSubmissionService;
use App\Models\ProgrameList;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $formService = app(FormSubmissionService::class);
        $allSubmissions = $formService->getAllSubmissions();
        
        $statistics = [
            'total_projects' => $allSubmissions->count(),
            'total_users' => User::count(),
            'total_candidats' => Candidat::count(),
            'male_count' => Candidat::where('gender', 'homme')->count(),
            'female_count' => Candidat::where('gender', 'femme')->count(),
            'recent_projects' => $formService->getRecentSubmissions(10),
            'as' => Candidat::where('address', 'Ain Sbaa')->count(),
            'hm' => Candidat::where('address', 'Hay Mohamadi')->count(),
            'rn' => Candidat::where('address', 'Roches noires')->count(),
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

        return view('livewire.admin.dashboard', [
            'statistics' => $statistics,
            'chartData' => $chartData,
            'programe_list' => $programe_list,
        ])->layout('layouts.admin', ['header' => 'Dashboard']);
    }
    
}
