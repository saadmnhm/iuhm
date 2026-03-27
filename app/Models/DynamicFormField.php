<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicFormField extends Model
{
    protected $fillable = [
        'dynamic_form_step_id', 'label', 'label_ar', 'field_key',
        'type', 'placeholder', 'help_text', 'options',
        'allow_multiple_files', 'is_required', 'is_full_width', 'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'allow_multiple_files' => 'boolean',
        'is_required' => 'boolean',
        'is_full_width' => 'boolean',
    ];

    public function step(): BelongsTo
    {
        return $this->belongsTo(DynamicFormStep::class, 'dynamic_form_step_id');
    }
}
