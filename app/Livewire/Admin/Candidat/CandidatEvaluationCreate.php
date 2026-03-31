<?php

namespace App\Livewire\Admin\Candidat;

use App\Models\Candidat;
use App\Models\CandidatEvaluationGrid;
use App\Models\ProgrameList;
use Throwable;
use Illuminate\Support\Carbon;
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
    public array $criteriaNotes = [];
    public string $dateEntretien = '';
    public string $evaluateurName = '';
    public string $evaluationComment = '';

    private function defaultCriteriaNotes(): array
    {
        return [
            'pertinence' => 1,
            'experience' => 1,
            'niveau_etude' => 1,
            'capacite_financiere' => 1,
            'statut_activite' => 1,
            'infrastructure' => 1,
            'viabilite_faisabilite' => 1,
            'disponibilite' => 1,
        ];
    }

    private function normalizeCriteriaNotes($notes): array
    {
        $defaults = $this->defaultCriteriaNotes();
        if (!is_array($notes)) {
            return $defaults;
        }

        foreach ($defaults as $key => $value) {
            if (!array_key_exists($key, $notes)) {
                continue;
            }

            $raw = $notes[$key];
            if ($raw === null || $raw === '') {
                $defaults[$key] = 1;
                continue;
            }

            $defaults[$key] = max(1, min(5, (int) $raw));
        }

        return $defaults;
    }

    private function calculateGroupScore(array $keys): int
    {
        $sum = 0;
        foreach ($keys as $key) {
            $value = (int) ($this->criteriaNotes[$key] ?? 0);
            $sum += max(0, min(5, $value));
        }

        $max = count($keys) * 5;
        if ($max === 0) {
            return 0;
        }

        return (int) round(($sum / $max) * 100);
    }

    private function recalculateScores(): void
    {
        $this->motivationScore = $this->calculateGroupScore([
            'pertinence',
            'disponibilite',
        ]);

        $this->profileScore = $this->calculateGroupScore([
            'experience',
            'niveau_etude',
            'statut_activite',
            'infrastructure',
        ]);

        $this->viabilityScore = $this->calculateGroupScore([
            'capacite_financiere',
            'viabilite_faisabilite',
        ]);
    }

    public function mount(int $id, int $projectId): void
    {
        $this->candidatId = $id;
        $this->projectId = $projectId;

        $this->candidat = Candidat::findOrFail($id);
        $this->project = ProgrameList::findOrFail($projectId);

        $this->evaluateurName = auth()->user()?->name ?? 'Administrateur';

        $latest = CandidatEvaluationGrid::where('candidat_id', $id)
            ->where('project_id', $projectId)
            ->with('admin')
            ->latest()
            ->first();

        $this->criteriaNotes = $this->normalizeCriteriaNotes($latest?->criteria_notes);
        $this->recalculateScores();
        $this->dateEntretien = $latest?->date_entretien?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->evaluateurName = $latest?->admin?->name ?? $this->evaluateurName;
        $this->evaluationComment = (string) ($latest->comment ?? '');
    }

    public function updated($property): void
    {
        if (is_string($property) && str_starts_with($property, 'criteriaNotes.')) {
            $this->recalculateScores();
        }
    }

    public function getTotalScoreProperty(): int
    {
        return (int) $this->motivationScore + (int) $this->profileScore + (int) $this->viabilityScore;
    }

    public function save(): void
    {
        $this->dateEntretien = trim((string) $this->dateEntretien);
        $this->evaluationComment = trim((string) $this->evaluationComment);

        $this->validate([
            'dateEntretien' => 'required|date',
            'criteriaNotes.pertinence' => 'required|integer|min:1|max:5',
            'criteriaNotes.experience' => 'required|integer|min:1|max:5',
            'criteriaNotes.niveau_etude' => 'required|integer|min:1|max:5',
            'criteriaNotes.capacite_financiere' => 'required|integer|min:1|max:5',
            'criteriaNotes.statut_activite' => 'required|integer|min:1|max:5',
            'criteriaNotes.infrastructure' => 'required|integer|min:1|max:5',
            'criteriaNotes.viabilite_faisabilite' => 'required|integer|min:1|max:5',
            'criteriaNotes.disponibilite' => 'required|integer|min:1|max:5',
            'evaluationComment' => 'required|string|max:4000',
        ]);

        $this->criteriaNotes = $this->normalizeCriteriaNotes($this->criteriaNotes);
        $this->recalculateScores();

        try {
            CandidatEvaluationGrid::create([
                'candidat_id' => $this->candidatId,
                'project_id' => $this->projectId,
                'admin_id' => auth()->id(),
                'date_entretien' => Carbon::parse($this->dateEntretien)->toDateString(),
                'criteria_notes' => $this->criteriaNotes,
                'motivation_score' => $this->motivationScore,
                'profile_score' => $this->profileScore,
                'viability_score' => $this->viabilityScore,
                'total_score' => $this->totalScore,
                'comment' => $this->evaluationComment,
            ]);
        } catch (Throwable $exception) {
            report($exception);
            $this->addError('save', 'Enregistrement echoue. Verifiez les donnees puis reessayez.');
            return;
        }

        session()->flash('success', 'Grille d\'evaluation enregistree avec succes.');

        $this->redirectRoute('admin.candidat.evaluation.create', ['id' => $this->candidatId, 'projectId' => $this->projectId]);
    }

    public function render()
    {
        $header = 'Grille d\'evaluation - ' . $this->candidat?->nom . ' ' . $this->candidat?->prenom;

        return view('livewire.admin.programe.candidat.candidat-evaluation-create')
            ->layout('layouts.admin', ['header' => $header]);
    }
}
