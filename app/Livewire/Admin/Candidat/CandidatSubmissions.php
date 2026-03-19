<?php

namespace App\Livewire\Admin\Candidat;

use Livewire\Component;
use App\Models\Candidat;
use App\Models\AdminActivityLog;
use App\Models\SubmissionHistory;
use App\Models\ProgrameList;
use App\Models\DynamicFormSubmission;
use App\Models\DynamicForm;
use App\Models\CandidatFormulaireOrder;
use App\Models\CandidatEvaluationGrid;
use App\Models\ProjectSubmission;
use App\Models\User;
use App\Services\FormSubmissionService;
use Illuminate\Support\Facades\DB;

class CandidatSubmissions extends Component
{
    private const SUBMISSION_STATUS_VALUES = ['draft', 'submitted', 'in_review', 'approved', 'rejected'];

    public $candidat;
    public $candidatId;
    public $candidatSubmissions;
    public $projectId;
    public $project;
    public $is_evaluated;
    public $statistics = [];
    public $submissions = [];
    public $dynamicSubmissions;
    public array $customOrders = [];
    public array $globalOrders = [];
    public array $lockedOrders = [];

    // Candidat-level review modal
    public bool   $showReviewModal    = false;
    public string $reviewStatus       = 'in_review';
    public ?int   $reviewerId         = null;
    public string $reviewNotes        = '';
    public array  $admins             = [];

    // Fiche modal
    public bool $showFicheModal = false;

    public string $formationRanking = '';

    // Submission workflow modal
    public bool $showWorkflowModal = false;
    public ?int $workflowSubmissionId = null;
    public string $workflowStatus = 'in_review';
    public string $workflowComment = '';
    public bool $stageFormationValidated = false;
    public bool $stageCandidateInFormation = false;
    public bool $stageAdministrativeValidated = false;
    public bool $workflowIsLastForm = false;
    public bool $workflowAlreadyAllowed = false;
    public array $workflowHistory = [];



