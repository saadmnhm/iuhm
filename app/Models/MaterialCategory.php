<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialCategory extends Model
{
    use SoftDeletes;
    protected $table = 'material_categories';

    protected $fillable = ['name', 'icon', 'color', 'sort_order'];

    public function materials()
    {
        return $this->hasMany(Material::class, 'category_id');
    }
}
