<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceTransaction;
use App\Models\FinanceCaisse;
use App\Models\Material;
use App\Models\RhEmployee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrintController extends Controller
{

    private function renderPrintTemplate(
        Request $request,
        string $view,
        array $data,
        string $fileName,
        string $paper = 'A4',
        string $orientation = 'portrait'
    ): Response {
        if (!view()->exists($view)) {
            abort(404, 'Template impression introuvable.');
        }

        if ($request->boolean('pdf')) {
            $pdf = Pdf::loadView($view, $data);
            $pdf->setPaper($paper, $orientation);
            return $pdf->stream($fileName);
        }

        $printHtml = view($view, $data)->render();

        $layoutCandidates = [
            'livewire.admin.impression.layouts.paper',
        ];

        $layoutView = collect($layoutCandidates)->first(fn (string $candidate) => view()->exists($candidate));

        if (!$layoutView) {
            abort(404, 'Layout impression introuvable.');
        }

        return response()->view($layoutView, [
            'title' => pathinfo($fileName, PATHINFO_FILENAME),
            'printHtml' => $printHtml,
        ]);
    }

    public function avisformation(Request $request, int $id, int $projectId){
        $candidat = \App\Models\Candidat::findOrFail($id);
        $project = \App\Models\ProgrameList::findOrFail($projectId);
        $submission = \App\Models\ProjectSubmission::where('candidat_id', $id)
            ->where('programe_id', $projectId)
            ->firstOrFail();
        $association = \App\Models\AssociationParameter::getByCategory('general');

        return $this->renderPrintTemplate($request,'livewire.admin.impression.avis-formation', compact('candidat', 'project', 'submission', 'association'),
            "avis-formation-{$candidat->nom}-{$candidat->prenom}.pdf"
        );
    }

    public function fiche_inscription(Request $request, int $id){
        $candidat = \App\Models\Candidat::with(['moroccoLocation'])->findOrFail($id);
        $association = \App\Models\AssociationParameter::getByCategory('general');

        return $this->renderPrintTemplate($request,'livewire.admin.impression.fiche-inscription', compact('candidat', 'association'),
            "fiche-inscription-{$candidat->nom}-{$candidat->prenom}.pdf"
        );
    }

    public function projectFicheInscription(Request $request, int $id, int $projectId){
        $candidat = \App\Models\Candidat::with(['moroccoLocation'])->findOrFail($id);
        $project = \App\Models\ProgrameList::findOrFail($projectId);
        $association = \App\Models\AssociationParameter::getByCategory('general');
        $agreement = \App\Models\CandidatProjectAgreement::where('candidat_id', $id)
            ->where('project_id', $projectId)
            ->first();

        return $this->renderPrintTemplate($request,'livewire.admin.impression.project-fiche-inscription', compact('candidat', 'project', 'agreement', 'association'),
            "fiche-inscription-projet-{$project->slug}-{$candidat->nom}-{$candidat->prenom}.pdf"
        );
    }

    public function projectAgreement(Request $request, int $id, int $projectId){
        $candidat = \App\Models\Candidat::findOrFail($id);
        $project = \App\Models\ProgrameList::findOrFail($projectId);
        $association = \App\Models\AssociationParameter::getByCategory('general');
        $agreement = \App\Models\CandidatProjectAgreement::where('candidat_id', $id)
            ->where('project_id', $projectId)
            ->firstOrFail();

        return $this->renderPrintTemplate($request,'livewire.admin.impression.project-agreement', compact('candidat', 'project', 'agreement', 'association'),
            "engagement-projet-{$project->slug}-{$candidat->nom}-{$candidat->prenom}.pdf"
        );
    }

    public function projectEvaluation(Request $request, int $id, int $projectId){
        $candidat = \App\Models\Candidat::findOrFail($id);
        $project = \App\Models\ProgrameList::findOrFail($projectId);
        $association = \App\Models\AssociationParameter::getByCategory('general');
        $evaluation = \App\Models\CandidatEvaluationGrid::where('candidat_id', $id)
            ->where('project_id', $projectId)
            ->firstOrFail();

        return $this->renderPrintTemplate($request,'livewire.admin.impression.project-evaluation', compact('candidat', 'project', 'evaluation', 'association'),
            "evaluation-projet-{$project->slug}-{$candidat->nom}-{$candidat->prenom}.pdf"
        );
    }

    public function projectReview(Request $request, int $id, int $projectId){
        if ($request->routeIs('user.*') && auth()->guard('candidat')->id() != $id) {
            abort(403, 'Unauthorized');
        }

        $candidat = \App\Models\Candidat::findOrFail($id);
        $project = \App\Models\ProgrameList::findOrFail($projectId);
        $association = \App\Models\AssociationParameter::getByCategory('general');
        $submission = \App\Models\ProjectSubmission::where('candidat_id', $id)
            ->where('programe_id', $projectId)
            ->firstOrFail();

        return $this->renderPrintTemplate($request,'livewire.admin.impression.project-review', compact('candidat', 'project', 'submission', 'association'),
            "avis-formation-{$project->slug}-{$candidat->nom}-{$candidat->prenom}.pdf"
        );
    }

    public function printFormulaire(Request $request, int $id){
        $submission = \App\Models\DynamicFormSubmission::with(['form', 'candidat', 'programe', 'answers.field'])->findOrFail($id);
        $association = \App\Models\AssociationParameter::getByCategory('general');
        $project = $submission->programe;

        return $this->renderPrintTemplate($request,'livewire.admin.impression.formulaire', compact('submission', 'project', 'association'),
            "formulaire-{$submission->form->title}-{$submission->candidat->nom}-{$submission->candidat->prenom}.pdf"
        );
    }

    public function printFolder(Request $request, int $id, int $projectId){
        if ($request->routeIs('user.*') && auth()->guard('candidat')->id() != $id) {
            abort(403, 'Unauthorized');
        }
        
        $candidat = \App\Models\Candidat::with(['moroccoLocation'])->findOrFail($id);
        $project = \App\Models\ProgrameList::findOrFail($projectId);
        $association = \App\Models\AssociationParameter::getByCategory('general');
        $agreement = \App\Models\CandidatProjectAgreement::where('candidat_id', $id)
            ->where('project_id', $projectId)
            ->first();

        $submissions = \App\Models\DynamicFormSubmission::with(['form', 'answers.field'])
            ->where('candidat_id', $id)
            ->where('programe_id', $projectId)
            ->get();

        $projectSubmission = \App\Models\ProjectSubmission::where('candidat_id', $id)
            ->where('programe_id', $projectId)
            ->first();

        return $this->renderPrintTemplate($request,'livewire.admin.impression.project-folder', compact('candidat', 'project', 'agreement', 'submissions', 'projectSubmission', 'association'),
            "dossier-complet-{$project->slug}-{$candidat->nom}-{$candidat->prenom}.pdf"
        );
    }

    /**
     * Print a single transaction receipt / justification
     */
    public function financeTransaction(Request $request, int $id)
    {
        $transaction = FinanceTransaction::with(['category', 'creator', 'attachments', 'caisse'])->findOrFail($id);
        $association = \App\Models\AssociationParameter::getByCategory('general');

        return $this->renderPrintTemplate($request,'livewire.admin.impression.finance-transaction', compact('transaction', 'association'),
            "transaction-{$transaction->reference}.pdf"
        );
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

        return $this->renderPrintTemplate(
            $request,
            'livewire.admin.impression.finance-report',
            compact('caisse', 'transactions', 'totalRevenue', 'totalDepense', 'solde', 'dateFrom', 'dateTo', 'association'),
            "rapport-financier-{$dateFrom}-{$dateTo}.pdf"
        );
    }

    // ═══════════════════════════════════════
    //  MATERIAL PRINTS
    // ═══════════════════════════════════════

    /**
     * Print a material fiche (single item)
     */
    public function materialFiche(Request $request, int $id)
    {
        $material = Material::with(['category', 'attachments', 'movements', 'maintenances'])->findOrFail($id);
        $association = \App\Models\AssociationParameter::getByCategory('general');

        return $this->renderPrintTemplate(
            $request,
            'livewire.admin.impression.material-fiche',
            compact('material', 'association'),
            "fiche-materiel-{$material->reference}.pdf"
        );
    }

    /**
     * Print full inventory report
     */
    public function materialInventory(Request $request)
    {
        $materials = Material::with(['category'])->orderBy('name')->get();
        $totalValue = $materials->sum('valeur_totale');
        $association = \App\Models\AssociationParameter::getByCategory('general');

        return $this->renderPrintTemplate(
            $request,
            'livewire.admin.impression.material-inventory',
            compact('materials', 'totalValue', 'association'),
            "inventaire-" . now()->format('Y-m-d') . ".pdf",
            'A4',
            'landscape'
        );
    }

    // ═══════════════════════════════════════
    //  RH PRINTS
    // ═══════════════════════════════════════

    /**
     * Print attestation de travail
     */
    public function rhAttestation(Request $request, int $id)
    {
        $employee = RhEmployee::findOrFail($id);
        $association = \App\Models\AssociationParameter::getByCategory('general');

        return $this->renderPrintTemplate(
            $request,
            'livewire.admin.impression.rh-attestation',
            compact('employee', 'association'),
            "attestation-travail-{$employee->nom}-{$employee->prenom}.pdf"
        );
    }

    /**
     * Print employee fiche
     */
    public function rhFiche(Request $request, int $id)
    {
        $employee = RhEmployee::findOrFail($id);
        $association = \App\Models\AssociationParameter::getByCategory('general');

        return $this->renderPrintTemplate(
            $request,
            'livewire.admin.impression.rh-fiche',
            compact('employee', 'association'),
            "fiche-employe-{$employee->nom}-{$employee->prenom}.pdf"
        );
    }

    /**
     * Print employee list
     */
    public function rhList(Request $request)
    {
        $employees = RhEmployee::where('status', 'active')->orderBy('nom')->get();
        $association = \App\Models\AssociationParameter::getByCategory('general');

        return $this->renderPrintTemplate(
            $request,
            'livewire.admin.impression.rh-list',
            compact('employees', 'association'),
            "liste-employes-" . now()->format('Y-m-d') . ".pdf",
            'A4',
            'landscape'
        );
    }
}
