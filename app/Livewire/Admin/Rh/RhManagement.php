<?php

namespace App\Livewire\Admin\Rh;

use App\Models\RhEmployee;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class RhManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $departementFilter = 'all';
    
    public bool $showModal = false;
    public bool $editMode = false;
    public ?int $employeeId = null;

    // Form fields
    public $matricule, $nom, $prenom, $cin, $email, $phone;
    public $poste, $departement, $contrat_type = 'CDI';
    public $date_embauche, $date_fin_contrat, $salaire;
    public $address, $gender, $date_naissance, $status = 'active', $notes;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'matricule'    => 'nullable|string|max:50|unique:rh_employees,matricule,' . ($this->employeeId ?? 'NULL'),
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
        ];
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingDepartementFilter() { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function openEdit(int $id): void
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
        $this->editMode = true;
        $this->showModal = true;
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
        ];

        if ($this->editMode) {
            $emp = RhEmployee::findOrFail($this->employeeId);
            $emp->update($data);
            AdminActivityLog::log('rh_employee_updated', "Updated RH employee: {$emp->nom} {$emp->prenom}", RhEmployee::class, $emp->id);
            session()->flash('success', 'Employé mis à jour avec succès!');
        } else {
            $data['created_by'] = auth()->id();
            $emp = RhEmployee::create($data);
            AdminActivityLog::log('rh_employee_created', "Created RH employee: {$emp->nom} {$emp->prenom}", RhEmployee::class, $emp->id);
            session()->flash('success', 'Employé créé avec succès!');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $emp = RhEmployee::findOrFail($id);
        AdminActivityLog::log('rh_employee_deleted', "Deleted RH employee: {$emp->nom} {$emp->prenom}", RhEmployee::class, $emp->id);
        $emp->delete();
        session()->flash('success', 'Employé supprimé avec succès!');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'employeeId', 'matricule', 'nom', 'prenom', 'cin', 'email', 'phone',
            'poste', 'departement', 'date_embauche', 'date_fin_contrat',
            'salaire', 'address', 'gender', 'date_naissance', 'notes',
        ]);
        $this->contrat_type = 'CDI';
        $this->status = 'active';
        $this->editMode = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = RhEmployee::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nom', 'like', "%{$this->search}%")
                  ->orWhere('prenom', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('matricule', 'like', "%{$this->search}%")
                  ->orWhere('cin', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->departementFilter !== 'all') {
            $query->where('departement', $this->departementFilter);
        }

        $employees = $query->latest()->paginate(12);

        $statistics = [
            'total'    => RhEmployee::count(),
            'active'   => RhEmployee::where('status', 'active')->count(),
            'inactive' => RhEmployee::where('status', 'inactive')->count(),
            'en_conge' => RhEmployee::where('status', 'en_conge')->count(),
        ];

        $departements = RhEmployee::whereNotNull('departement')
            ->select('departement')->distinct()->pluck('departement');

        return view('livewire.admin.rh.rh-management', compact('employees', 'statistics', 'departements'))
            ->layout('layouts.admin', ['header' => 'Gestion RH']);
    }
}
