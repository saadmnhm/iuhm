<?php
namespace App\Livewire\Admin\Programe;

use App\Models\AdminActivityLog;
use Livewire\Component;
use App\Models\MoroccoLocation;
use App\Models\ProgrameList;
use App\Models\DynamicForm;
use App\Models\Candidat;
use Livewire\WithPagination;

class ProgrameEdit extends Component{

    public $programeId;
    public $project_name;
    public $status = 'Active';
    public $description;
    public $icon = 'ri-file-list-3-line';
    public $color = '#2f5496';
    public $bg_color = '#ffffff';
    public $min_age;
    public $max_age;
    public $allowed_address_id = [];
    public $allowed_location_ids = [];
    public $candidature_types = [];
    public string $newCandidatureType = '';
    public bool $showLocationModal = false;
    public $locationRegionFilter = '';
    public $locationCityFilter = '';
    public $locationSearch = '';
    
    // Formulaire management
    public $showFormulaireModal = false;
    public $availableFormulaires = [];
    public $attachedFormulaires = [];
    public $selectedFormulaire = null;
    public $formulaireOrder = 1;
    public $formulaireStatus = 'active';
    public $formulaireRequired = true;
    public $formulaireUnlockStatus = 'approved';
    
    public function mount($id)
    {
        $this->programeId = $id;
        $programe = ProgrameList::findOrFail($id);
        
        $this->project_name = $programe->project_name;
        $this->status = $programe->status ?? 'Active';
        $this->description = $programe->description;
        $this->min_age = $programe->min_age;
        $this->max_age = $programe->max_age;
        
        $this->allowed_address_id = $programe->allowed_address_id 
            ? (is_array($programe->allowed_address_id) 
                ? $programe->allowed_address_id 
                : json_decode($programe->allowed_address_id, true)) 
            : [];

        $this->allowed_location_ids = is_array($programe->allowed_location_ids)
            ? $programe->allowed_location_ids
            : (json_decode($programe->allowed_location_ids ?? '[]', true) ?? []);

        $this->candidature_types = is_array($programe->candidature_types)
            ? $programe->candidature_types
            : (json_decode($programe->candidature_types ?? '[]', true) ?? []);

        $this->icon = $programe->icon ?? 'ri-file-list-3-line';
        $this->color = $programe->color ?? '#2f5496';
        $this->bg_color = $programe->bg_color ?? '#ffffff';
            
        $this->loadFormulaires();
    }
    
    public function loadFormulaires()
    {
        $programe = ProgrameList::findOrFail($this->programeId);
        $this->attachedFormulaires = $programe->formulaires()->get()->map(function($form) {
            return [
                'id' => $form->id,
                'title' => $form->title,
                'title_ar' => $form->title_ar,
                'order' => $form->pivot->order,
                'status' => $form->pivot->status,
                'is_required' => $form->pivot->is_required,
                'unlock_on_status' => $form->pivot->unlock_on_status ?? 'approved',
                'has_introduction' => $form->has_introduction,
            ];
        })->toArray();
        
        // Get all available formulaires that are not yet attached
        $attachedIds = collect($this->attachedFormulaires)->pluck('id')->toArray();
        $this->availableFormulaires = DynamicForm::whereNotIn('id', $attachedIds)
            ->where('is_active', true)
            ->get()
            ->map(function($form) {
                return [
                    'id' => $form->id,
                    'title' => $form->title,
                    'title_ar' => $form->title_ar,
                ];
            })->toArray();
    }
    
    public function openFormulaireModal()
    {
        $this->loadFormulaires();
        $this->showFormulaireModal = true;
        $this->selectedFormulaire = null;
        $this->formulaireOrder = count($this->attachedFormulaires) + 1;
        $this->formulaireStatus = 'active';
        $this->formulaireRequired = true;
        $this->formulaireUnlockStatus = 'approved';
    }
    
    public function closeFormulaireModal()
    {
        $this->showFormulaireModal = false;
        $this->selectedFormulaire = null;
    }
    
