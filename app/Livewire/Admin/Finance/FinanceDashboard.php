<?php

namespace App\Livewire\Admin\Finance;

use App\Models\FinanceCaisse;
use App\Models\FinanceTransaction;
use App\Models\FinanceCategory;
use App\Models\FinanceCharge;
use Livewire\Component;
use Livewire\WithPagination;

class FinanceDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = 'all';
    public $categoryFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';
    public $tab = 'transactions'; // transactions | charges | categories

    protected $paginationTheme = 'tailwind';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingTypeFilter() { $this->resetPage(); }

    public function deleteTransaction(int $id): void
    {
        $t = FinanceTransaction::findOrFail($id);
        $t->delete();
        session()->flash('success', 'Transaction supprimée avec succès.');
    }

    public function deleteCharge(int $id): void
    {
        FinanceCharge::findOrFail($id)->delete();
        session()->flash('success', 'Charge supprimée avec succès.');
    }

    public function deleteCategory(int $id): void
    {
        FinanceCategory::findOrFail($id)->delete();
        session()->flash('success', 'Catégorie supprimée.');
    }

    // Quick category creation
    public $newCatName = '';
    public $newCatType = 'depense';

    public function createCategory(): void
    {
        $this->validate([
            'newCatName' => 'required|string|max:255',
            'newCatType' => 'required|in:revenue,depense',
        ]);
        FinanceCategory::create([
            'name' => $this->newCatName,
            'type' => $this->newCatType,
        ]);
        $this->newCatName = '';
        session()->flash('success', 'Catégorie créée.');
    }

    public function render()
    {
        // Ensure a caisse exists
        $caisse = FinanceCaisse::firstOrCreate(
            ['id' => 1],
            ['label' => 'Caisse Principale', 'solde_initial' => 0, 'created_by' => auth()->id()]
        );

        $transactionsQuery = FinanceTransaction::with(['category', 'creator', 'attachments'])
            ->where('caisse_id', $caisse->id);

        if ($this->search) {
            $transactionsQuery->where(function ($q) {
                $q->where('label', 'like', "%{$this->search}%")
                  ->orWhere('reference', 'like', "%{$this->search}%")
                  ->orWhere('beneficiaire', 'like', "%{$this->search}%");
            });
        }

        if ($this->typeFilter !== 'all') {
            $transactionsQuery->where('type', $this->typeFilter);
        }

        if ($this->categoryFilter !== 'all') {
            $transactionsQuery->where('category_id', $this->categoryFilter);
        }

        if ($this->dateFrom) {
            $transactionsQuery->whereDate('date_transaction', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $transactionsQuery->whereDate('date_transaction', '<=', $this->dateTo);
        }

        $transactions = $transactionsQuery->latest('date_transaction')->paginate(15);

        $totalRevenue = FinanceTransaction::where('caisse_id', $caisse->id)->revenue()->valide()->sum('amount');
        $totalDepense = FinanceTransaction::where('caisse_id', $caisse->id)->depense()->valide()->sum('amount');
        $solde = (float) $caisse->solde_initial + (float) $totalRevenue - (float) $totalDepense;

        $charges = FinanceCharge::where('caisse_id', $caisse->id)->orderBy('label')->get();
        $categories = FinanceCategory::orderBy('type')->orderBy('name')->get();

        $monthlyRevenue = FinanceTransaction::where('caisse_id', $caisse->id)
            ->revenue()->valide()
            ->whereMonth('date_transaction', now()->month)
            ->whereYear('date_transaction', now()->year)
            ->sum('amount');

        $monthlyDepense = FinanceTransaction::where('caisse_id', $caisse->id)
            ->depense()->valide()
            ->whereMonth('date_transaction', now()->month)
            ->whereYear('date_transaction', now()->year)
            ->sum('amount');

        return view('livewire.admin.finance.finance-dashboard', compact(
            'caisse', 'transactions', 'totalRevenue', 'totalDepense', 'solde',
            'charges', 'categories', 'monthlyRevenue', 'monthlyDepense'
        ))->layout('layouts.admin', ['header' => 'Gestion Financière']);
    }
}
