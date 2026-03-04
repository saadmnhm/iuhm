<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceTransaction;
use App\Models\FinanceCaisse;
use App\Models\Material;
use App\Models\RhEmployee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    // ═══════════════════════════════════════
    //  FINANCE PRINTS
    // ═══════════════════════════════════════

    /**
     * Print a single transaction receipt / justification
     */
    public function financeTransaction(int $id)
    {
        $transaction = FinanceTransaction::with(['category', 'creator', 'attachments', 'caisse'])->findOrFail($id);
        $association = \App\Models\AssociationParameter::getByCategory('general');

        $pdf = Pdf::loadView('admin.prints.finance-transaction', compact('transaction', 'association'));
        $pdf->setPaper('A4');

        return $pdf->stream("transaction-{$transaction->reference}.pdf");
    }

    /**
     * Print full financial report / bilan
     */
    public function financeReport(Request $request)
    {
        $caisse = FinanceCaisse::firstOrFail();

        $dateFrom = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('to', now()->format('Y-m-d'));

        $transactions = FinanceTransaction::with(['category'])
            ->where('caisse_id', $caisse->id)
            ->where('status', 'valide')
            ->whereDate('date_transaction', '>=', $dateFrom)
            ->whereDate('date_transaction', '<=', $dateTo)
            ->orderBy('date_transaction')
            ->get();

        $totalRevenue = $transactions->where('type', 'revenue')->sum('amount');
        $totalDepense = $transactions->where('type', 'depense')->sum('amount');
        $solde = (float) $caisse->solde_initial + (float) $totalRevenue - (float) $totalDepense;
        $association = \App\Models\AssociationParameter::getByCategory('general');

        $pdf = Pdf::loadView('admin.prints.finance-report', compact(
            'caisse', 'transactions', 'totalRevenue', 'totalDepense', 'solde', 'dateFrom', 'dateTo', 'association'
        ));
        $pdf->setPaper('A4');

        return $pdf->stream("rapport-financier-{$dateFrom}-{$dateTo}.pdf");
    }

    // ═══════════════════════════════════════
    //  MATERIAL PRINTS
    // ═══════════════════════════════════════

    /**
     * Print a material fiche (single item)
     */
    public function materialFiche(int $id)
    {
        $material = Material::with(['category', 'attachments', 'movements', 'maintenances'])->findOrFail($id);
        $association = \App\Models\AssociationParameter::getByCategory('general');

        $pdf = Pdf::loadView('admin.prints.material-fiche', compact('material', 'association'));
        $pdf->setPaper('A4');

        return $pdf->stream("fiche-materiel-{$material->reference}.pdf");
    }

    /**
     * Print full inventory report
     */
    public function materialInventory()
    {
        $materials = Material::with(['category'])->orderBy('name')->get();
        $totalValue = $materials->sum('valeur_totale');
        $association = \App\Models\AssociationParameter::getByCategory('general');

        $pdf = Pdf::loadView('admin.prints.material-inventory', compact('materials', 'totalValue', 'association'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream("inventaire-" . now()->format('Y-m-d') . ".pdf");
    }

    // ═══════════════════════════════════════
    //  RH PRINTS
    // ═══════════════════════════════════════

    /**
     * Print attestation de travail
     */
    public function rhAttestation(int $id)
    {
        $employee = RhEmployee::findOrFail($id);
        $association = \App\Models\AssociationParameter::getByCategory('general');

        $pdf = Pdf::loadView('admin.prints.rh-attestation', compact('employee', 'association'));
        $pdf->setPaper('A4');

        return $pdf->stream("attestation-travail-{$employee->nom}-{$employee->prenom}.pdf");
    }

    /**
     * Print employee fiche
     */
    public function rhFiche(int $id)
    {
        $employee = RhEmployee::findOrFail($id);
        $association = \App\Models\AssociationParameter::getByCategory('general');

        $pdf = Pdf::loadView('admin.prints.rh-fiche', compact('employee', 'association'));
        $pdf->setPaper('A4');

        return $pdf->stream("fiche-employe-{$employee->nom}-{$employee->prenom}.pdf");
    }

    /**
     * Print employee list
     */
    public function rhList()
    {
        $employees = RhEmployee::where('status', 'active')->orderBy('nom')->get();
        $association = \App\Models\AssociationParameter::getByCategory('general');

        $pdf = Pdf::loadView('admin.prints.rh-list', compact('employees', 'association'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream("liste-employes-" . now()->format('Y-m-d') . ".pdf");
    }
}
