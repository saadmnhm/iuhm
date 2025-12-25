<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPresentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'product_name_presentation',
        'presentation_methode',
        'sort_order',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}