<?php

namespace App\Livewire\Concerns;

use App\Support\Seo\SeoAnalyzer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Shared state and behaviour for the WordPress-like SEO editor used by the
 * Article, Actualité and Projet administration forms.
 */
trait ManagesSeo
{
    public ?string $seo_title = null;

    public ?string $meta_description = null;

    public ?string $meta_keywords = null;

    public ?string $canonical_url = null;

    public ?string $focus_keyword = null;

    public bool $robots_index = true;

    public bool $robots_follow = true;

    public ?string $og_title = null;

    public ?string $og_description = null;

    public ?string $og_image = null;

    public ?string $og_type = 'article';

    public ?string $twitter_card = 'summary_large_image';

    public ?string $twitter_title = null;

    public ?string $twitter_description = null;

    public ?string $twitter_image = null;

    public ?string $seo_image = null;

    public ?string $schema_type = null;

    public ?string $schema_data = null;

    public ?string $seo_author_name = null;

    public ?string $seo_publisher_name = null;

    public ?string $schema_published_at = null;

    public ?string $schema_modified_at = null;

    public ?string $breadcrumb_label = null;

    public bool $breadcrumb_enabled = true;

    /** Uploads (new files selected in the SEO editor). */
    public $newSeoImage;

    public $newOgImage;

    public $newTwitterImage;

    /** Keep track of whether the user manually edited generated values. */
    public bool $slugTouched = false;

    public bool $seoTitleTouched = false;

