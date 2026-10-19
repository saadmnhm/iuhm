<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'projects';
    protected $fillable = [
        'title',
        'title_ar',
        'description',
        'description_ar',
        'short_description',
        'short_description_ar',
        'slug',
        'featured_image',
        'gallery',
        'start_date',
        'end_date',
        'status',
        'location',
        'partner',
        'tags',
        'external_link',
        'sort_order',
        'is_published',
        'is_featured',
    ];

    protected $casts = [
        'gallery' => 'array',
        'tags' => 'array',
        'title' => 'array',
        'description' => 'array',
        'short_description' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get the localized value of a field based on the current locale.
     */
    public function getLocalized(string $field): ?string
    {
        $locale = app()->getLocale();
        $value = $this->{$field};

        if (is_array($value)) {
            return $value[$locale] ?? $value['fr'] ?? reset($value) ?? null;
        }

        return $value;
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('start_date', 'desc');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'planned' => 'Planned',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'archived' => 'Archived',
            default => $this->status,
        };
    }

    public function getStatusLabelAr(): string
    {
        return match ($this->status) {
            'planned' => 'مخطط',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'archived' => 'مؤرشف',
            default => $this->status,
        };
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug . '%')->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}