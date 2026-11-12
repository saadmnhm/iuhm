<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Actualite extends Model
{
    use ResolvesMediaUrls, SoftDeletes;

    protected $table = 'actualite';

    protected $fillable = [
        'title',
        'title_ar',
        'slug',
        'excerpt',
        'excerpt_ar',
        'content',
        'content_ar',
        'image',
        'category',
        'tags',
        'is_published',
        'published_at',
        'author_id',
        'views_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /**
     * Get the localized value of a field based on the current locale.
     */
    public function getLocalized(string $field): ?string
    {
        $locale = app()->getLocale();
        $localizedField = $field.'_ar';

        if ($locale === 'ar' && filled($this->{$localizedField})) {
            return $this->{$localizedField};
        }

        return $this->{$field};
    }

    /**
     * Get the translated content based on the current locale.
     */
    public function getTranslatedContent(): ?string
    {
        return $this->getLocalized('content');
    }

    /**
     * Public URL of the cover image (null when no image is stored).
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->image);
    }

    /**
     * Estimated reading time in minutes, based on the localized content.
     */
    public function getReadingTimeAttribute(): int
    {
        $words = Str::wordCount(strip_tags((string) $this->getTranslatedContent()));

        return max(1, (int) ceil($words / 200));
    }

    /**
     * Human readable category label for public pages.
     */
    public function getCategoryLabelAttribute(): ?string
    {
        if (blank($this->category)) {
            return null;
        }

        $key = 'ui.category_'.$this->category;
        $translated = __($key);

        return $translated === $key ? Str::headline($this->category) : $translated;
    }

    /**
     * Published news items that share a category or tags with this one.
     * Falls back to the most recent news so the section is never empty.
     */
    public function relatedActualites(int $limit = 3): Collection
    {
        $query = static::published()->where('id', '!=', $this->getKey());
        $hasCriteria = false;

        $query->where(function ($q) use (&$hasCriteria) {
            if (filled($this->category)) {
                $q->where('category', $this->category);
                $hasCriteria = true;
            }

            foreach ((array) $this->tags as $tag) {
                if (blank($tag)) {
                    continue;
                }

                $q->orWhere('tags', 'like', '%'.$tag.'%');
                $hasCriteria = true;
            }
        });

        $related = $hasCriteria
            ? $query->latest('published_at')->limit($limit)->get()
            : collect();

        if ($related->count() < $limit) {
            $exclude = $related->pluck('id')->push($this->getKey())->all();

            $related = $related->merge(
                static::published()
                    ->whereNotIn('id', $exclude)
                    ->latest('published_at')
                    ->limit($limit - $related->count())
                    ->get()
            );
        }

        return $related->take($limit)->values();
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug.'%')->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
