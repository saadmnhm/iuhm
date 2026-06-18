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
use Illuminate\Support\Facades\Log;

class ProjectsCreate extends Component
{
    use WithFileUploads, WithPagination;

    protected $listeners = ['reorderFormulaires'];

    public ?int $programeId = null;
    public string $project_name = '';
    public string $status = 'Active';
    public string $description = ''; // Corps du texte (HTML)
    public string $excerpt = '';     // Résumé / Introduction
    public string $slug = '';
    public string $icon = 'ri-file-list-3-line';
    public string $color = '#2f5496';
    public string $bg_color = '#ffffff';
    public $min_age = null;
    public $max_age = null;
    public array $allowed_address_id = [];
    public array $allowed_location_ids = []; 
    public array $candidature_types = [];
    public string $newCandidatureType = '';
    public array $crit_sector = [];

    public $form_attached_id = null;
    public int $sort_order = 0;
    public $is_active = 1;
    public $created_by = null;

    // Logos / Images
    public ?string $existingLogo1 = null;
    public ?string $existingLogo2 = null;
    public ?string $existingLogo3 = null;

    public $logo1 = null;
    public $logo2 = null;
    public $logo3 = null;

    // Aliases li khaddamin f l-Frontend l-jdid
    public $title = '';
    public $content = '';
    public $image = null;
    public $newImage = null;

    public string $locationRegionFilter = '';
    public string $locationCityFilter = '';
    public string $locationSearch = '';

    public ?int $successProjectId = null;
    public string $errorMessage = '';
    public array $errorDetails = [];

    public array $availableFormulaires = [];
    public array $attachedFormulaires = [];
    public $selectedFormulaire = null;
    public int $formulaireOrder = 1;
    public string $formulaireStatus = 'active';
    public bool $formulaireRequired = true;
    public string $formulaireUnlockStatus = 'approved';

    public bool $isEditing = false;

    // Keep old/new property aliases in sync while the Blade is being migrated.
    public function updatedTitle($value) { $this->project_name = $value; }
    public function updatedContent($value) { $this->description = $value; }
    public function updatedNewImage($value) { $this->logo1 = $value; }
    public function updatedProjectName($value) { $this->title = $value; }
    public function updatedDescription($value) { $this->content = $value; }

    protected function rules(): array
    {
        return [
            'project_name'         => 'required|string|max:255',
            'description'          => 'required|string',
            'excerpt'              => 'nullable|string|max:250',
            'icon'                 => 'required|string|max:255',
            'color'                => 'nullable|string|max:7',
            'bg_color'             => 'nullable|string|max:7',
            'min_age'              => 'required|integer|min:0|max:120',
            'max_age'              => 'required|integer|min:0|max:120|gte:min_age',
            'allowed_address_id'   => 'nullable|array',
            'allowed_location_ids' => 'nullable|array',
            'candidature_types'    => 'nullable|array',
            'crit_sector'          => 'nullable|array',
            'form_attached_id'     => 'nullable|integer',
            'sort_order'           => 'nullable|integer',
            'is_active'            => 'required',
            'logo1'                => $this->programeId
                ? 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048'
                : 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'logo2'                => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'logo3'                => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'created_by'           => 'nullable|integer|exists:users,id',
        ];
    }

    protected function messages(): array
    {
        return [
            'project_name.required' => 'Le nom du projet est obligatoire.',
            'description.required'  => 'La description est obligatoire.',
            'icon.required'         => "L'icône est obligatoire.",
            'min_age.required'      => "L'âge minimum est obligatoire.",
            'min_age.integer'       => "L'âge minimum doit être un entier.",
            'max_age.required'      => "L'âge maximum est obligatoire.",
            'max_age.integer'       => "L'âge maximum doit être un entier.",
            'max_age.gte'           => "L'âge maximum doit être supérieur ou égal à l'âge minimum.",
            'logo1.required'        => "L'image illustrative est obligatoire pour la création.",
            'logo1.mimes'           => "L'image doit être au format (jpg, jpeg, png, webp).",
        ];
    }

