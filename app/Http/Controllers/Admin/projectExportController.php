<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;

class ProjectExportController extends Controller
{
    public function previewPdf($id)
    {
        $project = Project::with([
            'user',
            'products',
            'employees',
            'presentations',
            'deliveries',
            'equipment',
            'rawMaterials',
            'financials'
        ])->findOrFail($id);

        return view('livewire.admin.exports.project-pdf', compact('project'));
    }
    
    public function exportPdf($id)
    {
        $project = Project::with([
            'user',
            'products',
            'employees',
            'presentations',
            'deliveries',
            'equipment',
            'rawMaterials',
            'financials'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('livewire.admin.exports.project-pdf', compact('project'));
        
        return $pdf->stream('project-' . $project->id . '-' . now()->format('Y-m-d') . '.pdf');
    }
}