    public function mount($id, $projectId = null)
    {
        $this->candidatId = $id;
        $this->candidat   = Candidat::with('reviewer')->findOrFail($id);
        $this->formationRanking = (string) ($this->candidat->formation_ranking ?? '');
        $this->admins     = User::all()->sortBy('name')->values()->toArray();
        
        // Get project from URL when provided and valid for this candidat; fallback to first available
        $firstSubmission = DynamicFormSubmission::where('candidat_id', $id)
            ->whereNotNull('programe_id')
            ->first();

        $requestedProjectId = $projectId !== null ? (int) $projectId : null;
        $projectExistsForCandidat = $requestedProjectId
            ? DynamicFormSubmission::where('candidat_id', $id)
                ->where('programe_id', $requestedProjectId)
                ->exists()
            : false;

        $this->projectId = $projectExistsForCandidat
            ? $requestedProjectId
            : ($firstSubmission->programe_id ?? null);

        $this->project   = $this->projectId
            ? ProgrameList::with('formulaires')->findOrFail($this->projectId)
            : null;

        $this->candidatSubmissions = ProjectSubmission::firstOrCreate(
            [
                'candidat_id' => $this->candidatId,
                'programe_id' => $this->projectId,
            ],
            [
                'review_status' => 'in_review',
            ]
        );
        
        // Legacy form submissions
        $formService = app(FormSubmissionService::class);
        $this->submissions = $formService->getCandidatSubmissions($this->candidatId);

        // Submissions by formulaire
        $submissionsByFormulaire = [];
        $attachedForm = [];

        $customOrdersMap = collect();
        if ($this->projectId) {
            $customOrdersMap = CandidatFormulaireOrder::where('candidat_id', $this->candidatId)
                ->where('programe_id', $this->projectId)
                ->get()
                ->keyBy('formulaire_id');
        }
        
        if ($this->project) {
            foreach ($this->project->formulaires as $formulaire) {
                $count = DynamicFormSubmission::where('programe_id', $this->projectId)
                    ->where('dynamic_form_id', $formulaire->id)
                    ->where('candidat_id', $this->candidatId)
                    ->count();
                
                $completed = DynamicFormSubmission::where('programe_id', $this->projectId)
                    ->where('dynamic_form_id', $formulaire->id)
                    ->where('candidat_id', $this->candidatId)
                    ->whereIn('status', ['submitted', 'in_review', 'approved'])
                    ->count();

                $this->dynamicSubmissions = \Illuminate\Support\Facades\DB::table('programe_formulaire')
                    ->where('programe_id', $this->projectId)
                    ->where('formulaire_id', $formulaire->id)
                    ->orderByDesc('updated_at')
                    ->get();

                $DynamicFormSubmission = DynamicFormSubmission::with('reviewer')
                    ->where('programe_id', $this->projectId)
                    ->where('dynamic_form_id', $formulaire->id)
                    ->where('candidat_id', $this->candidatId)
                    ->first();

                $submissionsByFormulaire[] = [
                    'id' => $formulaire->id,
                    'title' => $formulaire->title,
                    'icon' => $formulaire->icon,
                    'color' => $formulaire->color,
                    'total' => $count,
                    'completed' => $completed,
                    'is_active' => $formulaire->pivot->status === 'active',
                ];

                $effectiveOrder = $customOrdersMap->has($formulaire->id)
                    ? (int) $customOrdersMap->get($formulaire->id)->order
                    : (int) $formulaire->pivot->order;

                $this->globalOrders[$formulaire->id] = (int) $formulaire->pivot->order;
                $this->customOrders[$formulaire->id] = $effectiveOrder;
                $this->lockedOrders[$formulaire->id] = (bool) (
                    ($DynamicFormSubmission?->is_submitted ?? false)
                    || in_array($DynamicFormSubmission?->status, ['submitted', 'in_review', 'approved'], true)
                );

                $attachedForm[] = [
                    'id' => $formulaire->id,
                    'submission_id' => $DynamicFormSubmission->id ?? null,
                    'title' => $formulaire->title,
                    'icon' => $formulaire->icon,
                    'color' => $formulaire->color,
                    'order' => $effectiveOrder,
                    'global_order' => (int) $formulaire->pivot->order,
                    'has_custom_order' => $customOrdersMap->has($formulaire->id),
                    'completed' => $completed,
                    'is_active' => $formulaire->pivot->status,
                    'actual_status'    => $DynamicFormSubmission?->status,
                    'is_submitted'     => $DynamicFormSubmission?->is_submitted ?? 0,
                    'status_label'     => $DynamicFormSubmission?->status
                        ? (['draft' => 'Brouillon', 'submitted' => 'Soumis', 'in_review' => 'En révision', 'approved' => 'Approuvé', 'rejected' => 'Rejeté'][$DynamicFormSubmission->status] ?? ucfirst($DynamicFormSubmission->status))
                        : 'Non soumis',
                    'programe'         => ['project_name' => $this->project->project_name ?? 'N/A'],
                    'created_at'       => $DynamicFormSubmission?->created_at?->format('d/m/Y H:i'),
                    'submitted_at'     => $DynamicFormSubmission?->submitted_at?->format('d/m/Y H:i'),
                    'review_notes'     => $DynamicFormSubmission?->review_notes,
                    'all_stages_validated' => $this->allWorkflowStagesValidated($DynamicFormSubmission?->workflow_stages),
                    'next_form_allowed' => $this->isNextFormAllowed($DynamicFormSubmission?->workflow_stages),
                ];
            }
        }

        $attachedForm = collect($attachedForm)
            ->sortBy([['order', 'asc'], ['global_order', 'asc'], ['id', 'asc']])
            ->values()
            ->toArray();

        $attachedForm = $this->addFormPositionFlags($attachedForm);

        $this->statistics = [
            'by_formulaire' => $submissionsByFormulaire,
            'form_attached' => $attachedForm,
        ];

    }