    public function mount($id = null): void
    {
        if ($id) {
            $this->programeId = (int) $id;
            $this->isEditing = false; // l-front l-jdid kayda f mode Visualisation hta y-cliquew Modifier
            $list = ProjectsList::findOrFail($id);

            $this->project_name = (string) ($list->project_name ?? '');
            $this->title        = $this->project_name;
            
            $this->description  = (string) ($list->description ?? '');
            $this->content      = $this->description;
            
            $this->excerpt      = (string) ($list->excerpt ?? ''); // Ila kant la colonne f db aw t-stora f blasa khra
            $this->slug         = (string) ($list->slug ?? '');
            $this->icon         = (string) ($list->icon ?? 'ri-file-list-3-line');
            $this->color        = (string) ($list->color ?? '#2f5496');
            $this->bg_color     = (string) ($list->bg_color ?? '#ffffff');
            $this->min_age      = $list->min_age;
            $this->max_age      = $list->max_age;
            $this->status       = (string) ($list->status ?? 'Active');
            $this->form_attached_id = $list->form_attached_id;
            $this->sort_order   = (int) ($list->sort_order ?? 0);
            $this->is_active    = $list->is_active ? 1 : 0;

            $this->existingLogo1 = $list->logo1 ?: null;
            $this->image         = $this->existingLogo1;
            $this->existingLogo2 = $list->logo2 ?: null;
            $this->existingLogo3 = $list->logo3 ?: null;

            $this->allowed_address_id = json_decode($list->allowed_address_id ?? '[]', true) ?? [];

            $rawLoc = $list->allowed_location_ids;
            $locArr = is_array($rawLoc) ? $rawLoc : (json_decode($rawLoc ?? '[]', true) ?? []);
            $this->allowed_location_ids = array_values(array_map('strval', $locArr));

            $rawCt = $list->candidature_types;
            $this->candidature_types = is_array($rawCt) ? $rawCt : (json_decode($rawCt ?? '[]', true) ?? []);

            $rawEc = $list->eligibility_criteria;
            $ec    = is_array($rawEc) ? $rawEc : (json_decode($rawEc ?? '{}', true) ?? []);
            $this->crit_sector = array_values($ec['sector'] ?? []);
        } else {
            $this->isEditing = true; // Ila dkhlo y-creezt direct, it opens in edit mode
        }

        $this->loadFormulaires();
    }

    public function toggleEdit(): void
    {
        $this->isEditing = true;
    }

    public function cancelEdit(): void
    {
        if ($this->programeId) {
            $this->mount($this->programeId); // Reset modifications
        } else {
            $this->redirect(route('admin.programe')); // wla l-blasa fin bghiti re-routage
        }
    }

    public function loadFormulaires(): void
    {
        if (!$this->programeId) {
            $this->attachedFormulaires  = [];
            $this->availableFormulaires = DynamicForm::where('is_active', true)
                ->orderBy('title')
                ->get()
                ->map(fn($f) => ['id' => $f->id, 'title' => $f->title, 'title_ar' => $f->title_ar])
                ->toArray();
            return;
        }

        $programe = ProjectsList::findOrFail($this->programeId);

        $this->attachedFormulaires = $programe->formulaires()
            ->get()
            ->map(fn($form) => [
                'id'               => $form->id,
                'title'            => $form->title,
                'title_ar'         => $form->title_ar,
                'order'            => $form->pivot->order,
                'status'           => $form->pivot->status,
                'is_required'      => (bool) $form->pivot->is_required,
                'unlock_on_status' => $form->pivot->unlock_on_status ?? 'approved',
            ])
            ->toArray();

        $attachedIds = collect($this->attachedFormulaires)->pluck('id')->toArray();

        $this->availableFormulaires = DynamicForm::whereNotIn('id', $attachedIds)
            ->where('is_active', true)
            ->orderBy('title')
            ->get()
            ->map(fn($f) => ['id' => $f->id, 'title' => $f->title, 'title_ar' => $f->title_ar])
            ->toArray();
    }

