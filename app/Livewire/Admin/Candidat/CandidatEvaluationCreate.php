<?php

namespace App\Livewire\Admin\Candidat;

use App\Models\Candidat;
use App\Models\CandidatEvaluationGrid;
use App\Models\ProgrameList;
use Livewire\Component;

class CandidatEvaluationCreate extends Component
{
    public int $candidatId;
    public int $projectId;

    public ?Candidat $candidat = null;
    public ?ProgrameList $project = null;

    public int $motivationScore = 0;
    public int $profileScore = 0;
    public int $viabilityScore = 0;
    public string $evaluationComment = '';

    public function mount(int $id, int $projectId): void
    {
        $this->candidatId = $id;
        $this->projectId = $projectId;

        $this->candidat = Candidat::findOrFail($id);
        $this->project = ProgrameList::findOrFail($projectId);

        $latest = CandidatEvaluationGrid::where('candidat_id', $id)
            ->where('project_id', $projectId)
            ->latest()
            ->first();

        $this->motivationScore = (int) ($latest->motivation_score ?? 0);
        $this->profileScore = (int) ($latest->profile_score ?? 0);
        $this->viabilityScore = (int) ($latest->viability_score ?? 0);
        $this->evaluationComment = (string) ($latest->comment ?? '');
    }

    public function save(): void
    {
        $this->validate([
            'motivationScore' => 'required|integer|min:0|max:20',
            'profileScore' => 'required|integer|min:0|max:20',
            'viabilityScore' => 'required|integer|min:0|max:20',
            'evaluationComment' => 'nullable|string|max:4000',
        ]);

        CandidatEvaluationGrid::create([
            'candidat_id' => $this->candidatId,
            'project_id' => $this->projectId,
            'admin_id' => auth()->id(),
            'motivation_score' => $this->motivationScore,
            'profile_score' => $this->profileScore,
            'viability_score' => $this->viabilityScore,
            'total_score' => $this->motivationScore + $this->profileScore + $this->viabilityScore,
            'comment' => $this->evaluationComment ?: null,
        ]);

        session()->flash('success', 'Grille d\'evaluation enregistree avec succes.');

        $this->redirectRoute('admin.candidat.submissions', ['id' => $this->candidatId, 'projectId' => $this->projectId]);
    }

    public function render()
    {
        $header = 'Grille d\'evaluation - ' . $this->candidat?->nom . ' ' . $this->candidat?->prenom;

        return view('livewire.admin.programe.candidat.candidat-evaluation-create')
            ->layout('layouts.admin', ['header' => $header]);
    }
}
