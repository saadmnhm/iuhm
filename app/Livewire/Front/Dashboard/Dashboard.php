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
    public $projects = [];
    public $showCompleteProfileModal = false;

    public function mount()
    {
        $this->candidat = Auth::guard('candidat')->user();
        
        // Load all projects for this candidat
        $this->projects = Project::where('candidat_id', $this->candidat->id)
            ->latest()
            ->get();
    }

    public function goToSettings()
    {
        return redirect()->route('form.settings');
    }

    public function getFormTypesProperty()
    {
        return [
            'business_plan' => [
                'label' => 'Business Plan',
                'icon' => 'ri-bar-chart-box-line',
                'color' => 'primary',
                'route' => 'form.business_plan',
            ],
            'etude_marche' => [
                'label' => 'Étude de Marché',
                'icon' => 'ri-search-eye-line',
                'color' => 'info',
                'route' => 'form.etude_marche',
            ],
            'evaluation_idee' => [
                'label' => 'Évaluation d\'Idée',
                'icon' => 'ri-lightbulb-line',
                'color' => 'warning',
                'route' => 'form.evaluation_idee',
            ],
            'bmc' => [
                'label' => 'Business Model Canvas',
                'icon' => 'ri-layout-grid-line',
                'color' => 'success',
                'route' => 'form.bmc',
            ],
            'bilan_competence' => [
                'label' => 'Bilan de Compétences',
                'icon' => 'ri-user-star-line',
                'color' => 'secondary',
                'route' => 'form.bilan_competences',
            ],
        ];
    }

    public function getProjectForType($formType)
    {
        return $this->projects->where('form_type', $formType)->first();
    }

    public function render()
    {
        $stats = [
            'total' => $this->projects->count(),
            'drafts' => $this->projects->where('status', 'draft')->count(),
            'submitted' => $this->projects->where('status', 'submitted')->count(),
            'approved' => $this->projects->where('status', 'approved')->count(),
        ];

        return view('livewire.front.dashboard.dashboard', [
            'stats' => $stats,
        ]);
    }
}