<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatEvaluationGrid extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidat_id',
        'project_id',
        'admin_id',
        'date_entretien',
        'criteria_notes',
        'motivation_score',
        'profile_score',
        'viability_score',
        'total_score',
        'comment',
    ];

    protected $casts = [
        'date_entretien' => 'date',
        'criteria_notes' => 'array',
    ];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }

    public function project()
    {
        return $this->belongsTo(ProgrameList::class, 'project_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