    public function attachFormulaire(?int $formulaireId = null): void
    {
        if ($formulaireId) {
            $this->selectedFormulaire = $formulaireId;
            $this->formulaireOrder = count($this->attachedFormulaires) + 1;
            $this->formulaireStatus = $this->formulaireStatus ?: 'active';
            $this->formulaireRequired = true;
            $this->formulaireUnlockStatus = $this->formulaireUnlockStatus ?: 'approved';
        }

        $this->validate([
            'selectedFormulaire'     => 'required|exists:dynamic_forms,id',
            'formulaireOrder'        => 'required|integer|min:1',
            'formulaireStatus'       => 'required|in:active,inactive,draft',
            'formulaireUnlockStatus' => 'required|in:submitted,in_review,approved',
        ]);

        if (!$this->programeId) {
            session()->flash('error', "Enregistrez d'abord le projet avant d'attacher des formulaires.");
            return;
        }

        $programe = ProjectsList::findOrFail($this->programeId);

        if ($programe->formulaires()->wherePivot('formulaire_id', $this->selectedFormulaire)->exists()) {
            session()->flash('error', 'Ce formulaire est déjà attaché à ce projet.');
            return;
        }

        $programe->formulaires()->attach($this->selectedFormulaire, [
            'order'            => $this->formulaireOrder,
            'status'           => $this->formulaireStatus,
            'is_required'      => $this->formulaireRequired,
            'unlock_on_status' => $this->formulaireUnlockStatus,
        ]);

        $this->normalizeFormulaireOrders($programe);
        $this->loadFormulaires();
        $this->selectedFormulaire = null;
        $this->formulaireOrder = count($this->attachedFormulaires) + 1;
        $this->formulaireStatus = 'active';
        $this->formulaireRequired = true;
        $this->formulaireUnlockStatus = 'approved';
        session()->flash('message', 'Formulaire attaché avec succès !');
    }

