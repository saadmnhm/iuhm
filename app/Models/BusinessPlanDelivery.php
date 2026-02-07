<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPlanDelivery extends Model
{
    use HasFactory;

    protected $table = 'project_deliveries';

    protected $fillable = [
        'business_plan_id',
        'product_name_livraison',
        'livraison_methode',
        'sort_order',
    ];

    public function project()
    {
        return $this->belongsTo(BusinessPlan::class);
    }

}