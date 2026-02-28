<?php

namespace App\Livewire\Admin\Candidat;

use App\Models\Candidat;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.admin', ['header' => 'Edit Candidat'])]
class EditCandidat extends Component
{
    public $candidatId;
    public $matricule;
    public $nom;
    public $prenom;
    public $email;
    public $phone;
    public $date_naissance;
    public $age;
    public $address;
    public $password;
    public $is_active;

    public function mount($id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can edit candidats.');
            return redirect()->route('admin.users.index');
        }

        $candidat = Candidat::findOrFail($id);
        $this->candidatId = $candidat->id;
        $this->matricule = $candidat->matricule;
        $this->nom = $candidat->nom;
        $this->prenom = $candidat->prenom;
        $this->email = $candidat->email;
        $this->phone = $candidat->phone;
        $this->date_naissance = $candidat->date_naissance;
        $this->address = $candidat->address;
        $this->age = $candidat->age;
        $this->is_active = $candidat->is_active ?? true;
    }

    protected function rules()
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:candidat,email,' . $this->candidatId,
            'phone' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];

        if ($this->password) {
            $rules['password'] = 'min:6';
        }

        return $rules;
    }

    public function updateCandidat()
    {
        if (!auth()->user()->isSuperAdmin()) {
            session()->flash('error', 'Only super admins can edit candidats.');
            return redirect()->route('admin.candidats.index');
        }

        $this->validate();

        $candidat = Candidat::findOrFail($this->candidatId);

        $data = [
            'matricule' => $this->matricule,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_naissance' => $this->date_naissance,
            'address' => $this->address,
            'age' => $this->age,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $candidat->update($data);

        session()->flash('success', 'Candidat updated successfully!');
        return redirect()->route('admin.candidats.show', $candidat->id);
    }

    public function render()
    {
        return view('livewire.admin.candidat.edit-candidat');
    }
}
