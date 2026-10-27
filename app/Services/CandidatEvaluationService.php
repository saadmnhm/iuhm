<?php

namespace App\Services;

use App\Models\Candidat;
use App\Models\CandidatProjectAgreement;
use App\Models\DynamicForm;
use App\Models\DynamicFormSubmission;
use App\Models\ProjectsList;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CandidatEvaluationService
{
    public function resolveEvaluationForm(): ?DynamicForm
    {
        $form = DynamicForm::where('is_active', true)
            ->where('slug', 'evaluation-didee-de-projet')
            ->first();

        if ($form) {
            return $form;
        }

        return DynamicForm::where('is_active', true)
            ->where('title', 'like', '%valuation%')
            ->where(function ($query) {
                $query->where('title', 'like', '%idée%')
                    ->orWhere('title', 'like', '%idee%');
            })
            ->orderBy('id')
            ->first();
    }

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

    public function attachForm(ProjectsList $project, DynamicForm $form): int
    {
        $existing = DB::table('programe_formulaire')
            ->where('programe_id', $project->id)
            ->where('formulaire_id', $form->id)
            ->first();

        if ($existing) {
            DB::table('programe_formulaire')
                ->where('id', $existing->id)
                ->update([
                    'status' => 'active',
                    'unlock_on_status' => 'submitted',
                    'updated_at' => now(),
                ]);

            return (int) $existing->order;
        }

        $maxOrder = (int) DB::table('programe_formulaire')
            ->where('programe_id', $project->id)
            ->max('order');

        $order = $maxOrder + 1;

        DB::table('programe_formulaire')->insert([
            'programe_id' => $project->id,
            'formulaire_id' => $form->id,
            'order' => $order,
            'status' => 'active',
            'is_required' => true,
            'unlock_on_status' => 'submitted',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $order;
    }

    /**
     * Allow the candidate to move past every submitted form that comes
     * before the evaluation form.
     *
     * @return string|null The title of the first form still blocking access, if any.
     */
    public function unlockPrecedingForms(Candidat $candidat, ProjectsList $project, DynamicForm $evaluationForm): ?string
    {
        $precedingRows = $this->precedingRows($project, $evaluationForm);

        $submissions = DynamicFormSubmission::where('candidat_id', $candidat->id)
            ->where('programe_id', $project->id)
            ->whereIn('dynamic_form_id', $precedingRows->pluck('formulaire_id'))
            ->get()
            ->keyBy('dynamic_form_id');

        foreach ($precedingRows as $row) {
            $submission = $submissions->get($row->formulaire_id);

            if (! $submission || ! $submission->is_submitted) {
                continue;
            }

            $stages = is_array($submission->workflow_stages) ? $submission->workflow_stages : [];

            if (($stages['next_form_allowed'] ?? false) !== true) {
                $stages['next_form_allowed'] = true;
                $submission->update(['workflow_stages' => $stages]);
            }
        }

        return $this->blockingTitle($candidat, $project, $evaluationForm);
    }

    /**
     * Return the pending evaluation form details for the candidate dashboard.
     *
     * @return array{form: DynamicForm, project: ProjectsList, order: int, url: string}|null
     */
    public function pendingFor(Candidat $candidat): ?array
    {
        $form = $this->resolveEvaluationForm();

        if (! $form) {
            return null;
        }

        $project = $this->resolveProject($candidat);

        if (! $project) {
            return null;
        }

        $pivot = DB::table('programe_formulaire')
            ->where('programe_id', $project->id)
            ->where('formulaire_id', $form->id)
            ->where('status', 'active')
            ->first();

        if (! $pivot) {
            return null;
        }

        $submission = DynamicFormSubmission::where('candidat_id', $candidat->id)
            ->where('programe_id', $project->id)
            ->where('dynamic_form_id', $form->id)
            ->first();

        if ($submission && $submission->is_submitted) {
            return null;
        }

        if ($this->blockingTitle($candidat, $project, $form) !== null) {
            return null;
        }

        return [
            'form' => $form,
            'project' => $project,
            'order' => (int) $pivot->order,
            'url' => route('user.project.formulaire', [
                'projectId' => $project->id,
                'formulaireSlug' => $form->slug,
                'order' => (int) $pivot->order,
            ]),
        ];
    }

    /**
     * @return Collection<int, object>
     */
    protected function precedingRows(ProjectsList $project, DynamicForm $evaluationForm): Collection
    {
        $pivotRows = DB::table('programe_formulaire')
            ->where('programe_id', $project->id)
            ->where('status', 'active')
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $evaluationIndex = $pivotRows->search(
            fn ($row) => (int) $row->formulaire_id === (int) $evaluationForm->id
        );

        return $evaluationIndex === false
            ? collect()
            : $pivotRows->slice(0, $evaluationIndex)->values();
    }

    protected function blockingTitle(Candidat $candidat, ProjectsList $project, DynamicForm $evaluationForm): ?string
    {
        $precedingRows = $this->precedingRows($project, $evaluationForm);

        $submissions = DynamicFormSubmission::where('candidat_id', $candidat->id)
            ->where('programe_id', $project->id)
            ->whereIn('dynamic_form_id', $precedingRows->pluck('formulaire_id'))
            ->get()
            ->keyBy('dynamic_form_id');

        foreach ($precedingRows as $row) {
            $submission = $submissions->get($row->formulaire_id);

            $unlocked = $submission
                && $submission->is_submitted
                && (($submission->workflow_stages['next_form_allowed'] ?? false) === true);

            if ((bool) $row->is_required && ! $unlocked) {
                return DynamicForm::find($row->formulaire_id)?->title ?? 'précédent';
            }
        }

        return null;
    }
}
