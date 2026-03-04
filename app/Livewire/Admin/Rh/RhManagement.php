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

    protected $paginationTheme = 'tailwind';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingDepartementFilter() { $this->resetPage(); }

    public function delete(int $id): void
    {
        $emp = RhEmployee::findOrFail($id);
        AdminActivityLog::log('rh_employee_deleted', "Deleted RH employee: {$emp->nom} {$emp->prenom}", RhEmployee::class, $emp->id);
        $emp->delete();
        session()->flash('success', 'Employé supprimé avec succès!');
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
