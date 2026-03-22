<?php

namespace App\Livewire\Front\Programe;

use App\Models\ProgrameList;
use App\Models\ProjectSubmission;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProjectFormationReview extends Component
{
    public int $projectId;
    public $project;
    public $projectSubmission;

    public ?int $reviewRating = null;
    public string $reviewFeedback = '';
    public array $answers = [];

    public function mount($id): void
    {
        $this->projectId = (int) $id;
        $this->project = ProgrameList::findOrFail($this->projectId);

        $candidat = Auth::guard('candidat')->user();
        abort_unless($candidat, 403);

        $this->projectSubmission = ProjectSubmission::firstOrCreate(
            [
                'candidat_id' => $candidat->id,
                'programe_id' => $this->projectId,
            ],
            [
                'review_status' => 'in_review',
            ]
        );

        $this->reviewRating = $this->projectSubmission->formation_review_rating;
        $this->reviewFeedback = (string) ($this->projectSubmission->formation_review_feedback ?? '');
        $this->answers = $this->projectSubmission->formation_review_answers ?? [];
    }

    public function saveReview()
    {
        $this->validate([
            'reviewRating' => 'required|integer|min:1|max:5',
            'reviewFeedback' => 'nullable|string|max:3000',
            'answers' => 'required|array',
            'answers.q1' => 'required|integer|min:1|max:3',
            'answers.q2' => 'required|integer|min:1|max:3',
            'answers.q3' => 'required|integer|min:1|max:3',
            'answers.q4' => 'required|integer|min:1|max:3',
            'answers.q5' => 'required|integer|min:1|max:3',
            'answers.q6' => 'required|integer|min:1|max:3',
            'answers.q7' => 'required|integer|min:1|max:3',
            'answers.q8' => 'required|integer|min:1|max:3',
        ], [
            'answers.*.required' => __('Veuillez répondre à toutes les questions.')
        ]);

        $candidat = Auth::guard('candidat')->user();
        abort_unless($candidat, 403);

        $this->projectSubmission->update([
            'formation_review_rating' => $this->reviewRating,
            'formation_review_feedback' => $this->reviewFeedback,
            'formation_review_answers' => $this->answers,
            'last_activity' => now(),
        ]);

        session()->flash('success', __('Avis enregistré avec succès.'));

        return redirect()->route('user.project.detail', ['id' => $this->projectId]);
    }

    public function render()
    {
        return view('livewire.front.programe.project-formation-review')
            ->layout('layouts.app', ['title' => __('Avis de formation')]);
    }
}