    public function openReviewModal(): void
    {
        $this->reviewerId      = $this->candidatSubmissions->reviewed_by ?? auth()->id();
        $this->reviewStatus    = $this->candidatSubmissions->review_status ?? 'in_review';
        $this->reviewNotes     = $this->candidatSubmissions->review_notes ?? '';
        $this->showReviewModal = true;
    }

    public function submitReview(): void
    {
        $this->validate([
            'reviewStatus' => 'required|in:in_review,approved,rejected',
            'reviewerId'   => 'required|exists:users,id',
        ]);

        $oldStatus = $this->candidatSubmissions->review_status;
        $oldReviewer = $this->candidatSubmissions->reviewed_by;

        $this->candidatSubmissions->update([
            'reviewed_by'   => $this->reviewerId,
            'reviewed_at'   => now(),
            'review_notes'  => $this->reviewNotes ?: null,
            'review_status' => $this->reviewStatus,
        ]);

        // Log history
        if ($oldStatus !== $this->reviewStatus) {
            SubmissionHistory::log(Candidat::class, $this->candidat->id, 'status_changed', $oldStatus, $this->reviewStatus, $this->reviewNotes);
        }
        if ($oldReviewer != $this->reviewerId) {
            $reviewerName = User::find($this->reviewerId)?->name ?? 'N/A';
            SubmissionHistory::log(Candidat::class, $this->candidat->id, 'reviewer_assigned', null, $reviewerName, null);
        }

        // Reload candidat with fresh reviewer relation
        $this->candidat = Candidat::with('reviewer')->findOrFail($this->candidatId);

        AdminActivityLog::log(
            'candidat_review_submitted',
            "Submitted review for candidat: {$this->candidat->nom} {$this->candidat->prenom} (status: {$this->reviewStatus})",
            Candidat::class,
            $this->candidat->id
        );

        $this->showReviewModal = false;
        session()->flash('success', 'Révision assignée avec succès.');
    }

    public function loadFormData(): void
    {
        if (!$this->project) {
            return;
        }

        $customOrdersMap = CandidatFormulaireOrder::where('candidat_id', $this->candidatId)
            ->where('programe_id', $this->projectId)
            ->get()
            ->keyBy('formulaire_id');

        $statusLabels = [
            'draft'     => 'Brouillon',
            'submitted' => 'Soumis',
            'in_review' => 'En révision',
            'approved'  => 'Approuvé',
            'rejected'  => 'Rejeté',
        ];

        $attachedForm = [];
        foreach ($this->project->formulaires as $formulaire) {
            $sub = DynamicFormSubmission::with('reviewer')
                ->where('programe_id', $this->projectId)
                ->where('dynamic_form_id', $formulaire->id)
                ->where('candidat_id', $this->candidatId)
                ->first();

            $effectiveOrder = $customOrdersMap->has($formulaire->id)
                ? (int) $customOrdersMap->get($formulaire->id)->order
                : (int) $formulaire->pivot->order;

            $this->globalOrders[$formulaire->id] = (int) $formulaire->pivot->order;
            $this->customOrders[$formulaire->id] = $effectiveOrder;
            $this->lockedOrders[$formulaire->id] = (bool) (
                ($sub?->is_submitted ?? false)
                || in_array($sub?->status, ['submitted', 'in_review', 'approved'], true)
            );

            $attachedForm[] = [
                'id'               => $formulaire->id,
                'submission_id'    => $sub?->id,
                'title'            => $formulaire->title,
                'icon'             => $formulaire->icon,
                'color'            => $formulaire->color,
                'order'            => $effectiveOrder,
                'global_order'     => (int) $formulaire->pivot->order,
                'has_custom_order' => $customOrdersMap->has($formulaire->id),
                'completed'        => $sub && in_array($sub->status, ['submitted', 'in_review', 'approved']) ? 1 : 0,
                'is_active'        => $formulaire->pivot->status,
                'actual_status'    => $sub?->status,
                'is_submitted'     => $sub?->is_submitted,
                'status_label'     => $sub?->status
                    ? ($statusLabels[$sub->status] ?? ucfirst($sub->status))
                    : 'Non soumis',
                'programe'         => ['project_name' => $this->project->project_name ?? 'N/A'],
                'created_at'       => $sub?->created_at?->format('d/m/Y H:i'),
                'submitted_at'     => $sub?->submitted_at?->format('d/m/Y H:i'),
                'review_notes'     => $sub?->review_notes,
                'all_stages_validated' => $this->allWorkflowStagesValidated($sub?->workflow_stages),
                'next_form_allowed' => $this->isNextFormAllowed($sub?->workflow_stages),
            ];
        }

        $attachedForm = collect($attachedForm)
            ->sortBy([['order', 'asc'], ['global_order', 'asc'], ['id', 'asc']])
            ->values()
            ->toArray();

        $attachedForm = $this->addFormPositionFlags($attachedForm);

        $this->statistics['form_attached'] = $attachedForm;
    }

