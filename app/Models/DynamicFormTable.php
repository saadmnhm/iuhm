<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DynamicFormTable extends Model
{
    protected $fillable = [
        'dynamic_form_step_id', 'title', 'title_ar', 'table_key',
        'has_dynamic_rows', 'has_total_row', 'min_rows', 'max_rows',
        'sort_order',
    ];

    protected $casts = [
        'has_dynamic_rows' => 'boolean',
        'has_total_row' => 'boolean',
    ];

    public function step(): BelongsTo
    {
        return $this->belongsTo(DynamicFormStep::class, 'dynamic_form_step_id');
    }

    public function columns(): HasMany
    {
        return $this->hasMany(DynamicFormTableColumn::class)->orderBy('sort_order');
    }

    public function fixedRows(): HasMany
    {
        return $this->hasMany(DynamicFormTableRow::class)->orderBy('sort_order');
    }
}
