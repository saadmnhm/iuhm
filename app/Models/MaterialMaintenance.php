<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialMaintenance extends Model
{
    protected $table = 'material_maintenances';

    protected $fillable = [
        'material_id', 'type_maintenance', 'description', 'cout',
        'date_maintenance', 'prochaine_maintenance', 'prestataire',
        'status', 'created_by',
    ];

    protected $casts = [
        'cout' => 'decimal:2',
        'date_maintenance' => 'date',
        'prochaine_maintenance' => 'date',
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
