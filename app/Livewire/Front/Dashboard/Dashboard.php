<?php

namespace App\Livewire\Front\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $totalProjects;
    public $pendingProjects;
    public $completedProjects;
    public $recentProjects;
    public $userInfo;

    public function mount()
    {
        // Initialize dashboard statistics
        $this->totalProjects = Project::count();
        $this->pendingProjects = Project::whereNull('registration')->count();
        $this->completedProjects = Project::whereNotNull('registration')->count();
        $this->recentProjects = Project::latest()->take(5)->get();
        
        // You can add user authentication if needed
        // $this->userInfo = Auth::user();
    }

    public function render()
    {
        return view('livewire.front.dashboard.dashboard');
    }
}