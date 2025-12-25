<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'item',
        'price_1',
        'price_2',
        'sort_order',
    ];

    protected $casts = [
        'price_1' => 'decimal:2',
        'price_2' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}