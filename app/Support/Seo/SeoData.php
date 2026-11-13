<?php

namespace App\Support\Seo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Immutable payload describing every SEO tag for one public page.
 *
 * It is built either from a content model (article, actualité, projet) or
 * manually for listing/static pages, then rendered by the <x-seo> component.
 */
class SeoData
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        /** @var list<string> */
        public array $keywords = [],
        public ?string $canonical = null,
        public string $robots = 'index, follow',
        public ?string $ogTitle = null,
        public ?string $ogDescription = null,
        public ?string $ogImage = null,
        public string $ogType = 'website',
        public string $twitterCard = 'summary_large_image',
        public ?string $twitterTitle = null,
        public ?string $twitterDescription = null,
        public ?string $twitterImage = null,
        public ?string $imageAlt = null,
        public ?string $siteName = null,
        public string $schemaType = 'WebPage',
        /** @var array<string, mixed> */
        public array $schemaOverrides = [],
        /** @var list<array{name: string, url: string|null}> */
        public array $breadcrumbs = [],
        public ?Carbon $publishedAt = null,
        public ?Carbon $modifiedAt = null,
        public ?string $author = null,
        public ?string $publisher = null,
        public ?string $locale = null,
    ) {
        $this->siteName ??= (string) config('app.name');
        $this->locale ??= str_replace('_', '-', app()->getLocale());
        $this->ogTitle ??= $this->title;
        $this->ogDescription ??= $this->description;
        $this->twitterTitle ??= $this->ogTitle;
        $this->twitterDescription ??= $this->ogDescription;
        $this->twitterImage ??= $this->ogImage;
        $this->imageAlt ??= $this->title;
    }

    /**
     * Build the SEO payload from a model using the HasSeo trait.
     */
    public static function forModel(Model $model): self
    {
        if (! method_exists($model, 'seoMetaTitle')) {
            return new self(title: (string) config('app.name'));
        }

        if (method_exists($model, 'loadMissing')) {
            $model->loadMissing('author');
        }

        $canonical = $model->seoCanonicalUrl();
        $image = $model->seoImageUrl();

        return new self(
            title: $model->seoMetaTitle(),
            description: $model->seoMetaDescription(),
            keywords: $model->seoKeywordsList(),
            canonical: $canonical,
            robots: $model->seoRobots(),
            ogTitle: $model->seoOgTitle(),
            ogDescription: $model->seoOgDescription(),
            ogImage: $model->seoOgImageUrl(),
            ogType: $model->seoOgType(),
            twitterCard: $model->seoTwitterCard(),
            twitterTitle: $model->seoTwitterTitle(),
            twitterDescription: $model->seoTwitterDescription(),
            twitterImage: $model->seoTwitterImageUrl(),
            imageAlt: $model->seoPreferredTitle(),
            schemaType: $model->seoSchemaType(),
            schemaOverrides: $model->seoSchemaOverrides(),
            breadcrumbs: $model->seoBreadcrumbs(),
            publishedAt: $model->seoPublishedAt(),
            modifiedAt: $model->seoModifiedAt(),
            author: $model->seoAuthorName(),
            publisher: $model->seoPublisherName(),
        );
    }

    /**
     * Build the SEO payload for a static/listing page.
     *
     * @param  list<array{name: string, url: string|null}>  $breadcrumbs
     */
    public static function forPage(
        string $title,
        ?string $description = null,
        ?string $canonical = null,
        array $breadcrumbs = [],
        string $schemaType = 'WebPage',
        bool $includeBrand = true,
    ): self {
        $brand = (string) config('app.name');
        $finalTitle = $includeBrand && ! str_contains($title, $brand)
            ? trim($title.' | '.$brand)
            : $title;

        return new self(
            title: $finalTitle,
            description: $description,
            canonical: $canonical ?? url()->current(),
            ogTitle: $finalTitle,
            schemaType: $schemaType,
            breadcrumbs: $breadcrumbs,
        );
    }

    /**
     * Full JSON-LD graph: main entity + breadcrumb list.
     *
     * @return array<string, mixed>
     */
    public function structuredData(): array
    {
        $graph = [$this->mainEntity()];

        if (count($this->breadcrumbs) > 0) {
            $graph[] = $this->breadcrumbList();
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mainEntity(): array
    {
        $entity = [
            '@type' => $this->schemaType,
            'name' => $this->title,
            'headline' => $this->title,
            'description' => $this->description,
            'url' => $this->canonical,
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $this->canonical,
            ],
            'inLanguage' => $this->locale,
        ];

        if (filled($this->ogImage)) {
            $entity['image'] = [
                '@type' => 'ImageObject',
                'url' => $this->ogImage,
            ];
        }

        if ($this->publishedAt) {
            $entity['datePublished'] = $this->publishedAt->toAtomString();
        }

        if ($this->modifiedAt) {
            $entity['dateModified'] = $this->modifiedAt->toAtomString();
        }

        if (filled($this->author)) {
            $entity['author'] = [
                '@type' => 'Person',
                'name' => $this->author,
            ];
        }

        if (filled($this->publisher)) {
            $entity['publisher'] = [
                '@type' => 'Organization',
                'name' => $this->publisher,
                'url' => url('/'),
            ];
        }

        if (count($this->keywords) > 0) {
            $entity['keywords'] = implode(', ', $this->keywords);
        }

        if (count($this->schemaOverrides) > 0) {
            $entity = array_replace_recursive($entity, $this->schemaOverrides);
        }

        return $entity;
    }

    /**
     * @return array<string, mixed>
     */
    private function breadcrumbList(): array
    {
        $items = [];

        foreach (array_values($this->breadcrumbs) as $position => $breadcrumb) {
            $item = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $breadcrumb['name'],
            ];

            if (filled($breadcrumb['url'] ?? null)) {
                $item['item'] = $breadcrumb['url'];
            }

            $items[] = $item;
        }

        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
}
