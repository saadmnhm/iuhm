<?php

namespace App\Livewire\Admin\Rh;

use App\Models\RhEmployee;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class RhCreate extends Component
{
    use WithFileUploads;

    public $matricule, $nom, $prenom, $cin, $email, $phone;
    public $poste, $departement, $contrat_type = 'CDI';
    public $date_embauche, $date_fin_contrat, $salaire;
    public $address, $gender, $date_naissance, $status = 'active', $notes;
    public $photo;

    protected function rules()
    {
        return [
            'matricule'    => 'nullable|string|max:50|unique:rh_employees,matricule',
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

    public function save(): void
    {
        $this->validate();

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
            'created_by' => auth()->id(),
        ];

        $emp = RhEmployee::create($data);

        if ($this->photo) {
            $path = $this->photo->store('rh/employees/' . $emp->id, 'uploads');
            $emp->update(['photo_path' => $path]);
        }

        AdminActivityLog::log('rh_employee_created', "Created RH employee: {$emp->nom} {$emp->prenom}", RhEmployee::class, $emp->id);
        session()->flash('success', 'Employé créé avec succès!');
        $this->redirect(route('admin.rh.index'));
    }

    public function render()
    {
        return view('livewire.admin.rh.rh-create')
            ->layout('layouts.admin', ['header' => 'Nouvel Employé']);
    }
}
