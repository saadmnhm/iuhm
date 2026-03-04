<?php

namespace App\Livewire\Admin\Material;

use App\Models\Material;
use App\Models\MaterialCategory;
use Livewire\Component;
use Livewire\WithPagination;

class MaterialDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = 'all';
    public $statusFilter = 'all';
    public $etatFilter = 'all';
    public $tab = 'inventory'; // inventory | categories

    // Quick category creation
    public $newCatName = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function deleteMaterial(int $id): void
    {
        Material::findOrFail($id)->delete();
        session()->flash('success', 'Matériel supprimé.');
    }

    public function deleteCategory(int $id): void
    {
        MaterialCategory::findOrFail($id)->delete();
        session()->flash('success', 'Catégorie supprimée.');
    }

    public function createCategory(): void
    {
        $this->validate(['newCatName' => 'required|string|max:255']);
        MaterialCategory::create(['name' => $this->newCatName]);
        $this->newCatName = '';
        session()->flash('success', 'Catégorie créée.');
    }

    public function render()
    {
        $query = Material::with(['category', 'attachments', 'primaryPhoto']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('reference', 'like', "%{$this->search}%")
                  ->orWhere('emplacement', 'like', "%{$this->search}%")
                  ->orWhere('numero_serie', 'like', "%{$this->search}%");
            });
        }

        if ($this->categoryFilter !== 'all') {
            $query->where('category_id', $this->categoryFilter);
        }
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }
        if ($this->etatFilter !== 'all') {
            $query->where('etat', $this->etatFilter);
        }

        $materials = $query->latest()->paginate(12);

        $statistics = [
            'total' => Material::count(),
            'disponible' => Material::where('status', 'disponible')->count(),
            'en_utilisation' => Material::where('status', 'en_utilisation')->count(),
            'maintenance' => Material::where('status', 'en_maintenance')->count(),
            'valeur_totale' => Material::sum('valeur_totale'),
            'low_stock' => Material::whereColumn('quantity', '<=', 'quantity_min')->where('quantity_min', '>', 0)->count(),
        ];

        $categories = MaterialCategory::orderBy('name')->get();

        return view('livewire.admin.material.material-dashboard', compact('materials', 'statistics', 'categories'))
            ->layout('layouts.admin', ['header' => 'Gestion Matériel & Inventaire']);
    }
}
