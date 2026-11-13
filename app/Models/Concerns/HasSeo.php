<?php

namespace App\Models\Concerns;

use App\Models\SeoRedirect;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Shared SEO behaviour for public website content (articles, news, projects).
 *
 * Each model only has to describe how its content is exposed publicly; every
 * meta/social/structured-data value is then resolved from the same rules.
 */
trait HasSeo
{
    /**
     * Register the slug history listener.
     */
    public static function bootHasSeo(): void
    {
        static::updating(function (self $model): void {
            if (! $model->isDirty('slug')) {
                return;
            }

            $oldSlug = $model->getOriginal('slug');

            if (blank($oldSlug) || blank($model->slug) || $oldSlug === $model->slug) {
                return;
            }

            SeoRedirect::updateOrCreate(
                [
                    'model_type' => $model->seoContentType(),
                    'old_slug' => $oldSlug,
                ],
                [
                    'model_id' => $model->getKey(),
                    'new_slug' => $model->slug,
                    'status_code' => 301,
                ]
            );

            // Redirects that used to land on the previous slug are re-pointed to
            // the new one so visitors keep a single redirect hop.
            SeoRedirect::query()
                ->where('model_type', $model->seoContentType())
                ->where('new_slug', $oldSlug)
                ->where('old_slug', '!=', $model->slug)
                ->update(['new_slug' => $model->slug]);

            // A newer redirect supersedes anything that used to land on this slug.
            SeoRedirect::query()
                ->where('model_type', $model->seoContentType())
                ->where('old_slug', $model->slug)
                ->where('new_slug', '!=', $model->slug)
                ->delete();
        });
    }

    /**
     * Machine key used to scope redirects (article, actualite, project).
     */
    abstract public function seoContentType(): string;

    /**
     * Public route name used to build the canonical URL.
     */
    abstract public function seoRouteName(): string;

    /**
     * Title of the item in the active locale.
     */
    abstract public function seoContentTitle(): string;

    /**
     * Short excerpt of the item in the active locale.
     */
    abstract public function seoContentExcerpt(): ?string;

    /**
     * Main HTML body of the item in the active locale.
     */
    abstract public function seoContentHtml(): ?string;

    /**
     * Cover image URL of the item (null when none is stored).
     */
    abstract public function seoContentImageUrl(): ?string;

    /**
     * Label + URL of the listing page used in breadcrumbs.
     *
     * @return array{label: string, url: string}
     */
    abstract public function seoBreadcrumbSection(): array;

    /**
     * Default schema.org type for this content type.
     */
    public function seoDefaultSchemaType(): string
    {
        return match ($this->seoContentType()) {
            'article' => 'Article',
            'actualite' => 'NewsArticle',
            'project' => 'Project',
            default => 'WebPage',
        };
    }

    public function seoPreferredTitle(): string
    {
        $title = trim((string) $this->seoContentTitle());

        return $title !== '' ? $title : (string) config('app.name');
    }

    /**
     * Final <title> value: a custom SEO title is used as-is, otherwise the
     * content title is suffixed with the site name.
     */
    public function seoMetaTitle(): string
    {
        if (filled($this->seo_title)) {
            return trim((string) $this->seo_title);
        }

        $brand = (string) config('app.name');

        return $this->seoPreferredTitle() === $brand
            ? $brand
            : $this->seoPreferredTitle().' | '.$brand;
    }

    public function seoMetaDescription(): ?string
    {
        if (filled($this->meta_description)) {
            return trim((string) $this->meta_description);
        }

        $source = $this->seoContentExcerpt() ?: $this->seoContentHtml();

        if (blank($source)) {
            return null;
        }

        $text = trim((string) preg_replace('/\s+/', ' ', strip_tags($source)));

        return $text !== '' ? Str::limit($text, 160) : null;
    }

    /**
     * @return list<string>
     */
    public function seoKeywordsList(): array
    {
        if (filled($this->meta_keywords)) {
            return collect(explode(',', (string) $this->meta_keywords))
                ->map(fn (string $keyword) => trim($keyword))
                ->filter()
                ->values()
                ->all();
        }

        return collect((array) ($this->tags ?? []))
            ->map(fn ($tag) => trim((string) $tag))
            ->filter()
            ->values()
            ->all();
    }

