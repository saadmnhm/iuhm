<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialCategory extends Model
{
    protected $table = 'material_categories';

    protected $fillable = ['name', 'icon', 'color', 'sort_order'];

    public function materials()
    {
        return $this->hasMany(Material::class, 'category_id');
    }
}
