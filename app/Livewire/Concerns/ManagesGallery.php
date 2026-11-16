<?php

namespace App\Livewire\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Shared multi-image upload handling for content forms that render a
 * public image slider (Actualité, Article, Projet, CLC, ZETTAT).
 *
 * The existing single cover image is left untouched. Extra slider images
 * are stored in the model's `gallery` JSON column.
 */
trait ManagesGallery
{
    /** @var list<string> Stored gallery paths. */
    public array $gallery = [];

    /** @var array<int, TemporaryUploadedFile|null> */
    public array $newGallery = [];

    /** @var list<string> Paths removed from the form, deleted after save. */
    public array $removedGallery = [];

    /**
     * @return array<string, mixed>
     */
    protected function galleryRules(): array
    {
        return [
            'gallery' => 'array',
            'gallery.*' => 'nullable|string|max:2048',
            'newGallery' => 'array',
            'newGallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    protected function loadGallery(Model $model): void
    {
        $this->gallery = collect((array) ($model->gallery ?? []))
            ->filter(fn ($path) => filled($path))
            ->values()
            ->all();

        $this->newGallery = [];
        $this->removedGallery = [];
    }

    public function removeNewGalleryImage(int $index): void
    {
        if (array_key_exists($index, $this->newGallery)) {
            unset($this->newGallery[$index]);
            $this->newGallery = array_values($this->newGallery);
        }

        $this->resetValidation('newGallery.*');
    }

    public function removeGalleryImage(int $index): void
    {
        if (! array_key_exists($index, $this->gallery)) {
            return;
        }

        $path = $this->gallery[$index];

        unset($this->gallery[$index]);
        $this->gallery = array_values($this->gallery);

        if (filled($path)) {
            $this->removedGallery[] = $path;
        }

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Succès',
            'message' => 'Image supprimée du slider. Enregistrez pour confirmer.',
        ]);
    }

    public function moveGalleryImage(int $index, int $direction): void
    {
        $target = $index + $direction;

        if (! array_key_exists($index, $this->gallery) || ! array_key_exists($target, $this->gallery)) {
            return;
        }

        [$this->gallery[$index], $this->gallery[$target]] = [$this->gallery[$target], $this->gallery[$index]];
        $this->gallery = array_values($this->gallery);
    }

    /**
     * Store every newly selected file and merge it with the existing gallery.
     *
     * @return list<string>
     */
    protected function processGalleryUploads(string $folder): array
    {
        $folder = trim($folder, '/');
        $paths = collect($this->gallery)
            ->filter(fn ($path) => filled($path))
            ->values()
            ->all();

        foreach ($this->newGallery as $file) {
            if (! $file) {
                continue;
            }

            $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = uniqid('gallery_').'_'.Str::random(6).'.'.$extension;

            $file->storeAs($folder.'/gallery', $filename, 'uploads');

            $paths[] = 'uploads/'.$folder.'/gallery/'.$filename;
        }

        return array_values(array_unique($paths));
    }

    /**
     * Persist the in-memory gallery state after a successful save.
     *
     * @param  list<string>  $paths
     */
    protected function syncGalleryState(array $paths, Model $model): void
    {
        $this->gallery = array_values($paths);
        $this->newGallery = [];
        $this->resetValidation('newGallery.*');

        foreach ($this->removedGallery as $path) {
            $model->deleteStoredMedia($path);
        }

        $this->removedGallery = [];
    }

    /**
     * Browser-ready URL for a stored gallery path.
     */
    public function mediaUrl(?string $path): ?string
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
