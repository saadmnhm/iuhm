<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    protected $table = 'materials';

    protected $fillable = [
        'category_id', 'reference', 'name', 'description', 'quantity',
        'quantity_min', 'prix_unitaire', 'valeur_totale', 'emplacement',
        'etat', 'status', 'fournisseur', 'date_acquisition', 'date_garantie',
        'numero_serie', 'notes', 'created_by',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'valeur_totale' => 'decimal:2',
        'date_acquisition' => 'date',
        'date_garantie' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(MaterialCategory::class, 'category_id');
    }

    public function attachments()
    {
        return $this->hasMany(MaterialAttachment::class, 'material_id');
    }

    public function primaryPhoto()
    {
        return $this->hasOne(MaterialAttachment::class, 'material_id')
            ->where('is_primary', true);
    }

    public function movements()
    {
        return $this->hasMany(MaterialMovement::class, 'material_id');
    }

    public function maintenances()
    {
        return $this->hasMany(MaterialMaintenance::class, 'material_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeDisponible($query)
    {
        return $query->where('status', 'disponible');
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'quantity_min');
    }

    public static function generateReference(): string
    {
        $count = static::withTrashed()->count() + 1;
        return 'MAT-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
