<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceCategory extends Model
{
    use SoftDeletes;
    protected $table = 'finance_categories';

    protected $fillable = ['type', 'name', 'icon', 'color', 'sort_order'];

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class, 'category_id');
    }

    public function scopeRevenue($query)
    {
        return $query->where('type', 'revenue');
    }

    public function scopeDepense($query)
    {
        return $query->where('type', 'depense');
    }
}
