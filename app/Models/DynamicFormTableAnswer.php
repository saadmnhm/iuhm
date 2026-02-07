<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicFormTableAnswer extends Model
{
    protected $fillable = [
        'dynamic_form_submission_id', 'dynamic_form_table_id',
        'table_key', 'row_index', 'column_key', 'value',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(DynamicFormSubmission::class, 'dynamic_form_submission_id');
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(DynamicFormTable::class, 'dynamic_form_table_id');
    }
}
