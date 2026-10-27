<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\Candidat;
use App\Services\CandidatEvaluationService;
use Illuminate\Http\RedirectResponse;

class CandidatEvaluationController extends Controller
{
    public function __construct(
        protected CandidatEvaluationService $evaluationService
    ) {}

    /**
     * Attach the evaluation form to the candidate's project, unlock the
     * preceding forms, and warn the candidate on their dashboard.
     */
    public function start(int $id): RedirectResponse
    {
        $candidat = Candidat::findOrFail($id);

        $evaluationForm = $this->evaluationService->resolveEvaluationForm();

        if (! $evaluationForm) {
            return back()->with('error', 'Formulaire d’évaluation d’idée introuvable.');
        }

        $project = $this->evaluationService->resolveProject($candidat);

        if (! $project) {
            return back()->with('error', 'Aucun projet actif n’est disponible pour ce candidat.');
        }

        $order = $this->evaluationService->attachForm($project, $evaluationForm);
        $blockingTitle = $this->evaluationService->unlockPrecedingForms($candidat, $project, $evaluationForm);

        if ($blockingTitle !== null) {
            return back()->with(
                'error',
                "Le candidat doit d’abord compléter le formulaire « {$blockingTitle} » avant de remplir l’évaluation d’idée."
            );
        }

        AdminActivityLog::log(
            'candidat_evaluation_form_started',
            "Attached evaluation form {$evaluationForm->title} to project {$project->project_name} for candidat {$candidat->nom} {$candidat->prenom}.",
            Candidat::class,
            $candidat->id,
            [
                'form_id' => $evaluationForm->id,
                'project_id' => $project->id,
                'order' => $order,
            ]
        );

        return redirect()
            ->route('admin.candidats.show', $candidat->id)
            ->with('success', 'Le formulaire d’évaluation d’idée a été assigné. Un avertissement s’affichera sur le tableau de bord du candidat.');
    }
}
