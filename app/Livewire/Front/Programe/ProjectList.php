<?php

namespace App\Livewire\Front\Programe;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\ProjectsList;
use App\Models\DynamicFormSubmission;
use Illuminate\Support\Facades\Auth;
use App\Services\ProjectEligibilityService;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
class ProjectList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    protected ProjectEligibilityService $eligibilityService;

    public function boot(ProjectEligibilityService $eligibilityService): void
    {
        $this->eligibilityService = $eligibilityService;
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }

    public function render()
    {
        $candidat = Auth::guard('candidat')->user();

        // Build query for all active projects with eligibility info
        $projectsQuery = ProjectsList::where('is_active', true)
            ->orderBy('sort_order');

        if ($this->search) {
            $projectsQuery->where(function ($q) {
                $q->where('project_name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $allProjects = $projectsQuery->get();

        // For each project, get the latest submission and eligibility
        $rows = $allProjects->map(function ($project) use ($candidat) {
            $check = $this->eligibilityService->evaluate($candidat, $project);

            $submission = DynamicFormSubmission::where('candidat_id', $candidat->id)
                ->where('programe_id', $project->id)
                ->orderByDesc('updated_at')
                ->first();

           
            return [
                'id'           => $project->id,
                'name'         => $project->project_name,
                'description'  => Str::limit(strip_tags((string) $project->description), 200),
                'icon'         => $project->icon ?: 'ri-briefcase-4-line',
                'color'        => $project->color ?: '#2f5496',
                'eligible'     => $check['eligible'],
                'reasons'      => $check['reasons'],
                'submission'   => $submission,
                'submitted_at' => $submission?->created_at?->format('d/m/Y'),
                'updated_at'   => $submission?->updated_at?->format('d/m/Y'),
                'min_age'      => $project->min_age,
                'max_age'      => $project->max_age,
                'locations'    => \App\Models\MoroccoLocation::whereIn('id', $project->allowed_location_ids ?? [])->get()->map(function($loc) {
                    return strtoupper($loc->city . ($loc->prefecture ? ', ' . $loc->prefecture : ''));
                })->implode(' / ') ?: 'TOUT LE MAROC',
            ];
        });

        // Apply status filter
        if ($this->statusFilter) {
            $rows = $rows->filter(function ($row) {
                if ($this->statusFilter === 'eligible')     return $row['eligible'] && !$row['submission'];
                if ($this->statusFilter === 'not_eligible') return !$row['eligible'];
                if ($this->statusFilter === 'submitted')    return $row['submission'] && in_array($row['submission']->status, ['submitted', 'in_review']);
                if ($this->statusFilter === 'approved')     return $row['submission'] && $row['submission']->status === 'approved';
                return true;
            });
        }

        $total        = $rows->count();
        $approved     = $rows->filter(fn($r) => $r['submission'] && $r['submission']->status === 'approved')->count();
        $inReview     = $rows->filter(fn($r) => $r['submission'] && in_array($r['submission']->status, ['submitted', 'in_review']))->count();
        $notEligible  = $rows->filter(fn($r) => !$r['eligible'])->count();

        // Manual pagination
        $page     = $this->getPage();
        $perPage  = 10;
        $paginated = $rows->values()->forPage($page, $perPage);
        $isArabic = str_starts_with(app()->getLocale(), 'ar');
        $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
        $state_card = [
            'Total Projets' => [
                'label' =>  $tr('Total Candidatures', 'إجمالي الترشيحات'),
                'icon' => 'ri-file-list-3-line',
                'data' => $total,
            ],
            'approved' => [
                'label' => $tr('Candidatures Approuvé', 'المقبولة'),
                'icon' => 'ri-checkbox-circle-line',
                'data' => $approved,
            ],
            'review' => [
                'label' => $tr('Candidatures En Révision', 'قيد المراجعة'),
                'icon' => 'ri-time-line',
                'data' => $inReview,
            ],
            'notEligible' => [
                'label' => $tr('Candidatures Non Éligibles', 'غير المؤهلة'),
                'icon' => 'ri-error-warning-line',
                'data' => $notEligible,
            ],

        ];

        return view('livewire.front.programe.project-list', [
            'state_card' => $state_card,
            'rows'    => $rows,
            'total' => $total,
            'totalPages'  => (int) ceil($total / $perPage),
            'currentPage' => $page,
            'perPage'     => $perPage,
        ]);
    }
}
