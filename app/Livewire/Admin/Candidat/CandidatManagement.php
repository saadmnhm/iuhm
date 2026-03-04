<?php

namespace App\Livewire\Admin\Candidat;

use Propaganistas\LaravelPhone\PhoneNumber;
use App\Models\Address;
use App\Models\Candidat;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CandidatManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = 'all';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showShowModal = false;
    public $showPasswordModal = false;
    public $generatedPassword = '';
    
    public $candidatId;
    public $matricule;
    public $nom;
    public $prenom;
    public $email;
    public $phone;
    public $address;
    public $address_id = '';
    public $address_custom = '';
    public $password;
    public $selectedCandidat = null;
    
    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        $rules = [
            'matricule' => 'nullable|string|max:50|unique:candidat,matricule,' . ($this->candidatId ?? 'NULL'),
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:candidat,email,' . ($this->candidatId ?? 'NULL'),
            'address' => 'nullable|string|max:500',
            'address_id' => 'nullable',
            'address_custom' => 'nullable|string|max:500|required_if:address_id,other',
            'phone' => 'nullable|string|max:20',
        ];

        if ($this->showCreateModal) {
            $rules['password'] = 'required|min:6';
        } elseif ($this->password) {
            $rules['password'] = 'min:6';
        }

        return $rules;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            session()->flash('error', 'You do not have permission to create users.');
            return;
        }

        $this->reset(['nom', 'prenom', 'email', 'password', 'candidatId', 'address_id', 'address_custom']);
        $this->showCreateModal = true;
    }

    public function openShowModal($candidatId)
    {
        $this->selectedCandidat = Candidat::findOrFail($candidatId);
        $this->showShowModal = true;
    }

    public function openEditModal($candidatId)
    {
        if (!Auth::user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can edit users.');
            return;
        }

        $candidat = Candidat::findOrFail($candidatId);
        $this->candidatId = $candidat->id;
        $this->matricule = $candidat->matricule;
        $this->nom = $candidat->nom;
        $this->prenom = $candidat->prenom;
        $this->email = $candidat->email;
        $this->password = '';

        // Determine if saved address matches a known address
        $known = Address::where('address_line1', $candidat->address)->first();
        if ($candidat->address && !$known) {
            $this->address_id = 'other';
            $this->address_custom = $candidat->address;
        } else {
            $this->address_id = $known ? $known->id : '';
            $this->address_custom = '';
        }

        $this->showEditModal = true;
    }

    public function openDeleteModal($candidatId)
    {
        if (!Auth::user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can delete candidats.');
            return;
        }

        if ($candidatId == Auth::id()) {
            session()->flash('error', 'You cannot delete yourself.');
            return;
        }

        $this->candidatId = $candidatId;
        $this->selectedCandidat = Candidat::find($candidatId);
        $this->showDeleteModal = true;
    }

    public function createCandidat()
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            session()->flash('error', 'You do not have permission to create candidats.');
            return;
        }

        if (Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin() && $this->role !== 'user') {
            session()->flash('error', 'You can only create regular candidats.');
            return;
        }

        $this->validate();

        $candidat = Candidat::create([
            'matricule' => $this->matricule ?: null,
            'nom'       => $this->nom,
            'prenom'    => $this->prenom,
            'email'     => $this->email,
            'password'  => Hash::make($this->password),
            'address'   => $this->resolveAddress(),
        ]);

        AdminActivityLog::log(
            'candidat_created',
            "Created candidat: {$candidat->nom} {$candidat->prenom} ({$candidat->email})",
            Candidat::class,
            $candidat->id
        );

        $this->showCreateModal = false;
        session()->flash('success', 'Candidat created successfully!');
        $this->reset(['matricule', 'nom', 'prenom', 'email', 'password', 'candidatId', 'address_id', 'address_custom']);
    }

    public function updateCandidat()
    {
        if (!Auth::user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can edit candidats.');
            return;
        }

        $this->validate();

        $candidat = Candidat::findOrFail($this->candidatId);

        $data = [
            'matricule' => $this->matricule ?: null,
            'nom'       => $this->nom,
            'prenom'    => $this->prenom,
            'email'     => $this->email,
            'address'   => $this->resolveAddress(),
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $candidat->update($data);

        AdminActivityLog::log(
            'candidat_updated',
            "Updated candidat: {$candidat->nom} {$candidat->prenom} ({$candidat->email})",
            Candidat::class,
            $candidat->id
        );

        $this->showEditModal = false;
        session()->flash('success', 'Candidat updated successfully!');
        $this->reset(['nom', 'prenom', 'email', 'password', 'candidatId', 'address_id', 'address_custom']);
    }

    public function deleteCandidat()
    {
        if (!Auth::user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can delete candidats.');
            return;
        }

        if ($this->candidatId == Auth::id()) {
            session()->flash('error', 'You cannot delete yourself.');
            return;
        }

        $candidat = Candidat::findOrFail($this->candidatId);
        $candidat->delete();

        AdminActivityLog::log(
            'candidat_deleted',
            "Deleted candidat: {$candidat->nom} {$candidat->prenom} ({$candidat->email})",
            Candidat::class,
            $candidat->id
        );

        $this->showDeleteModal = false;
        $this->selectedCandidat = null;
        session()->flash('success', 'Candidat deleted successfully!');
        $this->candidatId = null;
    }

    public function generateNewPassword($candidatId)
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            session()->flash('error', 'Permission refusée.');
            return;
        }

        $candidat = Candidat::findOrFail($candidatId);
        $newPassword = Str::random(10);
        
        $candidat->update([
            'password' => Hash::make($newPassword),
        ]);

        $this->generatedPassword = $newPassword;
        $this->candidatId = $candidatId;
        $this->selectedCandidat = $candidat;
        $this->showPasswordModal = true;

        AdminActivityLog::log(
            'password_generated',
            "Generated new password for candidat: {$candidat->nom} {$candidat->prenom}",
            Candidat::class,
            $candidat->id
        );
    }

    public function toggleStatus($candidatId)
    {
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isAdmin()) {
            session()->flash('error', 'Permission refusée.');
            return;
        }

        $candidat = Candidat::findOrFail($candidatId);
        $candidat->update(['is_active' => !$candidat->is_active]);

        $status = $candidat->is_active ? 'activé' : 'désactivé';
        AdminActivityLog::log(
            'candidat_status_toggled',
            "Candidat {$candidat->nom} {$candidat->prenom} {$status}",
            Candidat::class,
            $candidat->id
        );

        session()->flash('success', "Candidat {$status} avec succès!");
    }

    protected function resolveAddress(): ?string
    {
        if ($this->address_id === 'other') {
            return $this->address_custom ?: null;
        }
        if ($this->address_id) {
            $addr = Address::find($this->address_id);
            return $addr ? $addr->address_line1 . ($addr->city ? ', ' . $addr->city : '') : null;
        }
        return null;
    }

    public function closeModals()    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showShowModal = false;
        $this->selectedCandidat = null;
        $this->reset(['nom', 'prenom', 'email', 'password', 'candidatId', 'address_id', 'address_custom']);
    }

    public function render()
    {
        $query = Candidat::query();

        $query->where('id', '!=', Auth::id());

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('prenom', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter !== 'all') {
            $query->where('role', $this->roleFilter);
        }

        $candidats = $query->latest()->paginate(10);

        $statistics = [
            'total' => Candidat::count(),
            'active' => Candidat::where('is_active', true)->count(),
            'inactive' => Candidat::where('is_active', false)->count(),
            'new_this_month' => Candidat::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)->count(),
        ];

        return view('livewire.admin.candidat.candidat-management', [
            'candidats'  => $candidats,
            'statistics' => $statistics,
            'addresses'  => Address::orderBy('city')->orderBy('address_line1')->get(),
        ])->layout('layouts.admin', ['header' => 'Candidat Management']);
    }

}
