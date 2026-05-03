<?php

namespace App\Livewire\Admin\Address;

use Livewire\Component;
use App\Models\MoroccoLocation;
use App\Models\AdminActivityLog;
use Livewire\WithPagination;

class AddressManager extends Component
{
    use WithPagination;

    public $modalOpen = false;
    public $editMode = false;
    public $locationId;
    
    // Form fields
    public $region;
    public $city;
    public $prefecture;
    
    // Search
    public $search = '';
    // Filters
    public $regionFilter = 'all';
    public $cityFilter = 'all';
    public $prefectureFilter = 'all';

    // Options for selects
    public $regions = [];
    public $cities = [];
    public $prefectures = [];
    
    protected $rules = [
        'region' => 'required|string|max:150',
        'city' => 'required|string|max:100',
        'prefecture' => 'required|string|max:150',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRegionFilter()
    {
        $this->resetPage();
    }

    public function updatingCityFilter()
    {
        $this->resetPage();
    }

    public function updatingPrefectureFilter()
    {
        $this->resetPage();
    }

    public function applyFilters()
    {
        // noop — triggers re-render with current filter values
        $this->resetPage();
    }

    public function openModal()
    {
        $this->reset(['region', 'city', 'prefecture', 'locationId', 'editMode']);
        $this->modalOpen = true;
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->reset(['region', 'city', 'prefecture', 'locationId', 'editMode']);
        $this->resetValidation();
    }

    public function edit($id)
    {
        $location = MoroccoLocation::findOrFail($id);
        
        $this->locationId = $id;
        $this->region = $location->region;
        $this->city = $location->city;
        $this->prefecture = $location->prefecture;
        $this->editMode = true;
        $this->modalOpen = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $location = MoroccoLocation::findOrFail($this->locationId);
            $location->update([
                'region' => $this->region,
                'city' => $this->city,
                'prefecture' => $this->prefecture,
            ]);

            AdminActivityLog::log(
                'location_updated',
                "Updated location: {$location->region} / {$location->city} / {$location->prefecture}",
                MoroccoLocation::class,
                $location->id
            );
            
            $this->dispatch('alert', [
                'type' => 'success',
                'title' => 'Success',
                'message' => 'Location updated successfully!'
            ]);
        } else {
            $location = MoroccoLocation::create([
                'region' => $this->region,
                'city' => $this->city,
                'prefecture' => $this->prefecture,
            ]);

            AdminActivityLog::log(
                'location_created',
                "Created location: {$location->region} / {$location->city} / {$location->prefecture}",
                MoroccoLocation::class,
                $location->id
            );
            
            $this->dispatch('alert', [
                'type' => 'success',
                'title' => 'Success',
                'message' => 'Location created successfully!'
            ]);
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $location = MoroccoLocation::findOrFail($id);
        $location->delete();

        AdminActivityLog::log(
            'location_deleted',
            "Deleted location: {$location->region} / {$location->city} / {$location->prefecture}",
            MoroccoLocation::class,
            $location->id
        );
        
        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => 'Location deleted successfully!'
        ]);
    }

    public function render()
    {
        $query = MoroccoLocation::query()
            ->when($this->search, function($q) {
                $q->where('region', 'like', '%' . $this->search . '%')
                  ->orWhere('city', 'like', '%' . $this->search . '%')
                  ->orWhere('prefecture', 'like', '%' . $this->search . '%');
            })
            ->when($this->regionFilter && $this->regionFilter !== 'all', function($q) {
                $q->where('region', $this->regionFilter);
            })
            ->when($this->cityFilter && $this->cityFilter !== 'all', function($q) {
                $q->where('city', $this->cityFilter);
            })
            ->when($this->prefectureFilter && $this->prefectureFilter !== 'all', function($q) {
                $q->where('prefecture', $this->prefectureFilter);
            });

        $addresses = $query->orderBy('region')->orderBy('city')->orderBy('prefecture')->paginate(10);

        // Build select options
        $this->regions = MoroccoLocation::query()->select('region')->distinct()->orderBy('region')->pluck('region')->toArray();
        $this->cities = MoroccoLocation::query()->when($this->regionFilter && $this->regionFilter !== 'all', fn($q) => $q->where('region', $this->regionFilter))->select('city')->distinct()->orderBy('city')->pluck('city')->toArray();
        $this->prefectures = MoroccoLocation::query()->when($this->regionFilter && $this->regionFilter !== 'all', fn($q) => $q->where('region', $this->regionFilter))->when($this->cityFilter && $this->cityFilter !== 'all', fn($q) => $q->where('city', $this->cityFilter))->select('prefecture')->distinct()->orderBy('prefecture')->pluck('prefecture')->toArray();

        return view('livewire.admin.tools.address-manager', compact('addresses'))->layout('layouts.admin', [
                'header' => 'Manage Morocco Locations'
            ]);
    }
}
