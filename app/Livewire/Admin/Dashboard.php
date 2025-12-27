<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $statistics = [
            'total_projects' => Project::count(),
            'total_users' => User::count(),
            'male_count' => Project::where('gender', 'homme')->count(),
            'female_count' => Project::where('gender', 'femme')->count(),
            'recent_projects' => Project::with('user')->latest()->take(10)->get(),
            'as' => Project::where('address', 'ain_sbaa')->count(),
            'hm' => Project::where('address', 'hay_mohamadi')->count(),
            'rn' => Project::where('address', 'rochnoir')->count(),
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
