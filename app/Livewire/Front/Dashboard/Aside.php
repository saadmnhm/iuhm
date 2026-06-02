<?php

namespace App\Livewire\Front\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
class Aside extends Component
{
    public $showCompleteProfileModal = false;
    public $candidat;
    public $activeTab = 'profile';
    public $isSettingsPage = false;

    protected $listeners = [
        'set-active-tab' => 'setActiveTab',
    ];

    public function setActiveTab($tab)
    {
        if (is_array($tab)) {
            $this->activeTab = $tab['tab'] ?? 'profile';
        } else {
            $this->activeTab = $tab;
        }
    }

    public function mount()
    {
        $this->candidat = Auth::guard('candidat')->user();
        
        // Don't render aside for non-candidat users (admins, etc)
        if (!$this->candidat) {
            $this->skipRender();
            return;
        }

        $this->isSettingsPage = request()->routeIs('user.settings');
        
        $this->checkProfileCompletion();
    }

    public function checkProfileCompletion()
    {
        if ($this->isSettingsPage) {
            $this->showCompleteProfileModal = false;
            return;
        }
        
        if (!$this->candidat || !$this->candidat->phone || !$this->candidat->selected_prefecture || !$this->candidat->date_naissance || !$this->candidat->niveau_etude || !$this->candidat->specialite || !$this->candidat->gender || !$this->candidat->address_detail) {
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
