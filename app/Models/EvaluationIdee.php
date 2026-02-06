<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationIdee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'evaluation_idees';

    protected $fillable = [
        'candidat_id',
        'idee_projet',
        'resume_idee',
        'besoin_projet',
        'produits_services',
        'clients_identifies',
        'idee_existe_marche',
        'valeur_ajoutee',
        'resultats_prevus',
        'proches_comprennent',
        'reactions_positives',
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

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
}
