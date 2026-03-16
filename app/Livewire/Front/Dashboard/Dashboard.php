<?php

namespace App\Livewire\Front\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\FormSubmissionService;
use App\Services\ProjectEligibilityService;
use App\Models\Candidat;
use App\Models\ProgrameList;
use App\Models\DynamicFormSubmission;
use App\Models\CandidatFormulaireOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $candidat;
    public $projects = [];
    public $dynamicSubmissions = [];
    public $showCompleteProfileModal = false;
    public $activeFilter = null;
    public $searchQuery = '';
    protected $formSubmissionService;
    protected $eligibilityService;

    public function boot(FormSubmissionService $formSubmissionService, ProjectEligibilityService $eligibilityService)
    {
        $this->formSubmissionService = $formSubmissionService;
        $this->eligibilityService = $eligibilityService;
    }

    public function mount()
    {
        $this->candidat = Auth::guard('candidat')->user();
        
        // Get all form submissions for this candidat (legacy forms)
        $this->projects = app(FormSubmissionService::class)
            ->getCandidatSubmissions($this->candidat->id);
        
        // Load dynamic form submissions (drafts + submitted)
        $this->loadDynamicSubmissions();
    }

    protected function loadDynamicSubmissions()
    {
        $submissionRows = DynamicFormSubmission::with(['form', 'programe'])
            ->where('candidat_id', $this->candidat->id)
            ->orderByDesc('updated_at')
            ->get();

        $projectIds = $submissionRows->pluck('programe_id')->filter()->unique()->values();
        $formIds = $submissionRows->pluck('dynamic_form_id')->filter()->unique()->values();

        $pivotOrders = DB::table('programe_formulaire')
            ->whereIn('programe_id', $projectIds)
            ->whereIn('formulaire_id', $formIds)
            ->select('programe_id', 'formulaire_id', 'order')
            ->get()
            ->keyBy(fn ($row) => $row->programe_id . '-' . $row->formulaire_id);

        $customOrders = CandidatFormulaireOrder::where('candidat_id', $this->candidat->id)
            ->whereIn('programe_id', $projectIds)
            ->whereIn('formulaire_id', $formIds)
            ->get()
            ->keyBy(fn ($row) => $row->programe_id . '-' . $row->formulaire_id);

        $this->dynamicSubmissions = $submissionRows
            ->map(function ($sub) use ($pivotOrders, $customOrders) {
                $order = 1;
                if ($sub->programe_id && $sub->programe) {
                    $key = $sub->programe_id . '-' . $sub->dynamic_form_id;
                    if ($customOrders->has($key)) {
                        $order = (int) $customOrders->get($key)->order;
                    } elseif ($pivotOrders->has($key)) {
                        $order = (int) $pivotOrders->get($key)->order;
                    }
                }

                return [
                    'id' => $sub->id,
                    'form_title' => $sub->form->title ?? 'Unknown Form',
                    'form_title_ar' => $sub->form->title_ar ?? '',
                    'form_slug' => $sub->form->slug ?? '',
                    'form_icon' => $sub->form->icon ?? 'ri-file-list-3-line',
                    'form_color' => $sub->form->color ?? '#2f5496',
                    'programe_id' => $sub->programe_id,
                    'programe_name' => $sub->programe->project_name ?? null,
                    'order' => $order,
                    'status' => $sub->status,
                    'status_label' => $sub->status_label,
                    'status_badge_color' => $sub->status_badge_color,
                    'current_step' => $sub->current_step,
                    'total_steps' => $sub->form ? $sub->form->steps()->count() : 0,
                    'created_at' => $sub->created_at?->format('d/m/Y H:i'),
                    'updated_at' => $sub->updated_at?->format('d/m/Y H:i'),
                    'submitted_at' => $sub->submitted_at?->format('d/m/Y H:i'),
                ];
            })
            ->toArray();
    }

    public function setFilter(string $status)
    {
        $this->activeFilter = ($this->activeFilter === $status) ? null : $status;
    }

    public function clearFilters()
    {
        $this->activeFilter = null;
        $this->searchQuery = '';
    }

    public function resumeForm($submissionId)
    {
        $sub = collect($this->dynamicSubmissions)->firstWhere('id', $submissionId);
        if (!$sub) return;

        if ($sub['programe_id']) {
            return redirect()->route('user.project.formulaire', [
                'projectId' => $sub['programe_id'],
                'formulaireSlug' => $sub['form_slug'],
                'order' => $sub['order'],
            ]);
        } else {
            return redirect()->route('user.dynamic_form', $sub['form_slug']);
        }
    }

    public function goToSettings()
    {
        return redirect()->route('user.settings');
    }

    public function getFormTypesProperty()
    {
        return [];
    }

    public function getProjectForType($formType)
    {
        return $this->projects->first(function($project) use ($formType) {
            $projectFormType = $project->form_type ?? $project->getFormType();
            return $projectFormType === $formType;
        });
    }

    public function render()
    {
        $allDynamic = collect($this->dynamicSubmissions);
        $candidateAge = $this->candidat->age;

        if (!$candidateAge && $this->candidat->date_naissance) {
            $candidateAge = Carbon::parse($this->candidat->date_naissance)->age;
        }

        $submittedProjectIds = $allDynamic
            ->pluck('programe_id')
            ->filter()
            ->unique()
            ->values();

        $projects = ProgrameList::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $projectInsights = $projects->map(function ($project) use ($submittedProjectIds) {
            $check = $this->eligibilityService->evaluate($this->candidat, $project);
            $alreadyStarted = $submittedProjectIds->contains((int) $project->id);

            return [
                'id' => $project->id,
                'name' => $project->project_name,
                'description' => Str::limit(strip_tags((string) $project->description), 140),
                'icon' => $project->icon ?: 'ri-briefcase-4-line',
                'color' => $project->color ?: '#2f5496',
                'bg_color' => $project->bg_color ?: '#f8fafc',
                'min_age' => $project->min_age,
                'max_age' => $project->max_age,
                'eligible' => $check['eligible'],
                'reasons' => $check['reasons'],
                'already_started' => $alreadyStarted,
            ];
        })
        ->sortByDesc('eligible')
        ->values();

        $projectEligibilityStats = [
            'total' => $projectInsights->count(),
            'eligible' => $projectInsights->where('eligible', true)->count(),
            'not_eligible' => $projectInsights->where('eligible', false)->count(),
            'started' => $projectInsights->where('already_started', true)->count(),
        ];

        $stats = [
            'total'    => $this->projects->count() + $allDynamic->count(),
            'drafts'   => $this->projects->where('status', 'draft')->count() + $allDynamic->where('status', 'draft')->count(),
            'submitted'=> $this->projects->whereIn('status', ['submitted', 'in_review'])->count() + $allDynamic->whereIn('status', ['submitted', 'in_review'])->count(),
            'approved' => $this->projects->where('status', 'approved')->count() + $allDynamic->where('status', 'approved')->count(),
            'rejected' => $this->projects->where('status', 'rejected')->count() + $allDynamic->where('status', 'rejected')->count(),
        ];

        // Build filtered view of submissions
        $filteredSubmissions = $allDynamic;
        if ($this->activeFilter) {
            if ($this->activeFilter === 'submitted') {
                $filteredSubmissions = $allDynamic->whereIn('status', ['submitted', 'in_review']);
            } else {
                $filteredSubmissions = $allDynamic->where('status', $this->activeFilter);
            }
        }
        if ($this->searchQuery) {
            $q = strtolower($this->searchQuery);
            $filteredSubmissions = $filteredSubmissions->filter(fn($s) =>
                str_contains(strtolower($s['form_title'] ?? ''), $q) ||
                str_contains(strtolower($s['programe_name'] ?? ''), $q)
            );
        }

        return view('livewire.front.dashboard.dashboard', [
            'stats'                   => $stats,
            'filteredSubmissions'     => $filteredSubmissions->values()->toArray(),
            'projectInsights'         => $projectInsights->toArray(),
            'projectEligibilityStats' => $projectEligibilityStats,
            'candidateAge'            => $candidateAge,
        ]);
    }
}