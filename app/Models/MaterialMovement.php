<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialMovement extends Model
{
    protected $table = 'material_movements';

    protected $fillable = [
        'material_id', 'type', 'quantity', 'motif',
        'destination', 'notes', 'created_by',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
