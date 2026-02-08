<?php

namespace App\Livewire\Front\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessPlan;

class Aside extends Component
{
    public $showCompleteProfileModal = false;
    public $candidat;
    public $business_plan = [];

    public function mount()
    {
        $this->candidat = Auth::guard('candidat')->user();
        $this->checkProfileCompletion();
        
        // Load candidate business plans for dropdown
        if ($this->candidat) {
            $this->business_plan = BusinessPlan::where('candidat_id', $this->candidat->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    public function checkProfileCompletion()
    {
        if (request()->routeIs('form.settings')) {
            $this->showCompleteProfileModal = false;
            return;
        }
        
        if (!$this->candidat->phone || !$this->candidat->address || !$this->candidat->age) {
            $this->showCompleteProfileModal = true;
        }
    }

    public function goToSettings()
    {
        return redirect()->route('form.settings');
    }

    public function render()
    {
        return view('livewire.front.dashboard.aside');
    }
}
