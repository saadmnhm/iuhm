<?php

namespace App\Livewire\Admin\Candidat;

use Livewire\Component;
use App\Models\Candidat;
use App\Models\ProgrameList;
use App\Models\DynamicFormSubmission;
use App\Models\DynamicForm;
use App\Services\FormSubmissionService;

class CandidatSubmissions extends Component
{
    public $candidat;
    public $candidatId;
    public $projectId;
    public $project;
    public $statistics = [];
    public $submissions = [];
    public $dynamicSubmissions = [];

    public function loadProject()
    {
        $this->project = ProgrameList::with('formulaires')->findOrFail($this->projectId);
    }

    public function mount($id)
    {
        $this->candidatId = $id;

        $this->projectId = $id;
        $this->loadProject();
        
        $this->candidat = Candidat::findOrFail($id);
        
        // Legacy form submissions
        $formService = app(FormSubmissionService::class);
        $this->submissions = $formService->getCandidatSubmissions($this->candidatId);

        // Submissions by formulaire
        $submissionsByFormulaire = [];
        foreach ($this->project->formulaires as $formulaire) {
            $count = DynamicFormSubmission::where('programe_id', $this->projectId)
                ->where('dynamic_form_id', $formulaire->id)
                ->count();
            
            $completed = DynamicFormSubmission::where('programe_id', $this->projectId)
                ->where('dynamic_form_id', $formulaire->id)
                ->whereIn('status', ['submitted', 'in_review', 'approved'])
                ->count();

            
            $this->dynamicSubmissions = \Illuminate\Support\Facades\DB::table('programe_formulaire')
                ->where('programe_id', $this->projectId)
                ->where('formulaire_id', $formulaire->id)
                ->orderByDesc('updated_at')
                ->get();



            $DynamicFormSubmission = DynamicFormSubmission::where('programe_id', $this->projectId)
                ->where('dynamic_form_id', $formulaire->id)
                ->first();

            $attachedForm[] = [
                'id' => $formulaire->id,
                'title' => $formulaire->title,
                'icon' => $formulaire->icon,
                'color' => $formulaire->color,
                'completed' => $completed,
                'is_active' => $formulaire->pivot->status,
                "status_label" => $completed > 0 ? 'Submitted' : 'Not Submitted',
                "status" => ['submitted', 'in_review', 'approved'],
                "programe" => ['project_name' => 'Project A'],
                "created_at" => '2024-01-01 12:00:00',
                "submitted_at" => $DynamicFormSubmission->submitted_at ?? null,
                "review_notes" => $DynamicFormSubmission->review_notes ?? null,

                
            ];

            if(isset($_GET['debug']) ) {

                echo "<pre>";
                print_r($attachedForm);
                echo "</pre>";
                exit;
            }

        }


        $this->statistics = [
            'by_formulaire' => $submissionsByFormulaire,
            'form_attahed' => $attachedForm,
        ];

    }



    public function render()
    {
        return view('livewire.admin.candidat.candidat-submissions', [
            'dynamicSubs' => $this->dynamicSubmissions,
        ])->layout('layouts.admin', ['header' => 'Soumissions de ' . $this->candidat->nom . ' ' . $this->candidat->prenom]);
    }
}
