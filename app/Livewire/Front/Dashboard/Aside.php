<?php

namespace App\Livewire\Front\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
class Aside extends Component
{
    public $showCompleteProfileModal = false;
    public $candidat;

    public function mount()
    {
        $this->candidat = Auth::guard('candidat')->user();
        $this->checkProfileCompletion();
        
       
    }

    public function checkProfileCompletion()
    {
        if (request()->routeIs('user.settings')) {
            $this->showCompleteProfileModal = false;
            return;
        }
        
        if (!$this->candidat->phone || !$this->candidat->selected_prefecture || !$this->candidat->age) {
            $this->showCompleteProfileModal = true;
        }
    }

    public function goToSettings()
    {
        return redirect()->route('user.settings');
    }

    public function render()
    {
        return view('livewire.front.dashboard.aside');
    }
}
