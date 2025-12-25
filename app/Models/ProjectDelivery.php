<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'product_name_livraison',
        'livraison_methode',
        'sort_order',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}