    public function reorderFormulaires(array $orderedIds): void
    {
        if (!$this->programeId) return;

        $programe = ProjectsList::findOrFail($this->programeId);
        $attachedIds = $programe->formulaires()
            ->pluck('dynamic_forms.id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $orderedIds = collect($orderedIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => in_array($id, $attachedIds, true))
            ->unique()
            ->values();

        foreach ($orderedIds as $index => $id) {
            $programe->formulaires()->updateExistingPivot((int) $id, ['order' => $index + 1]);
        }

        collect($attachedIds)
            ->diff($orderedIds)
            ->values()
            ->each(function ($id, $index) use ($programe, $orderedIds) {
                $programe->formulaires()->updateExistingPivot((int) $id, [
                    'order' => $orderedIds->count() + $index + 1,
                ]);
            });

        $this->loadFormulaires();
    }

    public function selectFormulaire(int $formulaireId): void
    {
        $this->loadFormulaires();
        $this->selectedFormulaire     = $formulaireId;
        $this->formulaireOrder        = count($this->attachedFormulaires) + 1;
        $this->formulaireStatus       = 'active';
        $this->formulaireRequired     = true;
        $this->formulaireUnlockStatus = 'approved';
    }

    public function detachFormulaire(int $formulaireId): void
    {
        if (!$this->programeId) return;
        $programe = ProjectsList::findOrFail($this->programeId);
        $programe->formulaires()->detach($formulaireId);
        $this->normalizeFormulaireOrders($programe);
        $this->loadFormulaires();
        session()->flash('message', 'Formulaire détaché avec succès !');
    }

    public function updateFormulaireOrder(int $formulaireId, $newOrder): void
    {
        if (!$this->programeId) return;
        $programe = ProjectsList::findOrFail($this->programeId);
        $programe->formulaires()->updateExistingPivot($formulaireId, ['order' => max(1, (int) $newOrder)]);
        $this->normalizeFormulaireOrders($programe);
        $this->loadFormulaires();
    }

    public function updateFormulaireStatus(int $formulaireId, string $newStatus): void
    {
        if (!$this->programeId) return;
        ProjectsList::findOrFail($this->programeId)
            ->formulaires()
            ->updateExistingPivot($formulaireId, ['status' => $newStatus]);
        $this->loadFormulaires();
    }

    public function toggleFormulaireRequired(int $formulaireId): void
    {
        if (!$this->programeId) return;
        $formulaire  = collect($this->attachedFormulaires)->firstWhere('id', $formulaireId);
        $newRequired = !($formulaire['is_required'] ?? false);
        ProjectsList::findOrFail($this->programeId)
            ->formulaires()
            ->updateExistingPivot($formulaireId, ['is_required' => $newRequired]);
        $this->loadFormulaires();
    }

    private function normalizeFormulaireOrders(ProjectsList $programe): void
    {
        $programe->formulaires()
            ->get()
            ->sortBy(fn ($form) => [(int) $form->pivot->order, (int) $form->id])
            ->values()
            ->each(function ($form, int $index) use ($programe) {
                $programe->formulaires()->updateExistingPivot($form->id, ['order' => $index + 1]);
            });
    }

    public function selectIcon(string $iconClass): void
    {
        $this->icon = $iconClass;
    }

    public function addCandidatureType(): void
    {
        $value = trim($this->newCandidatureType);
        if ($value === '') return;

        $types = collect($this->candidature_types)
            ->map(fn($t) => trim((string) $t))
            ->filter()
            ->values();

        if (!$types->contains($value)) {
            $types->push($value);
        }

        $this->candidature_types  = $types->values()->toArray();
        $this->newCandidatureType = '';
    }

    public function removeCandidatureType(string $type): void
    {
        $this->candidature_types = collect($this->candidature_types)
            ->reject(fn($t) => trim((string) $t) === trim($type))
            ->values()
            ->toArray();
    }

    public function updatedLocationRegionFilter(): void
    {
        $this->locationCityFilter = '';
    }

    public function removeSelectedLocation(int $id): void
    {
        $this->allowed_location_ids = collect($this->allowed_location_ids)
            ->reject(fn($x) => (string) $x === (string) $id)
            ->values()
            ->toArray();
    }

    public function save(): void
    {
        $this->saveProjectList();
    }

    public function saveProjectList(): void
    {
        $this->resetValidation();
        $this->errorDetails   = [];
        $this->errorMessage   = '';

        try {
            $this->project_name = trim((string) ($this->project_name ?: $this->title));
            $this->description = trim((string) ($this->description ?: $this->content));
            $this->title = $this->project_name;
            $this->content = $this->description;

            $this->validate();

            $this->slug = Str::slug($this->project_name);
            $createdBy = Auth::id();

            $locationIds = array_values(
                array_unique(
                    array_map('intval', array_filter($this->allowed_location_ids ?? []))
                )
            );

            $data = [
                'project_name'         => $this->project_name,
                'status'               => $this->status ?? 'Active',
                'description'          => $this->description,
                'excerpt'              => $this->excerpt,
                'slug'                 => $this->slug,
                'icon'                 => $this->icon ?: 'ri-file-list-3-line',
                'color'                => $this->color ?: '#2f5496',
                'bg_color'             => $this->bg_color ?: '#ffffff',
                'min_age'              => (int) $this->min_age,
                'max_age'              => (int) $this->max_age,
                'allowed_address_id'   => json_encode($this->allowed_address_id ?? []),
                'allowed_location_ids' => json_encode($locationIds),
                'candidature_types'    => array_values(
                    array_unique(
                        array_filter(array_map('trim', $this->candidature_types ?? []))
                    )
                ),
                'eligibility_criteria' => [
                    'sector' => array_values(array_filter($this->crit_sector ?? [])),
                ],
                'form_attached_id'     => $this->form_attached_id,
                'sort_order'           => $this->sort_order ?? 0,
                'is_active'            => (bool) $this->is_active,
                'created_by'           => $createdBy,
            ];

            if ($this->logo1 instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $data['logo1'] = $this->logo1->store('project-logos', 'uploads');
            } elseif ($this->programeId && $this->existingLogo1) {
                $data['logo1'] = $this->existingLogo1;
            }

            if ($this->logo2 instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $data['logo2'] = $this->logo2->store('project-logos', 'uploads');
            } elseif ($this->programeId && $this->existingLogo2) {
                $data['logo2'] = $this->existingLogo2;
            }

            if ($this->logo3 instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $data['logo3'] = $this->logo3->store('project-logos', 'uploads');
            } elseif ($this->programeId && $this->existingLogo3) {
                $data['logo3'] = $this->existingLogo3;
            }

            if ($this->programeId) {
                $project = ProjectsList::findOrFail($this->programeId);
                $project->update($data);

                $this->existingLogo1 = $project->fresh()->logo1 ?: null;
                $this->image         = $this->existingLogo1;
                $this->logo1         = null;
                $this->newImage      = null;

                AdminActivityLog::log('programme_updated', "Updated programme: {$project->project_name}", ProjectsList::class, $project->id);
                session()->flash('success', 'Projet mis à jour avec succès.');
                $this->isEditing = false; // Rje3 l-mode normal après modification
            } else {
                $project = ProjectsList::create($data);
                AdminActivityLog::log('programme_created', "Created programme: {$project->project_name}", ProjectsList::class, $project->id);
                session()->flash('success', 'Projet créé avec succès.');
                $this->redirect(route('admin.programe.edit', $project->id), navigate: true);
            }

            $this->successProjectId = $project->id;

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            $this->errorDetails = collect($e->errors())->flatten()->toArray();
            $this->errorMessage = 'Veuillez corriger les erreurs de validation.';
            $this->dispatch('error-toast', $this->errorMessage); // Lansi t-toast l lfront jdid
        } catch (\Exception $e) {
            Log::error('Error saving project: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->errorDetails = [$e->getMessage()];
            $this->errorMessage = "Une erreur est survenue lors de l'enregistrement.";
            $this->dispatch('error-toast', $this->errorMessage);
        }
    }

    public function render()
    {
        $regions = MoroccoLocation::query()->select('region')->distinct()->orderBy('region')->pluck('region');

        $cities = MoroccoLocation::query()
            ->when($this->locationRegionFilter, fn($q) => $q->where('region', $this->locationRegionFilter))
            ->select('city')->distinct()->orderBy('city')->pluck('city');

        $locations = MoroccoLocation::query()
            ->when($this->locationRegionFilter, fn($q) => $q->where('region', $this->locationRegionFilter))
            ->when($this->locationCityFilter,   fn($q) => $q->where('city', $this->locationCityFilter))
            ->when($this->locationSearch, function ($q) {
                $search = trim($this->locationSearch);
                $q->where(function ($sub) use ($search) {
                    $sub->where('region', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('prefecture', 'like', "%{$search}%");
                });
            })
            ->orderBy('region')->orderBy('city')->orderBy('prefecture')->get();

        $selectedLocIds = array_map('intval', $this->allowed_location_ids ?: [0]);
        $selectedLocations = MoroccoLocation::query()
            ->whereIn('id', count($selectedLocIds) ? $selectedLocIds : [0])
            ->orderBy('region')->orderBy('city')->orderBy('prefecture')->get();

        $attachedIds = collect($this->attachedFormulaires)->pluck('id')->toArray();
        $this->availableFormulaires = DynamicForm::whereNotIn('id', $attachedIds)
            ->where('is_active', true)->orderBy('title')->get()
            ->map(fn($f) => ['id' => $f->id, 'title' => $f->title, 'title_ar' => $f->title_ar])->toArray();

        return view('livewire.admin.projects.create_project', [
            'regions'              => $regions,
            'cities'               => $cities,
            'locations'            => $locations,
            'selectedLocations'    => $selectedLocations,
            'availableFormulaires' => $this->availableFormulaires,
            'attachedFormulaires'  => $this->attachedFormulaires,
        ])->layout('layouts.admin', [
            'header' => $this->programeId ? 'Modifier le Projet' : 'Créer un nouveau Projet',
        ]);
    }
}
