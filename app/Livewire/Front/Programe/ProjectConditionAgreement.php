<?php

namespace App\Livewire\Front\Programe;

use App\Models\CandidatProjectAgreement;
use App\Models\ProgrameList;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProjectConditionAgreement extends Component
{
    public int $projectId;
    public ?ProgrameList $project = null;
    public bool $acceptConditions = false;
    public string $projectIdea = '';

    public function mount($id): void
    {
        $this->projectId = (int) $id;
        $this->project = ProgrameList::findOrFail($this->projectId);
    }

    public function agreeAndContinue()
    {
        $this->validate([
            'acceptConditions' => 'accepted',
            'projectIdea' => 'required|string|min:20|max:4000',
        ], [
            'acceptConditions.accepted' => 'Vous devez accepter les conditions pour continuer.',
            'projectIdea.required' => 'Veuillez saisir l\'idée de votre projet.',
            'projectIdea.min' => 'L\'idée doit contenir au moins 20 caractères.',
        ]);

        $candidatId = Auth::guard('candidat')->id();

        CandidatProjectAgreement::updateOrCreate(
            [
                'candidat_id' => $candidatId,
                'project_id' => $this->projectId,
            ],
            [
                'agreed_at' => now(),
                'agreed_ip' => request()->ip(),
                'project_idea' => $this->projectIdea,
            ]
        );

        return redirect()->route('user.project.detail', $this->projectId)
            ->with('success', 'Conditions acceptées. Vous pouvez maintenant commencer vos formulaires.');
    }

    public function render()
    {
        return view('livewire.front.programe.project-condition-agreement')
            ->layout('layouts.app', ['title' => 'Conditions du projet']);
    }
}
