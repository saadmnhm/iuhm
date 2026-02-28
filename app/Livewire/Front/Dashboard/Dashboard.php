<?php

namespace App\Livewire\Front\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\FormSubmissionService;
use App\Models\Candidat;
use App\Models\ProgrameList;
use App\Models\DynamicFormSubmission;
use Illuminate\Support\Facades\Auth;

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

    public function boot(FormSubmissionService $formSubmissionService)
    {
        $this->formSubmissionService = $formSubmissionService;
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
        $this->dynamicSubmissions = DynamicFormSubmission::with(['form', 'programe'])
            ->where('candidat_id', $this->candidat->id)
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($sub) {
                // Get order from pivot table if project-based
                $order = 1;
                if ($sub->programe_id && $sub->programe) {
                    $pivot = \Illuminate\Support\Facades\DB::table('programe_formulaire')
                        ->where('programe_id', $sub->programe_id)
                        ->where('formulaire_id', $sub->dynamic_form_id)
                        ->first();
                    $order = $pivot->order ?? 1;
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

        $programe_list = ProgrameList::all();

        return view('livewire.front.dashboard.dashboard', [
            'stats'               => $stats,
            'programe_list'       => $programe_list,
            'filteredSubmissions' => $filteredSubmissions->values()->toArray(),
        ]);
    }
}