    protected function addFormPositionFlags(array $attachedForm): array
    {
        $activeForms = collect($attachedForm)
            ->where('is_active', 'active')
            ->values();

        $lastFormId = $activeForms->isNotEmpty()
            ? (int) ($activeForms->last()['id'] ?? 0)
            : null;

        return collect($attachedForm)
            ->map(function (array $form) use ($lastFormId) {
                $isLastForm = $lastFormId !== null && (int) ($form['id'] ?? 0) === $lastFormId;
                $nextAllowed = (bool) ($form['next_form_allowed'] ?? false);

                $form['is_last_form'] = $isLastForm;
                $form['can_authorize_next'] = !$isLastForm && !$nextAllowed;

                return $form;
            })
            ->toArray();
    }

    public function saveCustomOrders(): void
    {
        if (!$this->projectId || !$this->project) {
            session()->flash('success', 'Aucun projet disponible pour configurer l\'ordre.');
            return;
        }

        $allowedFormIds = $this->project->formulaires->pluck('id')->map(fn ($id) => (int) $id)->toArray();

        DB::transaction(function () use ($allowedFormIds) {
            foreach ($allowedFormIds as $formId) {
                if (($this->lockedOrders[$formId] ?? false) === true) {
                    // Safety: never change order for already submitted/reviewed forms
                    continue;
                }

                $value = $this->customOrders[$formId] ?? $this->globalOrders[$formId] ?? 1;
                $order = max(1, (int) $value);
                $globalOrder = (int) ($this->globalOrders[$formId] ?? 1);

                if ($order === $globalOrder) {
                    CandidatFormulaireOrder::where('candidat_id', $this->candidatId)
                        ->where('programe_id', $this->projectId)
                        ->where('formulaire_id', $formId)
                        ->delete();
                    continue;
                }

                CandidatFormulaireOrder::updateOrCreate(
                    [
                        'candidat_id' => $this->candidatId,
                        'programe_id' => $this->projectId,
                        'formulaire_id' => $formId,
                    ],
                    [
                        'order' => $order,
                    ]
                );
            }
        });

        $this->loadFormData();
        session()->flash('success', 'Ordre personnalisé enregistré avec succès (hors formulaires déjà soumis).');
    }

    public function openFicheModal(): void
    {
        $this->showFicheModal = true;
    }

    public function toggleFormationReview(): void
    {
        if ($this->candidatSubmissions) {
            $this->candidatSubmissions->update([
                'require_formation_review' => !$this->candidatSubmissions->require_formation_review
            ]);
            $this->candidatSubmissions->refresh();
            
            $status = $this->candidatSubmissions->require_formation_review ? 'activée' : 'désactivée';
            session()->flash('message', "La demande d'avis de formation a été $status.");
            $this->dispatch('close-modal');
        }
    }

