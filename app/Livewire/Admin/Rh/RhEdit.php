<?php

namespace App\Livewire\Admin\Rh;

use App\Models\RhEmployee;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class RhEdit extends Component
{
    use WithFileUploads;

    public int $employeeId;
    public $matricule, $nom, $prenom, $cin, $email, $phone;
    public $poste, $departement, $contrat_type;
    public $date_embauche, $date_fin_contrat, $salaire;
    public $address, $gender, $date_naissance, $status, $notes;
    public $photo;
    public $existingPhoto;

    protected function rules()
    {
        return [
            'matricule'    => 'nullable|string|max:50|unique:rh_employees,matricule,' . $this->employeeId,
            'nom'          => 'required|string|max:255',
            'prenom'       => 'required|string|max:255',
            'cin'          => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:20',
            'poste'        => 'nullable|string|max:255',
            'departement'  => 'nullable|string|max:255',
            'contrat_type' => 'required|in:CDI,CDD,Stage,Freelance,Autre',
            'date_embauche' => 'nullable|date',
            'date_fin_contrat' => 'nullable|date|after_or_equal:date_embauche',
            'salaire'      => 'nullable|numeric|min:0',
            'address'      => 'nullable|string|max:500',
            'gender'       => 'nullable|in:homme,femme',
            'date_naissance' => 'nullable|date',
            'status'       => 'required|in:active,inactive,en_conge,quitte',
            'notes'        => 'nullable|string|max:1000',
            'photo'        => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function mount($id)
    {
        $emp = RhEmployee::findOrFail($id);
        $this->employeeId = $emp->id;
        $this->matricule = $emp->matricule;
        $this->nom = $emp->nom;
        $this->prenom = $emp->prenom;
        $this->cin = $emp->cin;
        $this->email = $emp->email;
        $this->phone = $emp->phone;
        $this->poste = $emp->poste;
        $this->departement = $emp->departement;
        $this->contrat_type = $emp->contrat_type;
        $this->date_embauche = $emp->date_embauche?->format('Y-m-d');
        $this->date_fin_contrat = $emp->date_fin_contrat?->format('Y-m-d');
        $this->salaire = $emp->salaire;
        $this->address = $emp->address;
        $this->gender = $emp->gender;
        $this->date_naissance = $emp->date_naissance?->format('Y-m-d');
        $this->status = $emp->status;
        $this->notes = $emp->notes;
        $this->existingPhoto = $emp->photo_path ?? null;
    }

    public function save(): void
    {
        $this->validate();

        $emp = RhEmployee::findOrFail($this->employeeId);

        $data = [
            'matricule' => $this->matricule ?: null,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'cin' => $this->cin,
            'email' => $this->email,
            'phone' => $this->phone,
            'poste' => $this->poste,
            'departement' => $this->departement,
            'contrat_type' => $this->contrat_type,
            'date_embauche' => $this->date_embauche ?: null,
            'date_fin_contrat' => $this->date_fin_contrat ?: null,
            'salaire' => $this->salaire ?: null,
            'address' => $this->address,
            'gender' => $this->gender ?: null,
            'date_naissance' => $this->date_naissance ?: null,
            'status' => $this->status,
            'notes' => $this->notes,
        ];

        if ($this->photo) {
            $path = $this->photo->store('rh/employees/' . $emp->id, 'uploads');
            $data['photo_path'] = $path;
        }

        $emp->update($data);

        AdminActivityLog::log('rh_employee_updated', "Updated RH employee: {$emp->nom} {$emp->prenom}", RhEmployee::class, $emp->id);
        session()->flash('success', 'Employé mis à jour avec succès!');
        $this->redirect(route('admin.rh.index'));
    }

    public function render()
    {
        return view('livewire.admin.rh.rh-edit')
            ->layout('layouts.admin', ['header' => 'Modifier Employé']);
    }
}
