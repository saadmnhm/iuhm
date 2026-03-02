<?php

namespace App\Livewire\Admin\Candidat;

use Livewire\Component;
use App\Models\Candidat;
use App\Models\AdminActivityLog;
use App\Models\SubmissionHistory;
use App\Models\ProgrameList;
use App\Models\DynamicFormSubmission;
use App\Models\DynamicForm;
use App\Models\User;
use App\Services\FormSubmissionService;
use Illuminate\Support\Facades\DB;

class CandidatSubmissions extends Component
{
    public $candidat;
    public $candidatId;
    public $projectId;
    public $project;
    public $statistics = [];
    public $submissions = [];
    public $dynamicSubmissions;

    // Candidat-level review modal
    public bool   $showReviewModal    = false;
    public string $reviewStatus       = 'in_review';
    public ?int   $reviewerId         = null;
    public string $reviewNotes        = '';
    public array  $admins             = [];



    public function mount($id)
    {
        $this->candidatId = $id;
        $this->candidat   = Candidat::with('reviewer')->findOrFail($id);
        $this->admins     = User::whereIn('role', ['admin', 'super_admin'])
            ->orderBy('name')
            ->get(['id', 'name', 'role'])
            ->toArray();
        
        // Get the first project this candidat has submissions for
        $firstSubmission = DynamicFormSubmission::where('candidat_id', $id)
            ->whereNotNull('programe_id')
            ->first();
        
        $this->projectId = $firstSubmission->programe_id ?? null;
        $this->project   = $this->projectId
            ? ProgrameList::with('formulaires')->findOrFail($this->projectId)
            : null;
        
        // Legacy form submissions
        $formService = app(FormSubmissionService::class);
        $this->submissions = $formService->getCandidatSubmissions($this->candidatId);

        // Submissions by formulaire
        $submissionsByFormulaire = [];
        $attachedForm = [];
        
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

                $attachedForm[] = [
                    'id' => $formulaire->id,
                    'submission_id' => $DynamicFormSubmission->id ?? null,
                    'title' => $formulaire->title,
                    'icon' => $formulaire->icon,
                    'color' => $formulaire->color,
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
                ];
            }
        }

        $this->statistics = [
            'by_formulaire' => $submissionsByFormulaire,
            'form_attached' => $attachedForm,
        ];

    }



    public function openReviewModal(): void
    {
        $this->reviewerId      = $this->candidat->reviewed_by ?? auth()->id();
        $this->reviewStatus    = $this->candidat->review_status ?? 'in_review';
        $this->reviewNotes     = $this->candidat->review_notes ?? '';
        $this->showReviewModal = true;
    }

    public function submitReview(): void
    {
        $this->validate([
            'reviewStatus' => 'required|in:in_review,approved,rejected',
            'reviewerId'   => 'required|exists:users,id',
        ]);

        $oldStatus = $this->candidat->review_status;
        $oldReviewer = $this->candidat->reviewed_by;

        $this->candidat->update([
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

            $attachedForm[] = [
                'id'               => $formulaire->id,
                'submission_id'    => $sub?->id,
                'title'            => $formulaire->title,
                'icon'             => $formulaire->icon,
                'color'            => $formulaire->color,
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
            ];
        }

        $this->statistics['form_attached'] = $attachedForm;
    }

    public function render()
    {
        return view('livewire.admin.programe.candidat.candidat-submissions')
            ->layout('layouts.admin', [
                'header' => 'Soumissions de ' . $this->candidat->nom . ' ' . $this->candidat->prenom,
            ]);
    }
}
