<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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
    public function getElementsAttribute(): Collection
    {
        $fields = $this->fields->map(function ($f) {
            $f->element_type = 'field';
            // element_type is a presentation-only attribute, never a column.
            $f->syncOriginalAttribute('element_type');

            return $f;
        });

        $tables = $this->tables->map(function ($t) {
            $t->element_type = 'table';
            $t->syncOriginalAttribute('element_type');

            return $t;
        });

        // Questions and tables share the same sort_order sequence. created_at
        // is used as a tie-breaker for records created before the shared
        // ordering was introduced, so the original creation order is kept.
        return $fields->concat($tables)
            ->sortBy([
                ['sort_order', 'asc'],
                ['created_at', 'asc'],
                ['id', 'asc'],
            ])
            ->values();
    }
}
