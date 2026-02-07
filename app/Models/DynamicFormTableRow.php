<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicFormTableRow extends Model
{
    protected $fillable = [
        'dynamic_form_table_id', 'label', 'label_ar', 'sort_order',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(DynamicFormTable::class, 'dynamic_form_table_id');
    }
}
