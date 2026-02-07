<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DynamicForm extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'title_ar', 'introduction', 'introduction_ar',
        'slug', 'icon', 'color', 'bg_color',
        'is_active', 'has_steps', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_steps' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($form) {
            if (empty($form->slug)) {
                $form->slug = Str::slug($form->title);
            }
        });
    }

    public function steps(): HasMany
    {
        return $this->hasMany(DynamicFormStep::class)->orderBy('step_number');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(DynamicFormSubmission::class);
    }

    public function getTotalFieldsAttribute(): int
    {
        return $this->steps->sum(function ($step) {
            return $step->fields->count();
        });
    }

    public function getTotalTablesAttribute(): int
    {
        return $this->steps->sum(function ($step) {
            return $step->tables->count();
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
