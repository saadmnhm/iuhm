<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        // Step 0 - Personal Info
        'candidat_id',
        'form_type',
        // Step 1 - Project Info
        'project_name',
        'description',
        'registration',
        'legal_structure',
        'resume_executif',
        // Step 2 - Market Analysis
        'public_cible',
        'concurrent',
        'volume_produits_locaux',
        'volume_demande',
        'demande_offre',
        'motivations_achat',
        'raison_choix_client',
        // Step 3 - Marketing & Timeline
        'méthodes_marketing',
        'adaptation_methodes',
        'differenciation_marketing',
        'plan_affaires',
        'obtention_financement',
        'ouverture_proces',
        'lancement_recrutement',
        'ouverture_definitive',
        'duree',
        // Step 4 - Location & Distribution
        'lieu_projet',
        'adaptation_lieu',
        'benefices_from_projet',
        'valeur_projet',
        // Step 5 - Capacities
        'step_8_1',
        'step_8_2',
        'step_8_3',
        'step_8_4',
        // Step 5 - Investment Program
        'couts_creation',
        'preparation_entreprise',
        'achat_machines',
        'achat_matieres_premieres',
        'autres_couts',
        'total',
        // Step 6 - Financial Questions
        'generer_profits',
        'projet_durable',
        // Status & Tracking
        'status',
        'current_step',
        'submitted_at',
        'reviewed_by',
        'review_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'age' => 'integer',
        'couts_creation' => 'decimal:2',
        'preparation_entreprise' => 'decimal:2',
        'achat_machines' => 'decimal:2',
        'achat_matieres_premieres' => 'decimal:2',
        'autres_couts' => 'decimal:2',
        'total' => 'decimal:2',
        'current_step' => 'integer',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
    public function products()
    {
        return $this->hasMany(ProjectProduct::class);
    }

    public function employees()
    {
        return $this->hasMany(ProjectEmployee::class);
    }

    public function presentations()
    {
        return $this->hasMany(ProjectPresentation::class);
    }

    public function deliveries()
    {
        return $this->hasMany(ProjectDelivery::class);
    }

    public function equipment()
    {
        return $this->hasMany(ProjectEquipment::class);
    }

    public function rawMaterials()
    {
        return $this->hasMany(ProjectRawMaterial::class);
    }

    public function financials()
    {
        return $this->hasOne(ProjectFinancial::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function activityLogs()
    {
        return $this->morphMany(AdminActivityLog::class, 'subject');
    }

    // Status checks
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
        return match($this->form_type) {
            'business_plan' => 'Business Plan',
            'etude_marche' => 'Étude de Marché',
            'evaluation_idee' => "Évaluation d'Idée",
            'bmc' => 'Business Model Canvas',
            'bilan_competence' => 'Bilan de Compétences',
            default => ucfirst(str_replace('_', ' ', $this->form_type ?? 'business_plan')),
        };
    }

    /**
     * Get form type badge color
     */
    public function getFormTypeBadgeColorAttribute(): string
    {
        return match($this->form_type) {
            'business_plan' => 'bg-blue-100 text-blue-800',
            'etude_marche' => 'bg-green-100 text-green-800',
            'evaluation_idee' => 'bg-purple-100 text-purple-800',
            'bmc' => 'bg-yellow-100 text-yellow-800',
            'bilan_competence' => 'bg-pink-100 text-pink-800',
            default => 'bg-gray-100 text-gray-800',
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