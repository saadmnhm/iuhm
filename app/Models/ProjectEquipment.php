<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'equipement',
        'reference',
        'prix_equipement',
        'sort_order',
    ];

    protected $casts = [
        'prix_equipement' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}