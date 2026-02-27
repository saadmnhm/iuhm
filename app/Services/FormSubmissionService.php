<?php

namespace App\Services;

use App\Models\DynamicFormSubmission;
use Illuminate\Support\Collection;

class FormSubmissionService
{
    /**
     * Get all form models
     */
    protected function getFormModels(): array
    {
        return [
            'candidat' => DynamicFormSubmission::class,
        ];
    }

    /**
     * Get all submissions for a specific candidat
     */
    public function getCandidatSubmissions(int $candidatId): Collection
    {
        $submissions = collect();

        foreach ($this->getFormModels() as $formType => $modelClass) {
            $forms = $modelClass::where('candidat_id', $candidatId)->get();
            foreach ($forms as $form) {
                // Ensure form_type is set
                if (!isset($form->form_type)) {
                    $form->form_type = $formType;
                }
                $submissions->push($form);
            }
        }

        return $submissions->sortByDesc('updated_at');
    }

    /**
     * Get submission for a specific form type and candidat
     */
    public function getCandidatSubmissionByType(int $candidatId, string $formType)
    {
        $models = $this->getFormModels();
        
        if (!isset($models[$formType])) {
            return null;
        }

        $modelClass = $models[$formType];
        $submission = $modelClass::where('candidat_id', $candidatId)
            ->latest()
            ->first();

        // Ensure form_type is set
        if ($submission && !isset($submission->form_type)) {
            $submission->form_type = $formType;
        }

        return $submission;
    }

    /**
     * Get all submissions across all forms
     */
    public function getAllSubmissions(?string $status = null): Collection
    {
        $submissions = collect();

        foreach ($this->getFormModels() as $formType => $modelClass) {
            $query = $modelClass::with(['candidat', 'reviewer', 'programe']);
            
            if ($status) {
                $query->where('status', $status);
            }

            $forms = $query->get();
            foreach ($forms as $form) {
                // Ensure form_type is set
                if (!isset($form->form_type)) {
                    $form->form_type = $formType;
                }
                $submissions->push($form);
            }
        }

        return $submissions->sortByDesc('created_at');
    }

    /**
     * Get statistics for candidat
     */
    public function getCandidatStats(int $candidatId): array
    {
        $submissions = $this->getCandidatSubmissions($candidatId);

        return [
            'total' => $submissions->count(),
            'drafts' => $submissions->where('status', 'draft')->count(),
            'submitted' => $submissions->where('status', 'submitted')->count(),
            'approved' => $submissions->where('status', 'approved')->count(),
            'in_review' => $submissions->where('status', 'in_review')->count(),
            'rejected' => $submissions->where('status', 'rejected')->count(),
        ];
    }

    /**
     * Get overall statistics
     */
    public function getOverallStats(): array
    {
        $submissions = $this->getAllSubmissions();

        return [
            'total' => $submissions->count(),
            'drafts' => $submissions->where('status', 'draft')->count(),
            'submitted' => $submissions->where('status', 'submitted')->count(),
            'approved' => $submissions->where('status', 'approved')->count(),
            'in_review' => $submissions->where('status', 'in_review')->count(),
            'rejected' => $submissions->where('status', 'rejected')->count(),
            'by_form_type' => $submissions->groupBy('form_type')->map->count(),
        ];
    }

    /**
     * Get specific submission by ID and type
     */
    public function getSubmission(int $id, string $formType)
    {
        $models = $this->getFormModels();
        
        if (!isset($models[$formType])) {
            return null;
        }

        $modelClass = $models[$formType];
        $submission = $modelClass::with('candidat')->find($id);

        // Ensure form_type is set
        if ($submission && !isset($submission->form_type)) {
            $submission->form_type = $formType;
        }

        return $submission;
    }
}
