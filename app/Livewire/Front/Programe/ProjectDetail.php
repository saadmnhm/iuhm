<?php

namespace App\Livewire\Front\Programe;

use Livewire\Component;
use App\Models\ProgrameList;
use App\Models\DynamicFormSubmission;
use Illuminate\Support\Facades\Auth;

class ProjectDetail extends Component
{
    public $projectId;
    public $project;
    public $formulaires = [];
    public $currentFormulaireIndex = null;
    
    public function mount($id)
    {
        $this->projectId = $id;
        $this->loadProject();
    }
    
    public function loadProject()
    {
        $this->project = ProgrameList::with(['formulaires' => function($query) {
            $query->where('programe_formulaire.status', 'active')
                  ->orderBy('programe_formulaire.order');
        }])->findOrFail($this->projectId);
        
        // Frontend is behind candidat middleware, always use candidat guard
        $candidatId = Auth::guard('candidat')->id();

        // Load formulaires with their details
        $this->formulaires = $this->project->formulaires->map(function($form, $index) use ($candidatId) {
            $submission = null;
            
            // Check if candidat has submitted this formulaire for this project
            if ($candidatId) {
                $submission = DynamicFormSubmission::where('dynamic_form_id', $form->id)
                    ->where('programe_id', $this->projectId)
                    ->where('candidat_id', $candidatId)
                    ->first();
            }
            
            return [
                'id' => $form->id,
                'title' => $form->title,
                'title_ar' => $form->title_ar,
                'slug' => $form->slug,
                'order' => $form->pivot->order,
                'is_required' => $form->pivot->is_required,
                'has_introduction' => $form->has_introduction,
                'icon' => $form->icon ?? 'ri-file-list-3-line',
                'color' => $form->color ?? '#2f5496',
                'status' => $form->pivot->status,
                'submission_id' => $submission?->id,
                'submitted_at' => $submission?->submitted_at,
                'is_completed' => in_array($submission?->status, ['submitted', 'in_review', 'approved']),
                'index' => $index,
            ];
        })->toArray();
        
        // Find the first incomplete formulaire
        $this->findCurrentFormulaire();
    }
    
    public function findCurrentFormulaire()
    {
        foreach ($this->formulaires as $index => $form) {
            if (!$form['is_completed']) {
                $this->currentFormulaireIndex = $index;
                return;
            }
        }
        // If all completed, set to null
        $this->currentFormulaireIndex = null;
    }
    
    public function startFormulaire($index)
    {
        if (isset($this->formulaires[$index])) {
            $formulaire = $this->formulaires[$index];
            
            // Check if previous required formulaires are completed
            for ($i = 0; $i < $index; $i++) {
                if ($this->formulaires[$i]['is_required'] && !$this->formulaires[$i]['is_completed']) {
                    session()->flash('error', 'You must complete the previous required formulaires first.');
                    return;
                }
            }
            
            return redirect()->route('user.project.formulaire', [
                'projectId' => $this->projectId,
                'formulaireSlug' => $formulaire['slug'],
                'order' => $formulaire['order']
            ]);
        }
    }
    
    public function render()
    {
        return view('livewire.front.programe.project-detail')
            ->layout('layouts.app', ['title' => $this->project->project_name]);
    }
}
