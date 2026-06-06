<?php

namespace App\Livewire\Front\Programe;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\DynamicFormSubmission;
use App\Models\ProjectsList;
use App\Models\CandidatFormulaireOrder;
use App\Services\ProjectEligibilityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
class SubmissionList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $activeTab = 'all'; // 'all' or 'project'

    protected ProjectEligibilityService $eligibilityService;

    public function boot(ProjectEligibilityService $eligibilityService): void
    {
        $this->eligibilityService = $eligibilityService;
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        $candidat = Auth::guard('candidat')->user();
        if (!$candidat) {
            abort(403);
        }
        $candidatId = $candidat->id;

        // 1. Get all active programs/projects and filter to count only those that are ELIGIBLE for this candidate
        $projects = ProjectsList::where('is_active', true)
            ->with(['formulaires' => function($q) {
                $q->where('programe_formulaire.status', 'active');
            }])
            ->get();

        $eligibleProjectIds = [];
        foreach ($projects as $project) {
            $check = $this->eligibilityService->evaluate($candidat, $project);
            if ($check['eligible']) {
                $eligibleProjectIds[] = $project->id;
            }
        }

        // 2. Query dynamic form submissions of the candidate belonging to eligible projects
        $submissionsQuery = DynamicFormSubmission::with(['programe', 'form', 'reviewer'])
            ->where('candidat_id', $candidatId)
            ->whereIn('programe_id', $eligibleProjectIds);

        if ($this->search) {
            $submissionsQuery->where(function ($q) {
                $q->whereHas('programe', function($pq) {
                    $pq->where('project_name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                })->orWhereHas('form', function($fq) {
                    $fq->where('title', 'like', '%' . $this->search . '%');
                });
            });
        }


        if ($this->statusFilter) {
            $submissionsQuery->where('status', $this->statusFilter);
        }

        $allSubmissions = $submissionsQuery->orderByDesc('updated_at')->get();
        // Load custom orders mapping for dynamic form redirect link
        $customOrders = CandidatFormulaireOrder::where('candidat_id', $candidatId)
            ->whereIn('programe_id', $eligibleProjectIds)
            ->get()
            ->groupBy('programe_id');

            $rows = $allSubmissions->map(function ($sub) use ($projects, $customOrders) {
            $statusLabel = $sub->status_label;
            $statusColor = $sub->status_badge_color;

            $hexColor = '#6b7280';
            if ($statusColor === 'blue')       $hexColor = '#2563eb';
            elseif ($statusColor === 'green')  $hexColor = '#16a34a';
            elseif ($statusColor === 'yellow') $hexColor = '#d97706';
            elseif ($statusColor === 'purple') $hexColor = '#9333ea';
            elseif ($statusColor === 'red')    $hexColor = '#dc2626';

            // Resolve effective order mapping of form inside project list
            $effectiveOrder = 1;
            if ($sub->form && $sub->programe_id) {
                $pId = $sub->programe_id;
                $p = $projects->firstWhere('id', $pId);
                if ($p) {
                    $f = $p->formulaires->firstWhere('id', $sub->dynamic_form_id);
                    if ($f) {
                        $effectiveOrder = (int) $f->pivot->order;
                        if (isset($customOrders[$pId])) {
                            $customOrd = $customOrders[$pId]->firstWhere('formulaire_id', $sub->dynamic_form_id);
                            if ($customOrd) {
                                $effectiveOrder = (int) $customOrd->order;
                            }
                        }
                    }
                }
            }
            if(isset($_GET['debug'])) {
                $allSubmissions = DynamicFormSubmission::all();
                echo '<pre>';
                print_r($allSubmissions[0]->form);
                echo '</pre>';
                exit;
            }

            return [
                'id'           => $sub->id,
                'project_id'   => $sub->programe_id,
                'project_name' => $sub->programe?->project_name,
                'project_icon' => $sub->programe?->icon ?: 'ri-briefcase-4-line',
                'project_color'=> $sub->programe?->color ?: '#2f5496',
                'form_name'    => $sub->programe?->form->title ?? $sub->form?->title ?? 'Formulaire Sans Titre',
                'form_slug'    => $sub->form->slug,
                'form_icon'    => $sub->programe?->icon ?: 'ri-file-text-line',
                'form_color'   => $sub->programe?->color ?: '#3b82f6',
                'order'        => $effectiveOrder,
                'description'  => Str::limit(strip_tags((string) ($sub->form->introduction ?? $sub->form?->introduction ?? '')), 120),
                'submitted_at' => $sub->created_at?->format('d/m/Y'),
                'updated_at'   => $sub->updated_at?->format('d/m/Y'),
                'reviewer_name'=> $sub->reviewer?->name ?? ($sub->reviewer?->nom ? ($sub->reviewer->nom . ' ' . $sub->reviewer->prenom) : '-'),
                'status_label' => $statusLabel,
                'status_color' => $hexColor,
                'status'       => $sub->status,
                'is_draft'     => $sub->status === 'draft',
            ];
        });

        $grouped = [];
        $isArabic = str_starts_with(app()->getLocale(), 'ar');
        foreach ($projects as $project) {
            if (!in_array($project->id, $eligibleProjectIds)) {
                continue;
            }

            // Find any matched submissions for this project
            $projectSubmissions = $rows->where('project_id', $project->id)->values()->toArray();

            // We only show projects that have at least one submission (either draft or submitted)
            if (!empty($projectSubmissions)) {
                $grouped[] = [
                    'id'          => $project->id,
                    'name'        => $project->project_name,
                    'description' => Str::limit(strip_tags((string) $project->description), 120),
                    'icon'        => $project->icon ?: 'ri-briefcase-4-line',
                    'color'       => $project->color ?: '#2f5496',
                    'forms'       => $projectSubmissions,
                ];
            }
        }

        // Stats collected over eligible dynamic form submissions
        $total        = $allSubmissions->count();
        $approved     = $allSubmissions->where('status', 'approved')->count();
        $inReview     = $allSubmissions->whereIn('status', ['submitted', 'in_review'])->count();
        $drafts       = $allSubmissions->where('status', 'draft')->count();

        $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;

        $state_card = [
            'Total Submissions' => [
                'label' =>  $tr('Total Formulaires', 'إجمالي الاستمارات'),
                'icon' => 'ri-file-list-3-line',
                'data' => $total,
                'bg_color' => 'bg-[#3b82f615]',
                'icon_color' => 'text-[#2563eb]',
            ],
            'approved' => [
                'label' => $tr('Formulaires Approuvés', 'الاستمارات المقبولة'),
                'icon' => 'ri-checkbox-circle-line',
                'data' => $approved,
                'bg_color' => 'bg-[#10b98115]',
                'icon_color' => 'text-[#059669]',
            ],
            'review' => [
                'label' => $tr('Formulaires En Révision', 'استمارات قيد المراجعة'),
                'icon' => 'ri-time-line',
                'data' => $inReview,
                'bg_color' => 'bg-[#8b5cf615]',
                'icon_color' => 'text-[#7c3aed]',
            ],
            'draft' => [
                'label' => $tr('Brouillons', 'المسودات'),
                'icon' => 'ri-edit-line',
                'data' => $drafts,
                'bg_color' => 'bg-[#f59e0b15]',
                'icon_color' => 'text-[#d97706]',
            ],
        ];

        // Pagination
        $page      = $this->getPage();
        $perPage   = 10;
        $paginated = $rows->values()->forPage($page, $perPage);

        return view('livewire.front.programe.submission-list', [
            'state_card' => $state_card,
            'rows'        => $paginated,
            'grouped'     => $grouped,
            'total'       => $total,
            'totalPages'  => (int) ceil($rows->count() / $perPage),
            'currentPage' => $page,
            'perPage'     => $perPage,
        ]);
    }
}
