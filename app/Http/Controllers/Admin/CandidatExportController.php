<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\DynamicFormSubmission;
use Barryvdh\DomPDF\Facade\Pdf;

class CandidatExportController extends Controller
{
    /**
     * Export a single form submission as PDF.
     */
    public function exportSingle(int $candidatId, int $id)
    {
        $submission = DynamicFormSubmission::with([
            'form.steps.fields',
            'form.steps.tables.columns',
            'form.steps.tables.fixedRows',
            'candidat',
            'answers',
            'tableAnswers',
            'reviewer',
            'programe',
        ])->findOrFail($id);

        // Group answers by field_key for easy template access
        $answers = $submission->answers->keyBy('field_key');

        $pdf = Pdf::loadView('admin.exports.submission-pdf', compact('submission', 'answers'))
            ->setPaper('a4', 'portrait')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => false]);

        $filename = 'formulaire_' . ($submission->form->title ?? $id) . '_' . $submission->candidat->nom . '.pdf';
        $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $filename);

        return $pdf->stream($filename);
    }

    /**
     * Export all form submissions for a candidat as a single PDF.
     */
    public function exportAll(int $id)
    {
        $candidat = Candidat::findOrFail($id);

        $submissions = DynamicFormSubmission::with([
            'form.steps.fields',
            'form.steps.tables.columns',
            'form.steps.tables.fixedRows',
            'answers',
            'tableAnswers',
            'reviewer',
            'programe',
        ])
        ->where('candidat_id', $id)
        ->whereNotNull('programe_id')
        ->whereIn('status', ['submitted', 'in_review', 'approved', 'rejected'])
        ->orderBy('dynamic_form_id')
        ->get();

        // For each submission, key answers by field_key
        $submissions = $submissions->map(function ($sub) {
            $sub->answers_keyed = $sub->answers->keyBy('field_key');
            return $sub;
        });

        $pdf = Pdf::loadView('admin.exports.candidat-all-submissions-pdf', compact('candidat', 'submissions'))
            ->setPaper('a4', 'portrait')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => false]);

        $filename = 'soumissions_' . $candidat->nom . '_' . $candidat->prenom . '.pdf';
        $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $filename);

        return $pdf->stream($filename);
    }
}
