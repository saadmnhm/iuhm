<?php

namespace App\Livewire\Front\Programe;

use Livewire\Component;
use App\Models\ProgrameList;
use App\Models\DynamicFormSubmission;
use App\Models\CandidatFormulaireOrder;
use App\Models\CandidatProjectAgreement;
use Illuminate\Support\Facades\Auth;
use App\Services\ProjectEligibilityService;

class ProjectDetail extends Component
{
    public $projectId;
    public $project;
    public $formulaires = [];
    public $currentFormulaireIndex = null;
    protected ProjectEligibilityService $eligibilityService;

    public function boot(ProjectEligibilityService $eligibilityService): void
    {
        $this->eligibilityService = $eligibilityService;
    }
    
    public function mount($id)
    {
        $this->projectId = $id;
        return $this->loadProject();
    }
    
    public function loadProject()
    {
        $this->project = ProgrameList::with(['formulaires' => function($query) {
            $query->where('programe_formulaire.status', 'active')
                  ->orderBy('programe_formulaire.order');
        }])->findOrFail($this->projectId);

        $candidat = Auth::guard('candidat')->user();
        if ($candidat) {
            $check = $this->eligibilityService->evaluate($candidat, $this->project);
            if (!$check['eligible']) {
                session()->flash('error', 'Vous ne pouvez pas accéder à ce projet: ' . implode(' ', $check['reasons']));
                return redirect()->route('user.projects.list');
            }
        }
        
        // Frontend is behind candidat middleware, always use candidat guard
        $candidatId = Auth::guard('candidat')->id();

        $hasAgreement = CandidatProjectAgreement::where('candidat_id', $candidatId)
            ->where('project_id', $this->projectId)
            ->exists();

        if (!$hasAgreement) {
            return redirect()->route('user.project.conditions', $this->projectId);
        }

        $customOrders = collect();
        if ($candidatId) {
            $customOrders = CandidatFormulaireOrder::where('candidat_id', $candidatId)
                ->where('programe_id', $this->projectId)
                ->get()
                ->keyBy('formulaire_id');
        }

        // Load formulaires with their details
        $forms = $this->project->formulaires->map(function($form) use ($candidatId, $customOrders) {
            $submission = null;
            
            // Check if candidat has submitted this formulaire for this project
            if ($candidatId) {
                $submission = DynamicFormSubmission::where('dynamic_form_id', $form->id)
                    ->where('programe_id', $this->projectId)
                    ->where('candidat_id', $candidatId)
                    ->first();
            }

            $effectiveOrder = $customOrders->has($form->id)
                ? (int) $customOrders->get($form->id)->order
                : (int) $form->pivot->order;

            $unlockOnStatus = $form->pivot->unlock_on_status ?? 'approved';
            $status = $submission?->status;
            
            return [
                'id' => $form->id,
                'title' => $form->title,
                'title_ar' => $form->title_ar,
                'slug' => $form->slug,
                'order' => $effectiveOrder,
                'global_order' => (int) $form->pivot->order,
                'is_required' => $form->pivot->is_required,
                'unlock_on_status' => $unlockOnStatus,
                'has_introduction' => $form->has_introduction,
                'icon' => $form->icon ?? 'ri-file-list-3-line',
                'color' => $form->color ?? '#2f5496',
                'status' => $form->pivot->status,
                'submission_id' => $submission?->id,
                'submitted_at' => $submission?->submitted_at,
                'is_submitted' => $submission?->is_submitted ?? false,
                'submission_status' => $status,
                'status_label' => $submission?->status_label ?? null,
                'is_unlocked_for_next' => $this->meetsUnlockStatus($submission, $unlockOnStatus),
            ];
        })
        ->sortBy([['order', 'asc'], ['global_order', 'asc'], ['id', 'asc']])
        ->values();

        $blockingTitle = null;
        $forms = $forms->values()->map(function ($form, $index) use (&$blockingTitle) {
            $canStart = $blockingTitle === null;
            $form['index'] = $index;
            $form['can_start'] = $canStart;
            $form['lock_reason'] = $canStart
                ? null
                : 'Vous devez attendre la validation du formulaire précédent: ' . $blockingTitle;

            if ($form['is_required'] && !$form['is_unlocked_for_next'] && $blockingTitle === null) {
                $blockingTitle = $form['title'];
            }

            return $form;
        });

        $this->formulaires = $forms->toArray();
        
        // Find the first incomplete formulaire
        $this->findCurrentFormulaire();
    }

    protected function meetsUnlockStatus(?DynamicFormSubmission $submission, string $unlockOnStatus): bool
    {
        if (!$submission) {
            return false;
        }

        $stages = is_array($submission->workflow_stages) ? $submission->workflow_stages : [];
        return (bool) ($stages['next_form_allowed'] ?? false);
    }
    
    public function findCurrentFormulaire()
    {
        foreach ($this->formulaires as $index => $form) {
            if ($form['can_start'] && !$form['is_submitted']) {
                $this->currentFormulaireIndex = $index;
                return;
            }
        }
        // If all completed, set to null
        $this->currentFormulaireIndex = null;
    }
    
    public function startFormulaire($index)
    {
        if (isset($this->formulaires[$index])) {
            $formulaire = $this->formulaires[$index];

            if (!$formulaire['can_start']) {
                session()->flash('error', $formulaire['lock_reason'] ?: 'Vous devez compléter le formulaire précédent.');
                return;
            }
            
            return redirect()->route('user.project.formulaire', [
                'projectId' => $this->projectId,
                'formulaireSlug' => $formulaire['slug'],
                'order' => $formulaire['order']
            ]);
        }
    }
    
    public function render()
    {
        return view('livewire.front.programe.project-detail')
            ->layout('layouts.app', ['title' => $this->project->project_name]);
    }
}
