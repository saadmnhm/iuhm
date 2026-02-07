<?php

namespace App\Livewire\Admin\Project;

use Livewire\Component;
use App\Services\FormSubmissionService;

class FormSubmissionView extends Component
{
    public $submission;
    public $formType;
    public $submissionId;

    public function mount($type, $id)
    {
        $this->formType = $type;
        $this->submissionId = $id;
        
        // Redirect to specific detail pages
        return match($type) {
            'business_plan' => redirect()->route('admin.projects.show', $id),
            'etude_marche' => redirect()->route('admin.etude-marche.show', $id),
            'evaluation_idee' => redirect()->route('admin.evaluation-idee.show', $id),
            'bmc' => redirect()->route('admin.bmc.show', $id),
            'bilan_competence' => redirect()->route('admin.bilan-competence.show', $id),
            default => abort(404, 'Form type not found'),
        };
    }

    public function render()
    {
        $viewName = match($this->formType) {
            'business_plan' => 'livewire.admin.submissions.business-plan-view',
            'etude_marche' => 'livewire.admin.submissions.etude-marche-view',
            'evaluation_idee' => 'livewire.admin.submissions.evaluation-idee-view',
            'bmc' => 'livewire.admin.submissions.bmc-view',
            'bilan_competence' => 'livewire.admin.submissions.bilan-competence-view',
            default => 'livewire.admin.submissions.generic-view',
        };

        return view($viewName, [
            'submission' => $this->submission,
        ])->layout('layouts.admin', ['header' => 'View Submission']);
    }
}