    public function saveCandidatSettings(): void
    {
        $this->validate([
            'formationRanking' => 'nullable|string|max:5000',
        ]);

        $this->candidat->update([
            'formation_ranking' => $this->formationRanking ?: null,
        ]);

        $this->candidat = Candidat::with('reviewer')->findOrFail($this->candidatId);
        session()->flash('success', 'Paramètres du candidat enregistrés avec succès.');
    }

    protected function allWorkflowStagesValidated($workflowStages): bool
    {
        $stages = is_array($workflowStages) ? $workflowStages : [];
        return (bool) (($stages['formation_validated'] ?? false)
            && ($stages['candidate_in_formation'] ?? false)
            && ($stages['administrative_validated'] ?? false));
    }

    protected function isNextFormAllowed($workflowStages): bool
    {
        $stages = is_array($workflowStages) ? $workflowStages : [];
        return (bool) ($stages['next_form_allowed'] ?? false);
    }

    public function openWorkflowModal(int $submissionId): void
    {
        $submission = DynamicFormSubmission::findOrFail($submissionId);

        $stages = is_array($submission->workflow_stages) ? $submission->workflow_stages : [];
        $this->workflowSubmissionId = $submission->id;
        $currentStatus = (string) ($submission->status ?? 'in_review');
        $this->workflowStatus = in_array($currentStatus, self::SUBMISSION_STATUS_VALUES, true)
            ? $currentStatus
            : 'in_review';
        $this->workflowComment = '';
        $this->stageFormationValidated = (bool) ($stages['formation_validated'] ?? false);
        $this->stageCandidateInFormation = (bool) ($stages['candidate_in_formation'] ?? false);
        $this->stageAdministrativeValidated = (bool) ($stages['administrative_validated'] ?? false);

        $currentForm = collect($this->statistics['form_attached'] ?? [])
            ->first(fn ($f) => (int) ($f['submission_id'] ?? 0) === (int) $submissionId);
        $this->workflowIsLastForm = (bool) ($currentForm['is_last_form'] ?? false);
        $this->workflowAlreadyAllowed = (bool) ($currentForm['next_form_allowed'] ?? false);

        $this->workflowHistory = SubmissionHistory::forSubject(DynamicFormSubmission::class, $submissionId)
            ->map(function ($h) {
                return [
                    'action' => $h->action,
                    'old' => $h->old_value,
                    'new' => $h->new_value,
                    'notes' => $h->notes,
                    'by' => $h->changedByUser?->name ?? 'Système',
                    'at' => $h->created_at?->format('d/m/Y H:i'),
                ];
            })->toArray();

        $this->showWorkflowModal = true;
    }

