<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class DynamicFormAnswer extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'dynamic_form_submission_id', 'dynamic_form_field_id',
        'field_key', 'value',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(DynamicFormSubmission::class, 'dynamic_form_submission_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(DynamicFormField::class, 'dynamic_form_field_id');
    }
}
