<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Navbar extends Component
{
    public $pageTitle = 'Dashboard';
    public $profile_image;
    public $candidat;
    public function mount($pageTitle = null)
    {
        $this->candidat = Auth::guard('candidat')->user();
        $this->pageTitle = $pageTitle ?? 'Dashboard';
        $this->profile_image = $this->candidat->profile_image;
    }

    public function render()
    {
        return view('livewire.components.navbar');
    }
}
