<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlanPresentation extends Model
{
    use HasFactory;

    protected $table = 'project_presentations';

    protected $fillable = [
        'business_plan_id',
        'product_name_presentation',
        'presentation_methode',
        'sort_order',
    ];

    public function project()
    {
        return $this->belongsTo(BusinessPlan::class);
    }
}