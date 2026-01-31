<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use App\Models\User;
use App\Models\Candidat;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $statistics = [
            'total_projects' => Project::count(),
            'total_users' => User::count(),
            'total_candidats' => Candidat::count(),
            'male_count' => Candidat::where('gender', 'homme')->count(),
            'female_count' => Candidat::where('gender', 'femme')->count(),
            'recent_projects' => Project::with('user')->latest()->take(10)->get(),
            'as' => Candidat::where('address', 'Ain Sbaa')->count(),
            'hm' => Candidat::where('address', 'Hay Mohamadi')->count(),
            'rn' => Candidat::where('address', 'Roches noires')->count(),
        ];

        $monthlyData = Project::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyData[$i] ?? 0;
        }

        return view('livewire.admin.dashboard', [
            'statistics' => $statistics,
            'chartData' => $chartData,
        ])->layout('layouts.admin', ['header' => 'Dashboard']);
    }
}
