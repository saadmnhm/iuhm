<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BilanCompetence extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bilan_competences';

    protected $fillable = [
        'candidat_id',
        // Step 1
        'qualites_defauts', 'qualites_contribution', 'defauts_freins', 'loisirs',
        // Step 2
        'niveau_etude', 'diplomes_obtenus', 'annee_obtention', 'etablissement_obtention',
        'competences_formation', 'besoin_formations', 'type_formations',
        // Step 3
        'environnement_professionnel', 'secteurs_activite',
        // Step 4
        'fonctions_envisagees', 'representation_travail',
        // Step 5
        'contraintes_acceptees', 'exigences', 'reflexions_personnelles',
        // Step 6
        'stage_societe', 'stage_lieu', 'stage_secteur', 'stage_duree',
        'stage_responsabilites', 'stage_competences', 'stage_obstacles',
        'stage_reflexions', 'stage_plu', 'stage_deplu', 'stage_appris',
        // Step 7
        'exp_societe', 'exp_lieu', 'exp_secteur', 'exp_duree',
        'exp_responsabilites', 'exp_competences', 'exp_obstacles',
        'exp_integration', 'exp_depart', 'exp_reflexions',
        // Meta
        'status', 'current_step', 'submitted_at', 'reviewed_at', 'review_notes',
    ];

    protected $casts = [
        'qualites_defauts' => 'array',
        'competences_formation' => 'array',
        'environnement_professionnel' => 'array',
        'secteurs_activite' => 'array',
        'fonctions_envisagees' => 'array',
        'representation_travail' => 'array',
        'contraintes_acceptees' => 'array',
        'exigences' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
}