    public function saveWorkflowProgress(): void
    {
        $this->validate([
            'workflowSubmissionId' => 'required|integer|exists:dynamic_form_submissions,id',
            'workflowStatus' => 'required|in:draft,submitted,in_review,approved,rejected',
            'workflowComment' => 'required|string|min:3|max:2000',
        ], [
            'workflowComment.required' => 'Un commentaire est obligatoire à chaque changement.',
        ]);

        $submission = DynamicFormSubmission::findOrFail($this->workflowSubmissionId);
        $oldStatus = $submission->status;
        $existingStages = is_array($submission->workflow_stages) ? $submission->workflow_stages : [];
        $nextFormAllowed = $oldStatus === $this->workflowStatus
            ? (bool) ($existingStages['next_form_allowed'] ?? false)
            : false;

        $submission->update([
            'status' => $this->workflowStatus,
            'workflow_stages' => [
                'formation_validated' => $this->stageFormationValidated,
                'candidate_in_formation' => $this->stageCandidateInFormation,
                'administrative_validated' => $this->stageAdministrativeValidated,
                'next_form_allowed' => $nextFormAllowed,
            ],
            'review_notes' => $this->workflowComment,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        if ($oldStatus !== $this->workflowStatus) {
            SubmissionHistory::log(
                DynamicFormSubmission::class,
                $submission->id,
                'status_changed',
                (string) $oldStatus,
                (string) $this->workflowStatus,
                $this->workflowComment
            );
        } else {
            SubmissionHistory::log(
                DynamicFormSubmission::class,
                $submission->id,
                'submission_updated',
                null,
                null,
                $this->workflowComment
            );
        }

        $this->loadFormData();
        ProjectSubmission::syncFinishedStatusFor((int) $submission->candidat_id, (int) $submission->programe_id);
        $this->candidatSubmissions->refresh();
        $this->openWorkflowModal($submission->id);
        session()->flash('success', 'Étapes / statut mis à jour.');
    }

    public function allowNextFormulaire(int $submissionId): void
    {
        $submission = DynamicFormSubmission::findOrFail($submissionId);

        $currentForm = collect($this->statistics['form_attached'] ?? [])
            ->first(fn ($f) => (int) ($f['submission_id'] ?? 0) === (int) $submissionId);

        if ((bool) ($currentForm['is_last_form'] ?? false)) {
            session()->flash('error', 'Ce formulaire est le dernier. Aucun passage suivant à autoriser.');
            return;
        }

        if ((bool) ($currentForm['next_form_allowed'] ?? false)) {
            session()->flash('error', 'Le passage au formulaire suivant est déjà autorisé pour ce formulaire.');
            return;
        }

        $stages = is_array($submission->workflow_stages) ? $submission->workflow_stages : [];

        $allValidated = (bool) (($stages['formation_validated'] ?? false)
            && ($stages['candidate_in_formation'] ?? false)
            && ($stages['administrative_validated'] ?? false));

        if (!$allValidated) {
            session()->flash('error', 'Toutes les étapes doivent être validées avant d\'autoriser le passage au formulaire suivant.');
            return;
        }

        $oldStatus = $submission->status;
        $workflowStages = is_array($submission->workflow_stages) ? $submission->workflow_stages : [];

        $submission->update([
            'status' => $submission->status,
            'is_submitted' => $submission->is_submitted,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'review_notes' => $this->workflowComment ?: 'Validation finale: autorisation de passage au formulaire suivant.',
            'workflow_stages' => [
                'formation_validated' => (bool) ($workflowStages['formation_validated'] ?? false),
                'candidate_in_formation' => (bool) ($workflowStages['candidate_in_formation'] ?? false),
                'administrative_validated' => (bool) ($workflowStages['administrative_validated'] ?? false),
                'next_form_allowed' => true,
            ],
        ]);

        SubmissionHistory::log(
            DynamicFormSubmission::class,
            $submission->id,
            'submission_updated',
            (string) $oldStatus,
            (string) $submission->status,
            'Validation finale de toutes les étapes. Passage au formulaire suivant autorisé.'
        );

        $this->loadFormData();
        ProjectSubmission::syncFinishedStatusFor((int) $submission->candidat_id, (int) $submission->programe_id);
        $this->candidatSubmissions->refresh();
        if ($this->showWorkflowModal && $this->workflowSubmissionId === $submissionId) {
            $this->openWorkflowModal($submissionId);
        }

        session()->flash('success', 'Formulaire validé: le candidat peut passer au suivant.');
    }



    public function render()
    {
        $is_evaluated = CandidatEvaluationGrid::where('candidat_id', $this->candidatId)
            ->where('project_id', $this->projectId)
            ->exists();

        $this->is_evaluated = $is_evaluated;


        return view('livewire.admin.programe.candidat.candidat-submissions')
            ->layout('layouts.admin', [
                'header' => 'Soumissions de ' . $this->candidat->nom . ' ' . $this->candidat->prenom . ($this->project ? " - {$this->project->project_name}" : ''),
                'is_evaluated' => $is_evaluated,
                ]);
    }
}
