<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Candidat;
use Barryvdh\DomPDF\Facade\Pdf;

class ProjectExportController extends Controller
{
    public function previewPdf($id)
    {
        $project = Project::with([
            'user',
            'products',
            'candidat',
            'employees',
            'presentations',
            'deliveries',
            'equipment',
            'rawMaterials',
            'financials'
        ])->findOrFail($id);

        $candidat = $project->candidat;

        return view('livewire.admin.exports.project-pdf', compact('project', 'candidat'));
    }
    
    public function exportPdf($id)
    {
        $project = Project::with([
            'user',
            'products',
            'employees',
            'candidat',
            'presentations',
            'deliveries',
            'equipment',
            'rawMaterials',
            'financials'
        ])->findOrFail($id);

        $candidat = $project->candidat;

        $pdf = Pdf::loadView('livewire.admin.exports.project-pdf', compact('project', 'candidat'));
        
        return $pdf->stream('project-' . $project->id . '-' . now()->format('Y-m-d') . '.pdf');
    }
}