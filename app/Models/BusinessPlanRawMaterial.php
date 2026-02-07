<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlanRawMaterial extends Model
{
    use HasFactory;

    protected $table = 'project_raw_materials';

    protected $fillable = [
        'business_plan_id',
        'matiere_premiere',
        'comment_procurer',
        'fournisseur_matiere',
        'sort_order',
    ];

    public function project()
    {
        return $this->belongsTo(BusinessPlan::class);
    }
    
}