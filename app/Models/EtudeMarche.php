<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HasFormSubmission;

class EtudeMarche extends Model
{
    use HasFactory, SoftDeletes, HasFormSubmission;

    protected $fillable = [
        'candidat_id',
        'form_type',
        // Step 1
        'produit_service',
        'description_offre',
        'benefices_clients',
        'prix_marche',
        'controle_prix',
        // Step 2
        'type_clients',
        'caracteristiques_clientele',
        'frequence_consommation',
        'localisation_clients',
        'exigences_principales',
        // Step 3
        'nombre_concurrents_directs',
        'concurrents_indirects',
        'taille_concurrents',
        'informations_concurrents',
        'communication_concurrents',
        // Step 4
        'nombre_fournisseurs',
        'origine_fournisseurs',
        'prix_fournisseurs',
        'delais_livraison',
        'stabilite_marche',
        // Meta
        'status',
        'current_step',
        'submitted_at',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];
}
