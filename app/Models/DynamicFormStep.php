<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DynamicFormStep extends Model
{
    protected $fillable = [
        'dynamic_form_id', 'title', 'title_ar',
        'description', 'step_number', 'sort_order',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(DynamicForm::class, 'dynamic_form_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(DynamicFormField::class)->orderBy('sort_order');
    }

    public function tables(): HasMany
    {
        return $this->hasMany(DynamicFormTable::class)->orderBy('sort_order');
    }

    /**
     * Get all elements (fields + tables) merged and sorted by sort_order
     */
    public function getElementsAttribute(): \Illuminate\Support\Collection
    {
        $fields = $this->fields->map(function ($f) {
            $f->element_type = 'field';
            return $f;
        });

        $tables = $this->tables->map(function ($t) {
            $t->element_type = 'table';
            return $t;
        });

        return $fields->concat($tables)->sortBy('sort_order')->values();
    }
}
