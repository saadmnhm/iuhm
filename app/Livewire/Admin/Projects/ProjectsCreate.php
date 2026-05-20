<?php

namespace App\Livewire\Admin\Projects;

use App\Models\ProjectsList;
use App\Models\AdminActivityLog;
use App\Models\MoroccoLocation;
use App\Models\DynamicForm;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class ProjectsCreate extends Component
{
    use WithFileUploads, WithPagination;

    public ?int $programeId = null;
    public $project_name = '';
    public $status = 'Active';
    public $description = '';
    public $slug = '';
    public $icon = 'ri-file-list-3-line';
    public $color = '#2f5496';
    public $bg_color = '#ffffff';
    public $min_age = null;
    public $max_age = null;
    public $allowed_address_id = [];
    public $allowed_location_ids = [];
    public $candidature_types = [];
    public string $newCandidatureType = '';

    // Sector eligibility criteria
    public array $crit_sector = [];

    public $form_attached_id = null;
    public $sort_order = 0;
    public $is_active = true;
    public $created_by = null;
    public bool $showLocationModal = false;
    public $locationRegionFilter = '';
    public $locationCityFilter = '';
    public $locationSearch = '';
    public bool $showSuccessModal = false;
    public ?int $successProjectId = null;
    public bool $showErrorModal = false;
    public string $errorMessage = '';
    public array $errorDetails = [];

    // Formulaire management
    public bool $showFormulaireModal = false;
    public array $availableFormulaires = [];
    public array $attachedFormulaires = [];
    public $selectedFormulaire = null;
    public int $formulaireOrder = 1;
    public string $formulaireStatus = 'active';
    public bool $formulaireRequired = true;
    public string $formulaireUnlockStatus = 'approved';

    public $logo1;
    public $logo2;
    public $logo3;

    protected function rules()
    {
        return [
            'project_name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'bg_color' => 'nullable|string|max:7',
            'min_age' => 'required|integer|min:0',
            'max_age' => 'required|integer|min:0|gte:min_age',
            'allowed_address_id' => 'nullable|array',
            'allowed_location_ids' => 'nullable|array',
            'candidature_types' => 'nullable|array',
            'crit_sector' => 'nullable|array',
            'form_attached_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'logo1' => $this->programeId ? 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048' : 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'logo2' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'logo3' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'created_by' => 'nullable|integer|exists:users,id',
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->programeId = $id;
            $list = ProjectsList::findOrFail($id);
            
            $this->project_name = $list->project_name;
            $this->description = $list->description;
            $this->slug = $list->slug;
            $this->icon = $list->icon;
            $this->color = $list->color;
            $this->bg_color = $list->bg_color;
            $this->min_age = $list->min_age;
            $this->max_age = $list->max_age;
            
            // Decode JSON to array
            $this->allowed_address_id = json_decode($list->allowed_address_id, true) ?? [];
            $this->allowed_location_ids = is_array($list->allowed_location_ids)
                ? $list->allowed_location_ids
                : (json_decode($list->allowed_location_ids ?? '[]', true) ?? []);

            $this->candidature_types = is_array($list->candidature_types)
                ? $list->candidature_types
                : (json_decode($list->candidature_types ?? '[]', true) ?? []);

            // Load supplementary eligibility criteria
            $ec = is_array($list->eligibility_criteria)
                ? $list->eligibility_criteria
                : (json_decode($list->eligibility_criteria ?? '{}', true) ?? []);
            $this->crit_sector = $ec['sector'] ?? [];
            $this->status = $list->status ?? 'Active';
            $this->form_attached_id = $list->form_attached_id;
            $this->sort_order = $list->sort_order;
            $this->is_active = $list->is_active;
            $this->loadFormulaires();
        } else {
            $this->loadFormulaires();
        }
    }

    public function loadFormulaires(): void
    {
        if (!$this->programeId) {
            $this->attachedFormulaires = [];
            $this->availableFormulaires = DynamicForm::where('is_active', true)->orderBy('title')
                ->get()->map(fn($f) => ['id' => $f->id, 'title' => $f->title, 'title_ar' => $f->title_ar])->toArray();
            return;
        }
        $programe = ProjectsList::findOrFail($this->programeId);
        $this->attachedFormulaires = $programe->formulaires()->get()->map(function ($form) {
            return [
                'id'               => $form->id,
                'title'            => $form->title,
                'title_ar'         => $form->title_ar,
                'order'            => $form->pivot->order,
                'status'           => $form->pivot->status,
                'is_required'      => $form->pivot->is_required,
                'unlock_on_status' => $form->pivot->unlock_on_status ?? 'approved',
            ];
        })->toArray();
        $attachedIds = collect($this->attachedFormulaires)->pluck('id')->toArray();
        $this->availableFormulaires = DynamicForm::whereNotIn('id', $attachedIds)->where('is_active', true)
            ->orderBy('title')->get()
            ->map(fn($f) => ['id' => $f->id, 'title' => $f->title, 'title_ar' => $f->title_ar])->toArray();
    }

    public function openFormulaireModal(): void
    {
        $this->loadFormulaires();
        $this->showFormulaireModal = true;
        $this->selectedFormulaire = null;
        $this->formulaireOrder = count($this->attachedFormulaires) + 1;
        $this->formulaireStatus = 'active';
        $this->formulaireRequired = true;
        $this->formulaireUnlockStatus = 'approved';
    }

    public function closeFormulaireModal(): void
    {
        $this->showFormulaireModal = false;
        $this->selectedFormulaire = null;
    }

    public function attachFormulaire(): void
    {
        $this->validate([
            'selectedFormulaire'     => 'required|exists:dynamic_forms,id',
            'formulaireOrder'        => 'required|integer|min:1',
            'formulaireStatus'       => 'required|in:active,inactive,draft',
            'formulaireUnlockStatus' => 'required|in:submitted,in_review,approved',
        ]);
        if (!$this->programeId) {
            session()->flash('error', 'Enregistrez d\'abord le projet avant d\'attacher des formulaires.');
            return;
        }
        $programe = ProjectsList::findOrFail($this->programeId);
        if ($programe->formulaires()->where('formulaire_id', $this->selectedFormulaire)->exists()) {
            session()->flash('error', 'Ce formulaire est déjà attaché à ce projet.');
            return;
        }
        $programe->formulaires()->attach($this->selectedFormulaire, [
            'order'            => $this->formulaireOrder,
            'status'           => $this->formulaireStatus,
            'is_required'      => $this->formulaireRequired,
            'unlock_on_status' => $this->formulaireUnlockStatus,
        ]);
        $this->loadFormulaires();
        $this->closeFormulaireModal();
        session()->flash('message', 'Formulaire attaché avec succès!');
    }

    public function detachFormulaire(int $formulaireId): void
    {
        $programe = ProjectsList::findOrFail($this->programeId);
        $programe->formulaires()->detach($formulaireId);
        $this->loadFormulaires();
        session()->flash('message', 'Formulaire détaché avec succès!');
    }

    public function updateFormulaireOrder(int $formulaireId, int $newOrder): void
    {
        ProjectsList::findOrFail($this->programeId)->formulaires()->updateExistingPivot($formulaireId, ['order' => $newOrder]);
        $this->loadFormulaires();
    }

    public function updateFormulaireStatus(int $formulaireId, string $newStatus): void
    {
        ProjectsList::findOrFail($this->programeId)->formulaires()->updateExistingPivot($formulaireId, ['status' => $newStatus]);
        $this->loadFormulaires();
    }

    public function toggleFormulaireRequired(int $formulaireId): void
    {
        $formulaire = collect($this->attachedFormulaires)->firstWhere('id', $formulaireId);
        $newRequired = !$formulaire['is_required'];
        ProjectsList::findOrFail($this->programeId)->formulaires()->updateExistingPivot($formulaireId, ['is_required' => $newRequired]);
        $this->loadFormulaires();
    }

    public function selectIcon(string $iconClass): void
    {
        $this->icon = $iconClass;
    }

    public function saveProjectList()
    {
        $created_by = Auth::id();

        \Log::info('saveProjectList called');
        \Log::info('Data:', [
            'project_name' => $this->project_name,
            'description' => $this->description,
            'icon' => $this->icon,
            'min_age' => $this->min_age,
            'max_age' => $this->max_age,
            'allowed_address_id' => $this->allowed_address_id,
            'allowed_location_ids' => $this->allowed_location_ids,
            'created_by' => $this->created_by,
        ]);

        try {
            $this->validate();
            
            \Log::info('Validation passed');

            $this->slug = Str::slug($this->project_name);

            $data = [
                'project_name' => $this->project_name,
                'status' => $this->status ?? 'Active',
                'description' => $this->description,
                'slug' => $this->slug,
                'icon' => $this->icon ?? 'ri-file-list-3-line',
                'color' => $this->color ?? '#2f5496',
                'bg_color' => $this->bg_color ?? '#ffffff',
                'min_age' => $this->min_age,
                'max_age' => $this->max_age,
                'allowed_address_id' => json_encode($this->allowed_address_id ?? []),
                'allowed_location_ids' => array_values(array_unique(array_map('intval', $this->allowed_location_ids ?? []))),
                'candidature_types' => array_values(array_unique(array_filter(array_map('trim', $this->candidature_types ?? [])))),
                'eligibility_criteria' => [
                    'sector' => array_values(array_filter($this->crit_sector ?? [])),
                ],
                'form_attached_id' => $this->form_attached_id,
                'sort_order' => $this->sort_order ?? 0,
                'is_active' => $this->is_active,
                'created_by' => $created_by,
            ];

            if ($this->logo1) {
                $data['logo1'] = $this->logo1->store('project-logos', 'uploads');
            }
            if ($this->logo2) {
                $data['logo2'] = $this->logo2->store('project-logos', 'uploads');
            }
            if ($this->logo3) {
                $data['logo3'] = $this->logo3->store('project-logos', 'uploads');
            }

            \Log::info('Data to save:', $data);

            if ($this->programeId) {
                $project = ProjectsList::findOrFail($this->programeId);
                $project->update($data);
                \Log::info('Project updated', ['id' => $this->programeId]);

                AdminActivityLog::log(
                    'programme_updated',
                    "Updated programme: {$project->project_name}",
                    ProjectsList::class,
                    $project->id
                );

                $this->successProjectId = $project->id;
                $this->showSuccessModal = true;
            } else {
                $project = ProjectsList::create($data);
                \Log::info('Project created', ['id' => $project->id]);

                AdminActivityLog::log(
                    'programme_created',
                    "Created programme: {$project->project_name}",
                    ProjectsList::class,
                    $project->id
                );

                $this->successProjectId = $project->id;
                $this->showSuccessModal = true;
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            $flat = [];
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $msg) {
                    $flat[] = $msg;
                }
            }
            $this->errorDetails = $flat;
            $this->errorMessage = 'Veuillez corriger les erreurs de validation.';
            $this->showErrorModal = true;
        } catch (\Exception $e) {
            \Log::error('Error saving project: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            $this->errorDetails = [$e->getMessage()];
            $this->errorMessage = 'Une erreur est survenue lors de l\'enregistrement.';
            $this->showErrorModal = true;
        }
    }

    public function openLocationModal(): void
    {
        $this->locationRegionFilter = '';
        $this->locationCityFilter = '';
        $this->locationSearch = '';
        $this->showLocationModal = true;
    }

    public function closeErrorModal(): void
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
        $this->errorDetails = [];
    }

    public function redirectAfterSuccess()
    {
        if ($this->successProjectId) {
            return redirect()->route('programe.edit', $this->successProjectId);
        }

        return redirect()->route('programe');
    }

    public function closeLocationModal(): void
    {
        $this->showLocationModal = false;
    }

    public function updatedLocationRegionFilter(): void
    {
        $this->locationCityFilter = '';
    }

    public function removeSelectedLocation(int $id): void
    {
        $this->allowed_location_ids = collect($this->allowed_location_ids)
            ->map(fn ($x) => (int) $x)
            ->reject(fn ($x) => $x === $id)
            ->values()
            ->toArray();
    }

    public function addCandidatureType(): void
    {
        $value = trim($this->newCandidatureType);
        if ($value === '') {
            return;
        }

        $types = collect($this->candidature_types)->map(fn ($t) => trim((string) $t))->filter()->values();
        if (!$types->contains($value)) {
            $types->push($value);
        }

        $this->candidature_types = $types->values()->toArray();
        $this->newCandidatureType = '';
    }

    public function removeCandidatureType(string $type): void
    {
        $this->candidature_types = collect($this->candidature_types)
            ->reject(fn ($t) => trim((string) $t) === trim($type))
            ->values()
            ->toArray();
    }

    public function render()
    {
        $regions = MoroccoLocation::query()->select('region')->distinct()->orderBy('region')->pluck('region');

        $cities = MoroccoLocation::query()
            ->when($this->locationRegionFilter, fn ($q) => $q->where('region', $this->locationRegionFilter))
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $locations = MoroccoLocation::query()
            ->when($this->locationRegionFilter, fn ($q) => $q->where('region', $this->locationRegionFilter))
            ->when($this->locationCityFilter, fn ($q) => $q->where('city', $this->locationCityFilter))
            ->when($this->locationSearch, function ($q) {
                $search = trim($this->locationSearch);
                $q->where(function ($subQuery) use ($search) {
                    $subQuery->where('region', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('prefecture', 'like', "%{$search}%");
                });
            })
            ->orderBy('region')
            ->orderBy('city')
            ->orderBy('prefecture')
            ->get();

        $selectedLocations = MoroccoLocation::query()
            ->whereIn('id', $this->allowed_location_ids ?: [0])
            ->orderBy('region')
            ->orderBy('city')
            ->orderBy('prefecture')
            ->get();

        $availableFormulaires = DynamicForm::where('is_active', true)
            ->orderBy('title')
            ->get()
            ->map(fn($f) => ['id' => $f->id, 'title' => $f->title, 'title_ar' => $f->title_ar])
            ->toArray();

        // Sync $availableFormulaires if not loaded yet (create mode)
        if (empty($this->availableFormulaires) && empty($this->attachedFormulaires)) {
            $this->availableFormulaires = $availableFormulaires;
        }

        return view('livewire.admin.projects.create_project', [
            'regions'             => $regions,
            'cities'              => $cities,
            'locations'           => $locations,
            'selectedLocations'   => $selectedLocations,
            'availableFormulaires' => $this->availableFormulaires,
            'attachedFormulaires'  => $this->attachedFormulaires,
        ])
            ->layout('layouts.admin', [
                'header' => $this->programeId ? 'Modifier le Projet' : 'Créer un nouveau Projet'
            ]);
    }
}