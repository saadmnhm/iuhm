<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlanProduct extends Model
{
    use HasFactory;

    protected $table = 'project_products';

    protected $fillable = [
        'business_plan_id',
        'product_name',
        'description',
        'sort_order',
    ];

    public function project()
    {
        return $this->belongsTo(BusinessPlan::class);
    }
}