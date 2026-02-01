<?php

namespace App\Livewire\Front\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Project;
use App\Models\Candidat;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $candidat;
    public $project;
    public $showCompleteProfileModal = false;

    public function mount()
    {
        $this->candidat = Auth::guard('candidat')->user();
        
        // Load existing project if available
        $this->project = Project::where('candidat_id', $this->candidat->id)
            ->latest()
            ->first();
    }

    public function goToSettings()
    {
        return redirect()->route('form.settings');
    }


    public function render()
    {
        return view('livewire.front.dashboard.dashboard');
    }
}