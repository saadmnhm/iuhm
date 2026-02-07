<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlanEquipment extends Model
{
    use HasFactory;

    protected $table = 'project_equipment';

    protected $fillable = [
        'business_plan_id',
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
        return $this->belongsTo(BusinessPlan::class);
    }
    
}