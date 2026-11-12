<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Resolves stored media paths (relative uploads or absolute URLs) to public URLs.
 */
trait ResolvesMediaUrls
{
    /**
     * Turn a stored path into a browser-ready URL.
     */
    public function resolveMediaUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $path = trim($path);

        if (Str::startsWith($path, ['http://', 'https://', '//', 'data:'])) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (Str::startsWith($path, ['uploads/', 'storage/'])) {
            return url('/'.$path);
        }

        return asset($path);
    }

    /**
     * Delete a stored upload from the public uploads disk.
     */
    public function deleteStoredMedia(?string $path): void
    {
        if (blank($path) || Str::startsWith($path, ['http://', 'https://', '//'])) {
            return;
        }

        $normalized = ltrim($path, '/');

        if (! Str::startsWith($normalized, 'uploads/')) {
            return;
        }

        Storage::disk('uploads')->delete(Str::after($normalized, 'uploads/'));
    }

    /**
     * Name of the attribute holding the cover image.
     */
    protected function coverImageAttribute(): string
    {
        return 'image';
    }

    /**
     * Public URL of the cover image, or null when no image is stored.
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->{$this->coverImageAttribute()} ?? null);
    }
}
