<?php

namespace App\Livewire\Admin\Content;

use App\Livewire\Concerns\ManagesGallery;
use App\Livewire\Concerns\ManagesSeo;
use App\Models\AdminActivityLog;
use App\Models\SectionItem;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Shared create/edit form for the CLC and ZETTAT content sections.
 *
 * Everything (rich content, cover, multi-image slider, SEO, publication)
 * mirrors the existing Actualité / Article / Projet forms.
 */
abstract class SectionItemForm extends Component
{
    use ManagesGallery, ManagesSeo, WithFileUploads;

    /** Section key managed by the concrete component. */
    protected string $section;

    public ?int $itemId = null;

    public bool $editMode = false;

    public $title;

    public $title_ar;

    public $slug;

    public $excerpt;

    public $excerpt_ar;

    public $content;

    public $content_ar;

    public $image;

    public $newImage;

    public $category;

    public $tags_input;

    public $is_published = false;

    public $sort_order = 0;

    protected $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        return array_merge([
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'excerpt_ar' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'content_ar' => 'nullable|string',
            'newImage' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category' => 'nullable|string|max:100',
            'tags_input' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ], $this->seoRules('section_items', $this->itemId), $this->galleryRules());
    }

    public function mount($id = 'new'): void
    {
        if ($id && $id !== 'new') {
            $this->editMode = true;
            $this->itemId = (int) $id;

            $item = SectionItem::query()
                ->section($this->section)
                ->findOrFail($id);

            $this->title = $item->title;
            $this->title_ar = $item->title_ar;
            $this->slug = $item->slug;
            $this->excerpt = $item->excerpt;
            $this->excerpt_ar = $item->excerpt_ar;
            $this->content = $item->content;
            $this->content_ar = $item->content_ar;
            $this->category = $item->category;
            $this->tags_input = $item->tags ? implode(', ', $item->tags) : '';
            $this->is_published = $item->is_published;
            $this->sort_order = $item->sort_order;
            $this->image = $item->image;
            $this->loadGallery($item);
            $this->loadSeo($item);

            return;
        }

        $this->editMode = false;
        $this->slugTouched = false;
        $this->seoTitleTouched = false;
        $this->sort_order = 0;
    }

    public function save()
    {
        $item = $this->itemId
            ? SectionItem::query()->section($this->section)->find($this->itemId)
            : null;

        $this->prepareSeoSlug(SectionItem::class, $item);
        $this->validate();

        $data = [
            'title' => $this->title,
            'title_ar' => $this->title_ar ?: null,
            'excerpt' => $this->excerpt ?: null,
            'excerpt_ar' => $this->excerpt_ar ?: null,
            'content' => $this->content ?: null,
            'content_ar' => $this->content_ar ?: null,
            'category' => $this->category ?: null,
            'tags' => $this->tags_input ? array_map('trim', explode(',', $this->tags_input)) : null,
            'is_published' => $this->is_published,
            'sort_order' => (int) ($this->sort_order ?: 0),
        ];

        $data['published_at'] = $this->is_published
            ? ($item?->published_at ?? now())
            : null;

        if ($this->newImage) {
            $filename = uniqid($this->section.'_').'.'.$this->newImage->getClientOriginalExtension();
            $this->newImage->storeAs('sections/'.$this->section, $filename, 'uploads');
            $data['image'] = 'uploads/sections/'.$this->section.'/'.$filename;

            $item?->deleteStoredMedia($item->image);
        }

        $data['gallery'] = $this->processGalleryUploads('sections/'.$this->section);

        $data = array_merge($data, $this->seoAttributes(SectionItem::class, $item));

        try {
            if ($item) {
                $item->update($data);

                AdminActivityLog::log(
                    "{$this->section}_item_updated",
                    "Updated {$this->section} item: {$item->title}",
                    SectionItem::class,
                    $item->id
                );

                $message = 'Contenu mis à jour avec succès!';
            } else {
                $data['section'] = $this->section;
                $data['author_id'] = auth()->id();

                $item = SectionItem::create($data);

                AdminActivityLog::log(
                    "{$this->section}_item_created",
                    "Created {$this->section} item: {$item->title}",
                    SectionItem::class,
                    $item->id
                );

                $this->editMode = true;
                $this->itemId = $item->id;

                $message = 'Contenu créé avec succès!';
            }

            $this->syncGalleryState($data['gallery'] ?? [], $item);

            if ($this->newImage) {
                $this->image = $data['image'];
                $this->newImage = null;
            }

            $this->dispatch('alert', [
                'type' => 'success',
                'title' => 'Succès',
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            report($e);

            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Erreur',
                'message' => 'Une erreur est survenue lors de l\'enregistrement.',
            ]);
        }
    }

    public function removeImage(): void
    {
        $item = $this->itemId
            ? SectionItem::query()->section($this->section)->find($this->itemId)
            : null;

        if ($item && filled($item->image)) {
            $item->deleteStoredMedia($item->image);
            $item->update(['image' => null]);
        }

        $this->image = null;
        $this->newImage = null;
        $this->resetValidation('newImage');

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Succès',
            'message' => 'Image de couverture supprimée.',
        ]);
    }

    public function resetForm()
    {
        return redirect()->route('admin.'.$this->section.'.index');
    }

    public function render()
    {
        $meta = SectionItem::sectionMeta($this->section);

        $seo = $this->seoEditorState([
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'image' => $this->newImage ? $this->newImage->temporaryUrl() : $this->mediaUrl($this->image),
            'base_url' => url($this->section),
            'section' => $meta['label_text'],
        ]);

        return view('livewire.admin.content.section-form', [
            'section' => $this->section,
            'meta' => $meta,
            'sectionLabel' => $meta['label_text'],
            'seo' => $seo,
        ])->layout('layouts.admin', ['header' => 'Gestion '.$meta['label_text']]);
    }
}
