<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use ResolvesMediaUrls, SoftDeletes;

    protected $fillable = [
        'title',
        'title_ar',
        'slug',
        'excerpt',
        'excerpt_ar',
        'content',
        'content_ar',
        'hero_image',
        'is_published',
        'published_at',
        'sort_order',
        'meta_title',
        'meta_title_ar',
        'meta_description',
        'meta_description_ar',
    ];

    protected $casts = [
        'title' => 'array',
        'excerpt' => 'array',
        'content' => 'array',
        'hero_image' => 'string',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    /**
     * Get the localized value of a JSON field (fr fallback when locale missing).
     */
    public function getLocalized(string $field): ?string
    {
        $value = $this->{$field};

        if (is_array($value)) {
            $locale = app()->getLocale();

            return $value[$locale] ?? $value['fr'] ?? reset($value) ?: null;
        }

        return $value;
    }

    public function getHeroImageUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->hero_image);
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug.'%')->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