    /**
     * Validation rules shared by every SEO-aware content form.
     *
     * @return array<string, mixed>
     */
    protected function seoRules(string $table, ?int $ignoreId = null): array
    {
        return [
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique($table, 'slug')->ignore($ignoreId),
            ],
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:2048',
            'focus_keyword' => 'nullable|string|max:120',
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:2048',
            'og_type' => 'nullable|in:article,website,product,profile,book',
            'twitter_card' => 'nullable|in:summary,summary_large_image,app,player',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:500',
            'twitter_image' => 'nullable|string|max:2048',
            'seo_image' => 'nullable|string|max:2048',
            'schema_type' => 'nullable|string|max:60',
            'schema_data' => [
                'nullable',
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (blank($value)) {
                        return;
                    }

                    json_decode((string) $value, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $fail('Les données structurées personnalisées doivent être un JSON valide.');
                    }
                },
            ],
            'seo_author_name' => 'nullable|string|max:255',
            'seo_publisher_name' => 'nullable|string|max:255',
            'schema_published_at' => 'nullable|date',
            'schema_modified_at' => 'nullable|date|after_or_equal:schema_published_at',
            'breadcrumb_label' => 'nullable|string|max:255',
            'breadcrumb_enabled' => 'boolean',
            'newSeoImage' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'newOgImage' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'newTwitterImage' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    /**
     * Fill every SEO property from an existing model.
     */
    public function loadSeo(Model $model): void
    {
        $this->seo_title = $model->seo_title;
        $this->meta_description = $model->meta_description;
        $this->meta_keywords = $model->meta_keywords;
        $this->canonical_url = $model->canonical_url;
        $this->focus_keyword = $model->focus_keyword;
        $this->robots_index = (bool) ($model->robots_index ?? true);
        $this->robots_follow = (bool) ($model->robots_follow ?? true);
        $this->og_title = $model->og_title;
        $this->og_description = $model->og_description;
        $this->og_image = $model->og_image;
        $this->og_type = $model->og_type ?: 'article';
        $this->twitter_card = $model->twitter_card ?: 'summary_large_image';
        $this->twitter_title = $model->twitter_title;
        $this->twitter_description = $model->twitter_description;
        $this->twitter_image = $model->twitter_image;
        $this->seo_image = $model->seo_image;
        $this->schema_type = $model->schema_type;
        $this->schema_data = $model->schema_data
            ? json_encode($model->schema_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            : null;
        $this->seo_author_name = $model->seo_author_name;
        $this->seo_publisher_name = $model->seo_publisher_name;
        $this->schema_published_at = $model->schema_published_at?->format('Y-m-d\TH:i');
        $this->schema_modified_at = $model->schema_modified_at?->format('Y-m-d\TH:i');
        $this->breadcrumb_label = $model->breadcrumb_label;
        $this->breadcrumb_enabled = (bool) ($model->breadcrumb_enabled ?? true);

        $this->slugTouched = filled($model->slug);
        $this->seoTitleTouched = filled($model->seo_title);
    }

    /**
     * Refresh values as the user edits the content.
     */
    public function updatedTitle($value): void
    {
        $title = trim(strip_tags((string) $value));

        if (! $this->slugTouched) {
            $this->slug = Str::slug($title);
        }

        if (! $this->seoTitleTouched) {
            $this->seo_title = $title;
            $this->og_title = $title;
            $this->twitter_title = $title;
        }
    }

    public function updatedSeoTitle($value): void
    {
        $this->seoTitleTouched = filled($value);

        if (! $this->slugTouched && filled($value)) {
            // Keep the slug in sync with a custom SEO title only when the
            // editor has not configured a slug yet.
            $this->slug = Str::slug((string) $value);
        }
    }

    public function updatedSlug($value): void
    {
        $this->slugTouched = true;

        if (filled($value) && Str::slug((string) $value) === (string) $value) {
            return;
        }

        if (filled($value)) {
            $this->slug = Str::slug((string) $value);
        }
    }

    /**
     * Normalise the slug and make it unique *before* validation runs, so the
     * `unique` rule never rejects a slug we would have suffixed anyway.
     *
     * @param  class-string<Model>  $modelClass
     */
    public function prepareSeoSlug(string $modelClass, ?Model $existing = null): void
    {
        $base = trim((string) ($this->slug ?: $this->title));
        $slug = Str::slug($base);

        if ($slug === '') {
            $slug = 'contenu';
        }

        /** @var Model $probe */
        $probe = $existing ?? new $modelClass;

        $this->slug = method_exists($probe, 'uniqueSeoSlug')
            ? $probe->uniqueSeoSlug($slug)
            : $slug;
    }

    /**
     * Build the SEO attributes to persist. Handles uploads, slug uniqueness
     * and empty values so optional fields can be cleared safely.
     *
     * @param  class-string<Model>  $modelClass
     * @return array<string, mixed>
     */
    public function seoAttributes(string $modelClass, ?Model $existing = null): array
    {
        $this->prepareSeoSlug($modelClass, $existing);
        $slug = (string) $this->slug;

        $data = [
            'slug' => $slug,
            'seo_title' => filled($this->seo_title) ? trim((string) $this->seo_title) : null,
            'meta_description' => filled($this->meta_description) ? trim((string) $this->meta_description) : null,
            'meta_keywords' => filled($this->meta_keywords) ? trim((string) $this->meta_keywords) : null,
            'canonical_url' => filled($this->canonical_url) ? trim((string) $this->canonical_url) : null,
            'focus_keyword' => filled($this->focus_keyword) ? trim((string) $this->focus_keyword) : null,
            'robots_index' => (bool) $this->robots_index,
            'robots_follow' => (bool) $this->robots_follow,
            'og_title' => filled($this->og_title) ? trim((string) $this->og_title) : null,
            'og_description' => filled($this->og_description) ? trim((string) $this->og_description) : null,
            'og_type' => filled($this->og_type) ? (string) $this->og_type : null,
            'twitter_card' => filled($this->twitter_card) ? (string) $this->twitter_card : null,
            'twitter_title' => filled($this->twitter_title) ? trim((string) $this->twitter_title) : null,
            'twitter_description' => filled($this->twitter_description) ? trim((string) $this->twitter_description) : null,
            'schema_type' => filled($this->schema_type) ? trim((string) $this->schema_type) : null,
            'seo_author_name' => filled($this->seo_author_name) ? trim((string) $this->seo_author_name) : null,
            'seo_publisher_name' => filled($this->seo_publisher_name) ? trim((string) $this->seo_publisher_name) : null,
            'schema_published_at' => filled($this->schema_published_at) ? Carbon::parse($this->schema_published_at) : null,
            'schema_modified_at' => filled($this->schema_modified_at) ? Carbon::parse($this->schema_modified_at) : null,
            'breadcrumb_label' => filled($this->breadcrumb_label) ? trim((string) $this->breadcrumb_label) : null,
            'breadcrumb_enabled' => (bool) $this->breadcrumb_enabled,
        ];

        $decoded = filled($this->schema_data) ? json_decode((string) $this->schema_data, true) : null;
        $data['schema_data'] = is_array($decoded) ? $decoded : null;

        // Images: text/path fields + optional new uploads.
        $imageFields = [
            'seo_image' => 'newSeoImage',
            'og_image' => 'newOgImage',
            'twitter_image' => 'newTwitterImage',
        ];

        foreach ($imageFields as $field => $property) {
            $upload = $this->{$property};

            if ($upload) {
                $filename = uniqid('seo_').'.'.$upload->getClientOriginalExtension();
                $upload->storeAs('seo', $filename, 'uploads');
                $data[$field] = 'uploads/seo/'.$filename;
                $this->{$field} = $data[$field];
                $this->{$property} = null;

                continue;
            }

            $data[$field] = filled($this->{$field}) ? trim((string) $this->{$field}) : null;
        }

        return $data;
    }

    /**
     * Everything the SEO editor view needs for its live previews.
     *
     * @param  array{content?: ?string, excerpt?: ?string, image?: ?string, base_url?: string}  $options
     * @return array<string, mixed>
     */
    public function seoEditorState(array $options = []): array
    {
        $content = (string) ($options['content'] ?? '');
        $excerpt = (string) ($options['excerpt'] ?? '');
        $fallbackImage = $options['image'] ?? null;
        $baseUrl = rtrim((string) ($options['base_url'] ?? url('/')), '/');

        $slug = trim((string) ($this->slug ?: Str::slug((string) $this->title)));
        $slug = $slug !== '' ? $slug : 'apercu-url';

        $url = filled($this->canonical_url)
            ? (string) $this->canonical_url
            : $baseUrl.'/'.$slug;

        $metaTitle = filled($this->seo_title)
            ? trim((string) $this->seo_title)
            : trim((string) $this->title);

        $description = filled($this->meta_description)
            ? trim((string) $this->meta_description)
            : trim($excerpt);

        if ($description === '') {
            $description = (string) Str::limit(trim((string) preg_replace('/\s+/', ' ', strip_tags($content))), 160);
        }

        $previewImage = $this->newOgImage
            ? $this->newOgImage->temporaryUrl()
            : ($this->seoMediaUrl($this->og_image) ?: $fallbackImage);

        $twitterImage = $this->newTwitterImage
            ? $this->newTwitterImage->temporaryUrl()
            : ($this->seoMediaUrl($this->twitter_image) ?: $previewImage);

        $seoImage = $this->newSeoImage
            ? $this->newSeoImage->temporaryUrl()
            : ($this->seoMediaUrl($this->seo_image) ?: $fallbackImage);

        $analysis = app(SeoAnalyzer::class)->analyze([
            'title' => $this->title,
            'seo_title' => $metaTitle,
            'description' => $description,
            'slug' => $slug,
            'keyword' => $this->focus_keyword,
            'content' => $content,
            'image' => $seoImage ?: $previewImage,
            'canonical_url' => $this->canonical_url,
        ]);

        $path = (string) (parse_url($url, PHP_URL_PATH) ?? '');
        $displayUrl = rtrim((string) (parse_url($url, PHP_URL_HOST) ?: config('app.url')), '/')
            .str_replace('/', ' › ', rtrim($path, '/'));

        return [
            'metaTitle' => $metaTitle !== '' ? $metaTitle : (string) config('app.name'),
            'metaTitleWithBrand' => filled($this->seo_title)
                ? $metaTitle
                : trim($metaTitle.' | '.config('app.name')),
            'section' => (string) ($options['section'] ?? ''),
            'description' => $description,
            'keywords' => $this->meta_keywords,
            'slug' => $slug,
            'url' => $url,
            'displayUrl' => $displayUrl,
            'index' => (bool) $this->robots_index,
            'follow' => (bool) $this->robots_follow,
            'ogTitle' => filled($this->og_title) ? trim((string) $this->og_title) : $metaTitle,
            'ogDescription' => filled($this->og_description) ? trim((string) $this->og_description) : $description,
            'ogImage' => $previewImage,
            'ogType' => $this->og_type ?: 'article',
            'twitterCard' => $this->twitter_card ?: 'summary_large_image',
            'twitterTitle' => filled($this->twitter_title) ? trim((string) $this->twitter_title) : $metaTitle,
            'twitterDescription' => filled($this->twitter_description) ? trim((string) $this->twitter_description) : $description,
            'twitterImage' => $twitterImage,
            'seoImage' => $seoImage,
            'analysis' => $analysis,
            'titleLength' => mb_strlen($metaTitle),
            'descriptionLength' => mb_strlen($description),
        ];
    }

    private function seoMediaUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $path = trim((string) $path);

        if (Str::startsWith($path, ['http://', 'https://', '//', 'data:'])) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (Str::startsWith($path, ['uploads/', 'storage/'])) {
            return url('/'.$path);
        }

        return asset($path);
    }
}
