<?php

namespace App\Livewire\Admin\Candidat;

use Livewire\Component;
use App\Models\Candidat;
use App\Models\DynamicFormSubmission;
use App\Services\FormSubmissionService;

class CandidatSubmissions extends Component
{
    public $candidat;
    public $candidatId;
    public $submissions = [];
    public $dynamicSubmissions = [];

    public function mount($id)
    {
        $this->candidatId = $id;
        $this->candidat = Candidat::findOrFail($id);
        
        // Legacy form submissions
        $formService = app(FormSubmissionService::class);
        $this->submissions = $formService->getCandidatSubmissions($this->candidatId);
        
        // Dynamic form submissions
        $this->dynamicSubmissions = DynamicFormSubmission::with(['form', 'programe'])
            ->where('candidat_id', $this->candidatId)
            ->orderByDesc('updated_at')
            ->get();
    }

    public function getFormStatusProperty()
    {
        $formTypes = [
            'business_plan' => [
                'label' => 'Business Plan',
                'icon' => 'ri-bar-chart-box-line',
                'color' => 'blue',
                'route' => 'form.business_plan',
            ],
            'etude_marche' => [
                'label' => 'Étude de Marché',
                'icon' => 'ri-search-eye-line',
                'color' => 'green',
                'route' => 'form.etude_marche',
            ],
            'evaluation_idee' => [
                'label' => 'Évaluation d\'Idée',
                'icon' => 'ri-lightbulb-line',
                'color' => 'purple',
                'route' => 'form.evaluation_idee',
            ],
            'bmc' => [
                'label' => 'Business Model Canvas',
                'icon' => 'ri-layout-grid-line',
                'color' => 'yellow',
                'route' => 'form.bmc',
            ],
            'bilan_competence' => [
                'label' => 'Bilan de Compétences',
                'icon' => 'ri-user-star-line',
                'color' => 'pink',
                'route' => 'form.bilan_competences',
            ],
        ];

        $status = [];
        foreach ($formTypes as $type => $info) {
            $submission = $this->submissions->first(function($s) use ($type) {
                $formType = $s->form_type ?? $s->getFormType();
                return $formType === $type;
            });

            $status[$type] = [
                'info' => $info,
                'submission' => $submission,
                'has_submission' => !is_null($submission),
            ];
        }

        return $status;
    }

    public function render()
    {
        return view('livewire.admin.candidat-submissions', [
            'formStatus' => $this->formStatus,
            'dynamicSubs' => $this->dynamicSubmissions,
        ])->layout('layouts.admin', ['header' => 'Soumissions de ' . $this->candidat->nom . ' ' . $this->candidat->prenom]);
    }
}
