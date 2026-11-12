<?php

namespace App\Livewire\Admin\Actualite;

use App\Models\Actualite;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ActualiteForm extends Component
{
    use WithFileUploads, WithPagination;

    public ?int $newsId = null;

    public bool $editMode = false;

    public $title;

    public $title_ar;

    public $excerpt;

    public $excerpt_ar;

    public $content;

    public $content_ar;

    public $image;

    public $file;

    public $newImage;

    public $category;

    public $tags_input;

    public $is_published = false;

    public bool $newsletter = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'excerpt_ar' => 'nullable|string|max:500',
            'content' => 'required|string',
            'content_ar' => 'nullable|string',
            'newImage' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category' => 'nullable|string|max:100',
            'tags_input' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ];
    }

    public function mount($id = 'new')
    {
        if ($id && $id !== 'new') {
            $this->editMode = true;
            $this->newsId = $id;

            $post = Actualite::findOrFail($id);
            $this->title = $post->title;
            $this->title_ar = $post->title_ar;
            $this->excerpt = $post->excerpt;
            $this->excerpt_ar = $post->excerpt_ar;
            $this->content = $post->content;
            $this->content_ar = $post->content_ar;
            $this->category = $post->category;
            $this->tags_input = $post->tags ? implode(', ', $post->tags) : '';
            $this->is_published = $post->is_published;
            $this->image = $post->image;
        } else {
            $this->editMode = false;
        }
    }

    public function save()
    {
        $this->validate();

        $post = $this->newsId ? Actualite::find($this->newsId) : null;

        $data = [
            'title' => $this->title,
            'title_ar' => $this->title_ar ?: null,
            'excerpt' => $this->excerpt ?: null,
            'excerpt_ar' => $this->excerpt_ar ?: null,
            'content' => $this->content,
            'content_ar' => $this->content_ar ?: null,
            'category' => $this->category ?: null,
            'tags' => $this->tags_input ? array_map('trim', explode(',', $this->tags_input)) : null,
            'is_published' => $this->is_published,
        ];

        $data['published_at'] = $this->is_published
            ? ($post?->published_at ?? now())
            : null;

        if ($this->newImage) {
            $filename = uniqid('actualite_').'.'.$this->newImage->getClientOriginalExtension();
            $this->newImage->storeAs('actualite', $filename, 'uploads');
            $data['image'] = 'uploads/actualite/'.$filename;

            $post?->deleteStoredMedia($post->image);
        }

        try {
            if ($post) {
                $post->update($data);
                AdminActivityLog::log('actualite_updated', "Updated actualite: {$post->title}", Actualite::class, $post->id);
                $this->dispatch('alert', [
                    'type' => 'success',
                    'title' => 'Succès',
                    'message' => 'Actualité mise à jour avec succès!',
                ]);
            } else {
                $data['slug'] = Actualite::generateSlug($this->title);
                $data['author_id'] = auth()->id();
                $post = Actualite::create($data);
                AdminActivityLog::log('actualite_created', "Created actualite: {$post->title}", Actualite::class, $post->id);

                $this->editMode = true;
                $this->newsId = $post->id;

                $this->dispatch('alert', [
                    'type' => 'success',
                    'title' => 'Succès',
                    'message' => 'Actualité créée avec succès!',
                ]);
            }

            // clear the new image so it's not re-uploaded next time
            if ($this->newImage) {
                $this->image = $data['image'];
                $this->newImage = null;
            }

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
        $post = $this->newsId ? Actualite::find($this->newsId) : null;

        if ($post && filled($post->image)) {
            $post->deleteStoredMedia($post->image);
            $post->update(['image' => null]);
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
        return redirect()->route('admin.actualite.index');
    }

    public function render()
    {
        $query = Actualite::with('author');

        return view('livewire.admin.actualite.actualite-form', [

        ])->layout('layouts.admin', ['header' => "Gestion d'actualités"]);
    }
}
