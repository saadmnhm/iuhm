<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicFormTableColumn extends Model
{
    protected $fillable = [
        'dynamic_form_table_id', 'header', 'header_ar', 'column_key',
        'input_type', 'options', 'is_totaled', 'width', 'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_totaled' => 'boolean',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(DynamicFormTable::class, 'dynamic_form_table_id');
    }
}
