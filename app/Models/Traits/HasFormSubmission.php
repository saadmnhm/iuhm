<?php

namespace App\Models\Traits;

use App\Models\Candidat;
use App\Models\User;

trait HasFormSubmission
{
    /**
     * Get the candidat that owns the form
     */
    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }

    /**
     * Get the reviewer user
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Status check methods
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isSubmitted()
    {
        return in_array($this->status, ['submitted', 'in_review', 'approved', 'rejected']);
    }

    public function canBeEdited()
    {
        return $this->status === 'draft';
    }

    /**
     * Get human-readable form type label
     */
    public function getFormTypeLabelAttribute(): string
    {
        return match($this->form_type ?? $this->getFormType()) {
            'business_plan' => 'Business Plan',
            'etude_marche' => 'Étude de Marché',
            'evaluation_idee' => "Évaluation d'Idée",
            'bmc' => 'Business Model Canvas',
            'bilan_competence' => 'Bilan de Compétences',
            default => ucfirst(str_replace('_', ' ', $this->form_type ?? 'unknown')),
        };
    }

    /**
     * Get form type badge color
     */
    public function getFormTypeBadgeColorAttribute(): string
    {
        return match($this->form_type ?? $this->getFormType()) {
            'business_plan' => 'bg-blue-100 text-blue-800',
            'etude_marche' => 'bg-green-100 text-green-800',
            'evaluation_idee' => 'bg-purple-100 text-purple-800',
            'bmc' => 'bg-yellow-100 text-yellow-800',
            'bilan_competence' => 'bg-pink-100 text-pink-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'submitted' => 'blue',
            'in_review' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Brouillon',
            'submitted' => 'Soumis',
            'in_review' => 'En révision',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get the form type from the model class
     */
    protected function getFormType(): string
    {
        return match(class_basename($this)) {
            'BusinessPlan' => 'business_plan',
            'EtudeMarche' => 'etude_marche',
            'EvaluationIdee' => 'evaluation_idee',
            'Bmc' => 'bmc',
            'BilanCompetence' => 'bilan_competence',
            default => 'unknown',
        };
    }


    /**
     * Available form types
     */
    public static function formTypes(): array
    {
        return [
            'business_plan' => 'Business Plan',
            'etude_marche' => 'Étude de Marché',
            'evaluation_idee' => "Évaluation d'Idée",
            'bmc' => 'Business Model Canvas',
            'bilan_competence' => 'Bilan de Compétences',
        ];
    }
}
