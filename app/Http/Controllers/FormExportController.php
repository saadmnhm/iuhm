<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormExportController extends Controller
{
    /**
     * Export Etude de Marche to PDF
     */
    public function exportEtudeMarche($id)
    {
        $etudeMarche = EtudeMarche::with('candidat')->findOrFail($id);
        
        $pdf = Pdf::loadView('livewire.admin.exports.etude-marche-pdf', [
            'etudeMarche' => $etudeMarche
        ]);
        
        $fileName = 'etude-marche-' . $etudeMarche->id . '-' . date('Y-m-d') . '.pdf';
        
        return $pdf->stream($fileName);
    }

    /**
     * Export Evaluation Idee to PDF
     */
    public function exportEvaluationIdee($id)
    {
        $evaluationIdee = EvaluationIdee::with('candidat')->findOrFail($id);
        
        $pdf = Pdf::loadView('livewire.admin.exports.evaluation-idee-pdf', [
            'evaluationIdee' => $evaluationIdee
        ]);
        
        $fileName = 'evaluation-idee-' . $evaluationIdee->id . '-' . date('Y-m-d') . '.pdf';
        
        return $pdf->stream($fileName);
    }

    /**
     * Export BMC to PDF
     */
    public function exportBmc($id)
    {
        $bmc = Bmc::with('candidat')->findOrFail($id);
        
        $pdf = Pdf::loadView('livewire.admin.exports.bmc-pdf', [
            'bmc' => $bmc
        ]);
        
        $fileName = 'bmc-' . $bmc->id . '-' . date('Y-m-d') . '.pdf';
        
        return $pdf->stream($fileName);
    }

    /**
     * Export Bilan Competence to PDF
     */
    public function exportBilanCompetence($id)
    {
        $bilanCompetence = BilanCompetence::with('candidat')->findOrFail($id);
        
        $pdf = Pdf::loadView('livewire.admin.exports.bilan-competence-pdf', [
            'bilanCompetence' => $bilanCompetence
        ]);
        
        $fileName = 'bilan-competence-' . $bilanCompetence->id . '-' . date('Y-m-d') . '.pdf';
        
        return $pdf->stream($fileName);
    }
}