    public function seoCanonicalUrl(): string
    {
        if (filled($this->canonical_url)) {
            return trim((string) $this->canonical_url);
        }

        return route($this->seoRouteName(), ['slug' => $this->slug]);
    }

    public function seoRobots(): string
    {
        return sprintf(
            '%s, %s',
            $this->robots_index ? 'index' : 'noindex',
            $this->robots_follow ? 'follow' : 'nofollow'
        );
    }

    public function seoImageUrl(): ?string
    {
        return $this->resolveMediaUrl($this->seo_image ?: $this->seoContentImageUrl());
    }

    public function seoOgImageUrl(): ?string
    {
        return $this->resolveMediaUrl($this->og_image) ?: $this->seoImageUrl();
    }

    public function seoTwitterImageUrl(): ?string
    {
        return $this->resolveMediaUrl($this->twitter_image) ?: $this->seoOgImageUrl();
    }

    public function seoOgType(): string
    {
        return filled($this->og_type) ? (string) $this->og_type : 'article';
    }

    public function seoTwitterCard(): string
    {
        return filled($this->twitter_card) ? (string) $this->twitter_card : 'summary_large_image';
    }

    public function seoOgTitle(): string
    {
        return filled($this->og_title) ? trim((string) $this->og_title) : $this->seoMetaTitle();
    }

    public function seoOgDescription(): ?string
    {
        return filled($this->og_description) ? trim((string) $this->og_description) : $this->seoMetaDescription();
    }

    public function seoTwitterTitle(): string
    {
        return filled($this->twitter_title) ? trim((string) $this->twitter_title) : $this->seoOgTitle();
    }

    public function seoTwitterDescription(): ?string
    {
        return filled($this->twitter_description)
            ? trim((string) $this->twitter_description)
            : $this->seoOgDescription();
    }

    public function seoSchemaType(): string
    {
        return filled($this->schema_type) ? (string) $this->schema_type : $this->seoDefaultSchemaType();
    }

    /**
     * Optional custom JSON-LD overrides saved from the admin form.
     *
     * @return array<string, mixed>
     */
    public function seoSchemaOverrides(): array
    {
        $data = $this->schema_data;

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        return is_array($data) ? $data : [];
    }

    public function seoAuthorName(): string
    {
        if (filled($this->seo_author_name)) {
            return trim((string) $this->seo_author_name);
        }

        if (method_exists($this, 'author') && $this->author) {
            return (string) $this->author->name;
        }

        return (string) config('app.name');
    }

    public function seoPublisherName(): string
    {
        if (filled($this->seo_publisher_name)) {
            return trim((string) $this->seo_publisher_name);
        }

        return (string) config('app.name');
    }

    public function seoPublishedAt(): ?Carbon
    {
        $value = $this->schema_published_at ?: $this->published_at ?: $this->created_at;

        return $value ? Carbon::parse($value) : null;
    }

    public function seoModifiedAt(): ?Carbon
    {
        $value = $this->schema_modified_at ?: $this->updated_at;

        return $value ? Carbon::parse($value) : null;
    }

    public function seoBreadcrumbName(): string
    {
        return filled($this->breadcrumb_label)
            ? trim((string) $this->breadcrumb_label)
            : $this->seoPreferredTitle();
    }

    public function seoBreadcrumbsEnabled(): bool
    {
        return (bool) ($this->breadcrumb_enabled ?? true);
    }

    /**
     * Build the breadcrumb trail (home > listing > current item).
     *
     * @return list<array{name: string, url: string|null}>
     */
    public function seoBreadcrumbs(): array
    {
        if (! $this->seoBreadcrumbsEnabled()) {
            return [];
        }

        $section = $this->seoBreadcrumbSection();

        return [
            ['name' => __('ui.home'), 'url' => route('home')],
            ['name' => $section['label'], 'url' => $section['url']],
            ['name' => $this->seoBreadcrumbName(), 'url' => $this->seoCanonicalUrl()],
        ];
    }

    /**
     * Generate a unique slug for this model, ignoring the current record.
     */
    public function uniqueSeoSlug(string $value): string
    {
        $base = Str::slug($value);

        if ($base === '') {
            $base = 'contenu';
        }

        $query = static::query();

        if (method_exists($this, 'trashed')) {
            $query->withTrashed();
        }

        if ($this->exists) {
            $query->whereKeyNot($this->getKey());
        }

        $slug = $base;
        $suffix = 2;

        while ((clone $query)->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
