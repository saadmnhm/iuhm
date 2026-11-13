<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\ResolvesMediaUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasSeo, ResolvesMediaUrls, SoftDeletes;

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
        'published_at',
        'views_count',
        'author_id',
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
        'title' => 'array',
        'description' => 'array',
        'short_description' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'robots_index' => 'boolean',
        'robots_follow' => 'boolean',
        'breadcrumb_enabled' => 'boolean',
        'schema_data' => 'array',
        'schema_published_at' => 'datetime',
        'schema_modified_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get the author of the project.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the localized value of a field based on the current locale.
     */
    public function getLocalized(string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();
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

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'planned' => 'bg-amber-100 text-amber-700',
            'in_progress' => 'bg-blue-100 text-blue-700',
            'completed' => 'bg-emerald-100 text-emerald-700',
            'archived' => 'bg-slate-100 text-slate-700',
            default => 'bg-slate-100 text-slate-700',
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

    protected function coverImageAttribute(): string
    {
        return 'featured_image';
    }

    /**
     * Public URL of the featured image (null when no image is stored).
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->featured_image);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->getFeaturedImageUrlAttribute();
    }

    /**
     * Localized status label for public pages.
     */
    public function getStatusLabelLocalizedAttribute(): string
    {
        $key = 'ui.project_status_'.$this->status;
        $translated = __($key);

        return $translated === $key ? (string) $this->status : $translated;
    }

    /**
     * Other published projects sharing tags or status with this one.
     * Falls back to the most recent projects so the section is never empty.
     */
    public function relatedProjects(int $limit = 3): Collection
    {
        $query = static::published()->where('id', '!=', $this->getKey());
        $hasCriteria = false;

        $query->where(function ($q) use (&$hasCriteria) {
            foreach ((array) $this->tags as $tag) {
                if (blank($tag)) {
                    continue;
                }

                $q->orWhere('tags', 'like', '%'.$tag.'%');
                $hasCriteria = true;
            }
        });

        $related = $hasCriteria
            ? $query->orderByDesc('published_at')->orderByDesc('id')->limit($limit)->get()
            : collect();

        if ($related->count() < $limit) {
            $exclude = $related->pluck('id')->push($this->getKey())->all();

            $related = $related->merge(
                static::published()
                    ->whereNotIn('id', $exclude)
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
        return 'project';
    }

    public function seoRouteName(): string
    {
        return 'projects.show';
    }

    public function seoContentTitle(): string
    {
        return (string) $this->getLocalized('title');
    }

    public function seoContentExcerpt(): ?string
    {
        return $this->getLocalized('short_description');
    }

    public function seoContentHtml(): ?string
    {
        return $this->getLocalized('description');
    }

    public function seoContentImageUrl(): ?string
    {
        return $this->featured_image_url;
    }

    /**
     * @return array{label: string, url: string}
     */
    public function seoBreadcrumbSection(): array
    {
        return [
            'label' => __('ui.nav_projects'),
            'url' => route('projects.index'),
        ];
    }
}
