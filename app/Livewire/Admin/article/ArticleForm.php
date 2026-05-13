<?php

namespace App\Livewire\Admin\Article;

use App\Models\Article;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ArticleForm extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $statusFilter = 'all';

    public bool $showModal = false;
    public bool $editMode = false;
    public ?int $postId = null;
    public bool $showComments = true;
    public bool $newsletter = false;
    // Form fields
    public $title, $title_ar, $excerpt, $content, $image, $newImage;
    public $category, $tags_input, $is_published = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'title'        => 'required|string|max:255',
            'title_ar'     => 'nullable|string|max:255',
            'excerpt'      => 'nullable|string|max:500',
            'content'      => 'required|string',
            'newImage'     => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'category'     => 'nullable|string|max:100',
            'tags_input'   => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ];
    }



    public function mount($id = 'new')
    {
        if ($id && $id !== 'new') {
            $this->editMode = true;
            $this->postId = $id;
            
            $post = Article::findOrFail($id);
            $this->title = $post->title;
            $this->title_ar = $post->title_ar;
            $this->excerpt = $post->excerpt;
            $this->content = $post->content;
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

        $data = [
            'title'        => $this->title,
            'title_ar'     => $this->title_ar ?: null,
            'excerpt'      => $this->excerpt ?: null,
            'content'      => $this->content,
            'category'     => $this->category ?: null,
            'tags'         => $this->tags_input ? array_map('trim', explode(',', $this->tags_input)) : null,
            'is_published' => $this->is_published,
        ];

        if ($this->is_published) {
            $data['published_at'] = now();
        }

        if ($this->newImage) {
            $filename = uniqid('article_') . '.' . $this->newImage->getClientOriginalExtension();
            $this->newImage->storeAs('article', $filename, 'uploads');
            $data['image'] = 'uploads/article/' . $filename;
        }

        try {
            if ($this->editMode) {
                $post = Article::findOrFail($this->postId);
                $post->update($data);
                AdminActivityLog::log('article_updated', "Updated article: {$post->title}", Article::class, $post->id);
                $this->dispatch('alert', [
                    'type' => 'success',
                    'title' => 'Succès',
                    'message' => 'Article mis à jour avec succès!'
                ]);
            } else {
                $data['slug'] = Article::generateSlug($this->title);
                $data['author_id'] = auth()->id();
                $post = Article::create($data);
                AdminActivityLog::log('article_created', "Created article: {$post->title}", Article::class, $post->id);
                
                $this->editMode = true;
                $this->postId = $post->id;
                
                $this->dispatch('alert', [
                    'type' => 'success',
                    'title' => 'Succès',
                    'message' => 'Article créé avec succès!'
                ]);
            }
            
            // clear the new image so it's not re-uploaded next time
            if ($this->newImage) {
                $this->image = $data['image'];
                $this->newImage = null;
            }

        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Erreur',
                'message' => 'Une erreur est survenue lors de l\'enregistrement.'
            ]);
        }
    }


    public function resetForm()
    {
        return redirect()->route('admin.article.index');
    }

    public function render()
    {
        $query = Article::with('author');



        return view('livewire.admin.article.article-form')
            ->layout('layouts.admin', ['header' => 'Gestion des Articles']);
    }
}
