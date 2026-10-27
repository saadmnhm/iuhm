<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\Candidat;
use App\Services\CandidatFormationReviewService;
use Illuminate\Http\RedirectResponse;

class CandidatFormationReviewController extends Controller
{
    public function __construct(
        protected CandidatFormationReviewService $reviewService
    ) {}

    /**
     * Request the candidate's formation review and warn them on their dashboard.
     */
    public function start(int $id): RedirectResponse
    {
        $candidat = Candidat::findOrFail($id);

        $request = $this->reviewService->requestFor($candidat);

        if (! $request) {
            return back()->with('error', 'Aucun projet actif n’est disponible pour ce candidat.');
        }

        $project = $request['project'];
        $submission = $request['submission'];

        AdminActivityLog::log(
            'candidat_formation_review_requested',
            "Requested formation review for project {$project->project_name} for candidat {$candidat->nom} {$candidat->prenom}.",
            Candidat::class,
            $candidat->id,
            [
                'project_id' => $project->id,
                'submission_id' => $submission->id,
            ]
        );

        return redirect()
            ->route('admin.candidats.show', $candidat->id)
            ->with('success', 'L’avis de formation a été demandé. Un avertissement s’affichera sur le tableau de bord du candidat.');
    }
}
