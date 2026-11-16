<?php

namespace App\Models\Concerns;

/**
 * Adds multi-image slider helpers to any content model that has a
 * `gallery` JSON column and a single cover image.
 *
 * The carousel always shows the cover image first, followed by the extra
 * gallery images, with duplicates removed.
 */
trait HasGallery
{
    /**
     * Browser-ready URLs for the stored gallery images.
     *
     * @return list<string>
     */
    public function getGalleryUrlsAttribute(): array
    {
        return collect((array) ($this->gallery ?? []))
            ->filter(fn ($path) => filled($path))
            ->map(fn (?string $path) => $this->resolveMediaUrl($path))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Every image that should appear in the slider (cover + gallery).
     *
     * @return list<string>
     */
    public function getCarouselImagesAttribute(): array
    {
        $images = [];

        if (filled($cover = $this->cover_image_url)) {
            $images[] = $cover;
        }

        foreach ($this->gallery_urls as $url) {
            if (! in_array($url, $images, true)) {
                $images[] = $url;
            }
        }

        return $images;
    }

    public function hasCarouselImages(): bool
    {
        return $this->carousel_images !== [];
    }
}
