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
        'profile_image',
        'age',
        'gender',
        'address',
        'email',
        'phone',
        // Step 1 - Project Info
        'project_name',
        'ceo_name',
        'description',
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
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
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
}