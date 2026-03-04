<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RhEmployee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'matricule', 'nom', 'prenom', 'cin', 'email', 'phone',
        'poste', 'departement', 'contrat_type', 'date_embauche',
        'date_fin_contrat', 'salaire', 'address', 'gender',
        'date_naissance', 'status', 'notes', 'photo_path', 'created_by',
    ];

    protected $casts = [
        'date_embauche' => 'date',
        'date_fin_contrat' => 'date',
        'date_naissance' => 'date',
        'salaire' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFullNameAttribute(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
