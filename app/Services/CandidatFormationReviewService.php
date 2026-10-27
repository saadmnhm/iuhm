<?php

namespace App\Services;

use App\Models\Candidat;
use App\Models\CandidatProjectAgreement;
use App\Models\DynamicFormSubmission;
use App\Models\ProjectsList;
use App\Models\ProjectsSubmission;

class CandidatFormationReviewService
{
    /**
     * Resolve the project the candidate is currently working on.
     */
    public function resolveProject(Candidat $candidat): ?ProjectsList
    {
        $projectId = DynamicFormSubmission::where('candidat_id', $candidat->id)
            ->whereNotNull('programe_id')
            ->latest('updated_at')
            ->value('programe_id');

        if (! $projectId) {
            $projectId = CandidatProjectAgreement::where('candidat_id', $candidat->id)
                ->latest('agreed_at')
                ->value('project_id');
        }

        if (! $projectId) {
            $projectId = ProjectsList::where('is_active', true)
                ->orderBy('sort_order')
                ->value('id');
        }

        return $projectId ? ProjectsList::find($projectId) : null;
    }

    /**
     * Flag the candidate's project submission as requiring a formation review.
     *
     * @return array{project: ProjectsList, submission: ProjectsSubmission}|null
     */
    public function requestFor(Candidat $candidat): ?array
    {
        $project = $this->resolveProject($candidat);

        if (! $project) {
            return null;
        }

        $submission = ProjectsSubmission::firstOrCreate(
            [
                'candidat_id' => $candidat->id,
                'programe_id' => $project->id,
            ],
            [
                'review_status' => 'in_review',
            ]
        );

        if (! $submission->require_formation_review) {
            $submission->update([
                'require_formation_review' => true,
                'last_activity' => now(),
            ]);
        }

        return [
            'project' => $project,
            'submission' => $submission,
        ];
    }

    /**
     * Return the pending formation review details for the candidate dashboard.
     *
     * @return array{project: ProjectsList, submission: ProjectsSubmission, url: string}|null
     */
    public function pendingFor(Candidat $candidat): ?array
    {
        $submission = ProjectsSubmission::where('candidat_id', $candidat->id)
            ->where('require_formation_review', true)
            ->whereNull('formation_review_rating')
            ->latest('updated_at')
            ->first();

        if (! $submission) {
            return null;
        }

        $project = ProjectsList::find($submission->programe_id);

        if (! $project) {
            return null;
        }

        return [
            'project' => $project,
            'submission' => $submission,
            'url' => route('user.project.review', ['id' => $project->id]),
        ];
    }
}
