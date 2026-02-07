<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HasFormSubmission;

class Bmc extends Model
{
    use HasFactory, SoftDeletes, HasFormSubmission;

    protected $table = 'bmcs';

    protected $fillable = [
        'candidat_id',
        'form_type',
        'partenaires_cles',
        'activites_cles',
        'proposition_valeur',
        'relations_clients',
        'segments_clientele',
        'ressources_cles',
        'canaux',
        'structure_couts',
        'flux_revenus',
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