    public function attachFormulaire()
    {
        $this->validate([
            'selectedFormulaire' => 'required|exists:dynamic_forms,id',
            'formulaireOrder' => 'required|integer|min:1',
            'formulaireStatus' => 'required|in:active,inactive,draft',
            'formulaireUnlockStatus' => 'required|in:submitted,in_review,approved',
        ]);
        
        $programe = ProgrameList::findOrFail($this->programeId);
        
        // Check if already attached
        if ($programe->formulaires()->where('formulaire_id', $this->selectedFormulaire)->exists()) {
            session()->flash('error', 'Ce formulaire est déjà attaché à ce projet.');
            return;
        }
        
        $programe->formulaires()->attach($this->selectedFormulaire, [
            'order' => $this->formulaireOrder,
            'status' => $this->formulaireStatus,
            'is_required' => $this->formulaireRequired,
            'unlock_on_status' => $this->formulaireUnlockStatus,
        ]);

        AdminActivityLog::log(
            'formulaire_attached',
            "Attached formulaire ID {$this->selectedFormulaire} to programme: {$programe->project_name}",
            ProgrameList::class,
            $programe->id
        );
        
        $this->loadFormulaires();
        $this->closeFormulaireModal();
        session()->flash('message', 'Formulaire attaché avec succès!');
    }
    
    public function detachFormulaire($formulaireId)
    {
        $programe = ProgrameList::findOrFail($this->programeId);
        $programe->formulaires()->detach($formulaireId);

        AdminActivityLog::log(
            'formulaire_detached',
            "Detached formulaire ID {$formulaireId} from programme: {$programe->project_name}",
            ProgrameList::class,
            $programe->id
        );

        $this->loadFormulaires();
        session()->flash('message', 'Formulaire détaché avec succès!');
    }
    
    public function updateFormulaireOrder($formulaireId, $newOrder)
    {
        $programe = ProgrameList::findOrFail($this->programeId);
        $programe->formulaires()->updateExistingPivot($formulaireId, ['order' => $newOrder]);
        $this->loadFormulaires();
    }
    
    public function updateFormulaireStatus($formulaireId, $newStatus)
    {
        $programe = ProgrameList::findOrFail($this->programeId);
        $programe->formulaires()->updateExistingPivot($formulaireId, ['status' => $newStatus]);
        $this->loadFormulaires();
    }
    
    public function toggleFormulaireRequired($formulaireId)
    {
        $programe = ProgrameList::findOrFail($this->programeId);
        $formulaire = collect($this->attachedFormulaires)->firstWhere('id', $formulaireId);
        $newRequired = !$formulaire['is_required'];
        $programe->formulaires()->updateExistingPivot($formulaireId, ['is_required' => $newRequired]);
        $this->loadFormulaires();
    }

    public function updateFormulaireUnlockStatus($formulaireId, $unlockStatus)
    {
        if (!in_array($unlockStatus, ['submitted', 'in_review', 'approved'])) {
            return;
        }

        $programe = ProgrameList::findOrFail($this->programeId);
        $programe->formulaires()->updateExistingPivot($formulaireId, ['unlock_on_status' => $unlockStatus]);
        $this->loadFormulaires();
    }

    public function selectIcon(string $iconClass): void
    {
        $this->icon = $iconClass;
    }

    public function save()
    {
        $this->validate([
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
        ]);

        $programe = ProgrameList::findOrFail($this->programeId);
        
        $programe->update([
            'project_name' => $this->project_name,
            'status' => $this->status,
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color,
            'bg_color' => $this->bg_color,
            'min_age' => $this->min_age,
            'max_age' => $this->max_age,
            'allowed_address_id' => json_encode($this->allowed_address_id),
            'allowed_location_ids' => array_values(array_unique(array_map('intval', $this->allowed_location_ids ?? []))),
            'candidature_types' => array_values(array_unique(array_filter(array_map('trim', $this->candidature_types ?? [])))),
        ]);

        AdminActivityLog::log(
            'programme_updated',
            "Updated programme: {$programe->project_name}",
            ProgrameList::class,
            $programe->id
        );

        session()->flash('message', 'Programme mis à jour avec succès!');
        
        return redirect()->route('admin.programe');
    }

    public function openLocationModal(): void
    {
        $this->locationRegionFilter = '';
        $this->locationCityFilter = '';
        $this->locationSearch = '';
        $this->showLocationModal = true;
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

        return view('livewire.admin.programe.edit_project', compact('regions', 'cities', 'locations', 'selectedLocations'))
            ->layout('layouts.admin', ['header' => 'Edit Project']);
    }
}