<?php

namespace App\Models;

use App\Models\Concerns\HasGallery;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\ResolvesMediaUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Content items for the CLC and ZETTAT public sections.
 *
 * Both sections share the same structure; the `section` column keeps their
 * records, routes, administration screens and SEO history independent.
 */
class SectionItem extends Model
{
    use HasGallery, HasSeo, ResolvesMediaUrls, SoftDeletes;

    public const SECTION_CLC = 'clc';

    public const SECTION_ZETTAT = 'zettat';

    /**
     * Publicly available sections.
     *
     * @var array<string, array{label: string, label_key: string, description_key: string, badge_key: string, icon: string}>
     */
    public const SECTIONS = [
        self::SECTION_CLC => [
            'label' => 'CLC',
            'label_key' => 'ui.nav_clc',
            'description_key' => 'ui.clc_description',
            'badge_key' => 'ui.clc_badge',
            'icon' => 'ri-community-line',
        ],
        self::SECTION_ZETTAT => [
            'label' => 'ZETTAT',
            'label_key' => 'ui.nav_zettat',
            'description_key' => 'ui.zettat_description',
            'badge_key' => 'ui.zettat_badge',
            'icon' => 'ri-map-pin-2-line',
        ],
    ];

    protected $fillable = [
        'section',
        'title',
        'title_ar',
        'slug',
        'excerpt',
        'excerpt_ar',
        'content',
        'content_ar',
        'image',
        'gallery',
        'category',
        'tags',
        'is_published',
        'published_at',
        'author_id',
        'views_count',
        'sort_order',
        'seo_title', 'meta_description', 'meta_keywords', 'canonical_url', 'focus_keyword',
        'robots_index', 'robots_follow',
        'og_title', 'og_description', 'og_image', 'og_type',
        'twitter_card', 'twitter_title', 'twitter_description', 'twitter_image',
        'seo_image', 'schema_type', 'schema_data',
        'seo_author_name', 'seo_publisher_name', 'schema_published_at', 'schema_modified_at',
        'breadcrumb_label', 'breadcrumb_enabled',
    ];

    protected $casts = [
        'gallery' => 'array',
        'tags' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'sort_order' => 'integer',
        'robots_index' => 'boolean',
        'robots_follow' => 'boolean',
        'breadcrumb_enabled' => 'boolean',
        'schema_data' => 'array',
        'schema_published_at' => 'datetime',
        'schema_modified_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopeSection($query, string $section)
    {
        return $query->where('section', $section);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at')->orderByDesc('id');
    }

    /**
     * Validate/normalise a section key.
     */
    public static function normalizeSection(string $section): string
    {
        return array_key_exists($section, self::SECTIONS) ? $section : self::SECTION_CLC;
    }

    /**
     * Metadata (labels, icons) used by the public pages and admin screens.
     *
     * @return array{key: string, label: string, label_key: string, label_text: string, description_key: string, description_text: string, badge_key: string, badge_text: string, icon: string}
     */
    public static function sectionMeta(string $section): array
    {
        $section = self::normalizeSection($section);
        $meta = self::SECTIONS[$section];

        return [
            'key' => $section,
            'label' => $meta['label'],
            'label_key' => $meta['label_key'],
            'label_text' => __($meta['label_key']) === $meta['label_key'] ? $meta['label'] : __($meta['label_key']),
            'description_key' => $meta['description_key'],
            'description_text' => __($meta['description_key']) === $meta['description_key'] ? '' : __($meta['description_key']),
            'badge_key' => $meta['badge_key'],
            'badge_text' => __($meta['badge_key']) === $meta['badge_key'] ? $meta['label'] : __($meta['badge_key']),
            'icon' => $meta['icon'],
        ];
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

    public function getTranslatedContent(): ?string
    {
        return $this->getLocalized('content');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->image);
    }

    public function getReadingTimeAttribute(): int
    {
        $words = Str::wordCount(strip_tags((string) $this->getTranslatedContent()));

        return max(1, (int) ceil($words / 200));
    }

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
     * Published items from the same section.
     */
    public function relatedItems(int $limit = 3): Collection
    {
        $exclude = $this->getKey();

        $related = static::query()
            ->section($this->section)
            ->published()
            ->where('id', '!=', $exclude)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();

        if ($related->count() < $limit) {
            $related = $related->merge(
                static::query()
                    ->section($this->section)
                    ->published()
                    ->where('id', '!=', $exclude)
                    ->whereNotIn('id', $related->pluck('id')->all())
                    ->orderByDesc('published_at')
                    ->orderByDesc('id')
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

    public function seoContentType(): string
    {
        return $this->section ?: self::SECTION_CLC;
    }

    public function seoRouteName(): string
    {
        return $this->seoContentType().'.show';
    }

    public function seoContentTitle(): string
    {
        return (string) $this->getLocalized('title');
    }

    public function seoContentExcerpt(): ?string
    {
        return $this->getLocalized('excerpt');
    }

    public function seoContentHtml(): ?string
    {
        return $this->getTranslatedContent();
    }

    public function seoContentImageUrl(): ?string
    {
        return $this->image_url;
    }

    /**
     * @return array{label: string, url: string}
     */
    public function seoBreadcrumbSection(): array
    {
        $meta = static::sectionMeta($this->seoContentType());

        return [
            'label' => $meta['label_text'],
            'url' => route($this->seoContentType().'.index'),
        ];
    }
}
