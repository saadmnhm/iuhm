<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Candidat;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $statistics = [
            'total_projects' => Project::count(),
            'pending_projects' => Project::where('status', 'pending')->count(),
            'approved_projects' => Project::where('status', 'approved')->count(),
            'rejected_projects' => Project::where('status', 'rejected')->count(),
            'total_users' => User::count(),
            'recent_projects' => Project::with('user')->latest()->take(10)->get(),
            'male_count' => Candidat::where('gender', 'male')->count(),
            'female_count' => Candidat::where('gender', 'female')->count(),
        ];
        
        return view('admin.dashboard', compact('statistics'));
    }
}
