<?php

namespace App\Livewire\Admin\Candidat;

use App\Models\Role;
use App\Models\Candidat;
use App\Models\AdminActivityLog;
use App\Models\DynamicFormSubmission;
use App\Models\ProgrameList;
use App\Models\CandidatFormulaireOrder;
use App\Services\ProjectEligibilityService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin', ['header' => 'View Candidat'])]
class ShowCandidat extends Component
{
    public $candidat;
    public $recentLogs;
    public $dynamicSubmissions;
    public $eligibleProjects;

    public bool $showRankingModal = false;
    public string $rankingStatus = 'pending';
    public string $rankingNote = '';

    protected ProjectEligibilityService $eligibilityService;

    public function boot(ProjectEligibilityService $eligibilityService): void
    {
        $this->eligibilityService = $eligibilityService;
    }

    public function mount($id)
    {
        $this->candidat = Candidat::findOrFail($id);
        $this->rankingStatus = (string) ($this->candidat->ranking_feedback_status ?? 'pending');
        $this->rankingNote = (string) ($this->candidat->ranking_feedback_note ?? '');
        $this->loadData();
    }

    protected function loadData(): void
    {
        $this->recentLogs = AdminActivityLog::with('user')
            ->where('subject_type', Candidat::class)
            ->where('subject_id', $this->candidat->id)
            ->latest()
            ->take(25)
            ->get();

        $customOrders = CandidatFormulaireOrder::where('candidat_id', $this->candidat->id)
            ->get()
            ->keyBy(fn ($row) => $row->programe_id . '-' . $row->formulaire_id);

        $this->dynamicSubmissions = DynamicFormSubmission::with(['form', 'programe'])
            ->where('candidat_id', $this->candidat->id)
            ->latest('updated_at')
            ->take(40)
            ->get()
            ->map(function ($sub) use ($customOrders) {
                $effectiveOrder = null;
                if ($sub->programe_id && $sub->dynamic_form_id) {
                    $key = $sub->programe_id . '-' . $sub->dynamic_form_id;
                    $effectiveOrder = $customOrders->has($key) ? (int) $customOrders[$key]->order : null;
                }

                return [
                    'id' => $sub->id,
                    'project_name' => $sub->programe?->project_name ?? '—',
                    'form_title' => $sub->form?->title ?? '—',
                    'status' => $sub->status,
                    'status_label' => $sub->status_label,
                    'is_submitted' => (bool) $sub->is_submitted,
                    'effective_order' => $effectiveOrder,
                    'created_at' => $sub->created_at,
                    'submitted_at' => $sub->submitted_at,
                ];
            });

        $this->eligibleProjects = ProgrameList::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($project) {
                $check = $this->eligibilityService->evaluate($this->candidat, $project);
                return [
                    'id' => $project->id,
                    'project_name' => $project->project_name,
                    'eligible' => (bool) ($check['eligible'] ?? false),
                    'reasons' => $check['reasons'] ?? [],
                ];
            });
    }

    public function toggleStatus()
    {
        $currentUser = auth()->user();

        if (!(Role::isDevelopmentAccessLocked() && Role::canBypassDevelopmentLock($currentUser->role)) && !$currentUser->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can change user status.');
            return;
        }

        $this->candidat->update([
            'is_active' => !$this->candidat->is_active
        ]);

        session()->flash('success', 'Candidat status updated successfully!');
    }

    public function openRankingModal(): void
    {
        $this->showRankingModal = true;
    }

    public function saveRankingDecision(): void
    {
        $this->validate([
            'rankingStatus' => 'required|in:pending,good,not_good',
            'rankingNote' => 'nullable|string|max:4000',
        ]);

        $this->candidat->update([
            'ranking_feedback_status' => $this->rankingStatus,
            'ranking_feedback_note' => $this->rankingNote ?: null,
        ]);

        AdminActivityLog::log(
            'candidat_ranking_feedback_updated',
            "Updated ranking feedback for candidat {$this->candidat->nom} {$this->candidat->prenom}: {$this->rankingStatus}",
            Candidat::class,
            $this->candidat->id,
            ['note' => $this->rankingNote]
        );

        $this->showRankingModal = false;
        session()->flash('success', 'Décision de classement enregistrée.');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.candidat.show-candidat');
    }
}
