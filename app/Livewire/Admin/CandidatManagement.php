<?php

namespace App\Livewire\Admin;

use App\Models\Candidat;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CandidatManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = 'all';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showShowModal = false;
    
    public $candidatId;
    public $nom;
    public $prenom;
    public $email;
    public $password;
    public $selectedCandidat = null;
    
    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:candidats,email,' . ($this->candidatId ?? 'NULL'),
            'address' => 'nullable|string|max:500',
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

        $this->reset(['nom', 'prenom', 'email', 'password', 'candidatId']);
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
        $this->nom = $candidat->nom;
        $this->prenom = $candidat->prenom;
        $this->email = $candidat->email;
        $this->password = '';
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

        Candidat::create([
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $this->showCreateModal = false;
        session()->flash('success', 'Candidat created successfully!');
        $this->reset(['nom', 'prenom', 'email', 'password', 'candidatId']);
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
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $candidat->update($data);

        $this->showEditModal = false;
        session()->flash('success', 'Candidat updated successfully!');
        $this->reset(['nom', 'prenom', 'email', 'password', 'candidatId']);
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

        Candidat::findOrFail($this->candidatId)->delete();

        $this->showDeleteModal = false;
        session()->flash('success', 'Candidat deleted successfully!');
        $this->candidatId = null;
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showShowModal = false;
        $this->selectedCandidat = null;
        $this->reset(['nom', 'prenom', 'email', 'password', 'candidatId']);
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

        // $statistics = [
        //     'total_candidats' => Candidat::count(),
        //     'admins' => Candidat::where('role', 'admin')->count(),
        //     'super_admins' => Candidat::where('role', 'super_admin')->count(),
        //     'regular_candidats' => Candidat::where('role', 'user')->count(),
        // ];

        return view('livewire.admin.candidat.candidat-management', [
            'candidats' => $candidats,
            // 'statistics' => $statistics,
        ])->layout('layouts.admin', ['header' => 'Candidat Management']);
    }
}
