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
        'total_employee_1',
        'total_employee_2',
        'sort_order',
    ];

    protected $casts = [
        'total_employee_1' => 'decimal:0',
        'total_employee_2' => 'decimal:0',

    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}