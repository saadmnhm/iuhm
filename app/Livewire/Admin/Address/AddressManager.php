<?php

namespace App\Livewire\Admin\Address;

use Livewire\Component;
use App\Models\Address;
use App\Models\AdminActivityLog;
use Livewire\WithPagination;

class AddressManager extends Component
{
    use WithPagination;

    public $modalOpen = false;
    public $editMode = false;
    public $addressId;
    
    // Form fields
    public $address_line1;
    public $city;
    public $state;
    public $postal_code;
    
    // Search
    public $search = '';
    
    protected $rules = [
        'address_line1' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'nullable|string|max:100',
        'postal_code' => 'nullable|string|max:20',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->reset(['address_line1', 'city', 'state', 'postal_code', 'addressId', 'editMode']);
        $this->modalOpen = true;
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->reset(['address_line1', 'city', 'state', 'postal_code', 'addressId', 'editMode']);
        $this->resetValidation();
    }

    public function edit($id)
    {
        $address = Address::findOrFail($id);
        
        $this->addressId = $id;
        $this->address_line1 = $address->address_line1;
        $this->city = $address->city;
        $this->state = $address->state;
        $this->postal_code = $address->postal_code;
        $this->editMode = true;
        $this->modalOpen = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $address = Address::findOrFail($this->addressId);
            $address->update([
                'address_line1' => $this->address_line1,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
            ]);

            AdminActivityLog::log(
                'address_updated',
                "Updated address: {$address->address_line1}, {$address->city}",
                Address::class,
                $address->id
            );
            
            $this->dispatch('alert', [
                'type' => 'success',
                'title' => 'Success',
                'message' => 'Address updated successfully!'
            ]);
        } else {
            $address = Address::create([
                'address_line1' => $this->address_line1,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
            ]);

            AdminActivityLog::log(
                'address_created',
                "Created address: {$address->address_line1}, {$address->city}",
                Address::class,
                $address->id
            );
            
            $this->dispatch('alert', [
                'type' => 'success',
                'title' => 'Success',
                'message' => 'Address created successfully!'
            ]);
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();

        AdminActivityLog::log(
            'address_deleted',
            "Deleted address: {$address->address_line1}, {$address->city}",
            Address::class,
            $address->id
        );
        
        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => 'Address deleted successfully!'
        ]);
    }

    public function render()
    {
        $addresses = Address::when($this->search, function($query) {
            $query->where('address_line1', 'like', '%' . $this->search . '%')
                  ->orWhere('city', 'like', '%' . $this->search . '%');
        })->paginate(10);

        return view('livewire.admin.tools.address-manager', compact('addresses'))->layout('layouts.admin', [
                'header' => 'Manage Addresses'
            ]);;
    }
}
