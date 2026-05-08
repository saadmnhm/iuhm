<?php

namespace App\Livewire\Admin\Blog;

use App\Models\Newsletter;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class NewsletterManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $statusFilter = 'all';
    public bool $showModal = false;
    public bool $editMode = false;
    public ?int $newsletterId = null;

    public $title, $title_ar, $content, $content_ar, $featuredImage, $newFeaturedImage;
    public $issueNumber, $is_published = false, $sent_at;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'title'              => 'required|string|max:255',
            'title_ar'           => 'nullable|string|max:255',
            'content'            => 'required|string',
            'content_ar'         => 'nullable|string',
            'newFeaturedImage'   => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'issueNumber'        => 'nullable|integer|unique:newsletters,issue_number',
            'is_published'       => 'boolean',
            'sent_at'            => 'nullable|date',
        ];
    }

    public function updatingSearch() { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->issueNumber = Newsletter::max('issue_number') + 1;
        $this->showModal = true;
        $this->editMode = false;
    }

    public function openEdit(int $id): void
    {
        $newsletter = Newsletter::findOrFail($id);
        $this->newsletterId = $newsletter->id;
        $this->title = $newsletter->title;
        $this->title_ar = $newsletter->title_ar;
        $this->content = $newsletter->content;
        $this->content_ar = $newsletter->content_ar;
        $this->featuredImage = $newsletter->featured_image;
        $this->issueNumber = $newsletter->issue_number;
        $this->is_published = $newsletter->is_published;
        $this->sent_at = $newsletter->sent_at?->format('Y-m-d H:i');
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'        => $this->title,
            'title_ar'     => $this->title_ar ?: null,
            'content'      => $this->content,
            'content_ar'   => $this->content_ar ?: null,
            'issue_number' => $this->issueNumber,
            'is_published' => $this->is_published,
        ];

        if ($this->is_published) {
            $data['published_at'] = now();
        }

        if ($this->sent_at) {
            $data['sent_at'] = now()->parse($this->sent_at);
        }

        if ($this->newFeaturedImage) {
            $filename = uniqid('newsletter_') . '.' . $this->newFeaturedImage->getClientOriginalExtension();
            $this->newFeaturedImage->storeAs('newsletters', $filename, 'uploads');
            $data['featured_image'] = 'uploads/newsletters/' . $filename;
        }

        if ($this->editMode) {
            $newsletter = Newsletter::findOrFail($this->newsletterId);
            $newsletter->update($data);
            AdminActivityLog::log('newsletter_updated', "Updated newsletter #$newsletter->issue_number", Newsletter::class, $newsletter->id);
            session()->flash('success', 'Infolettre mise à jour avec succès!');
        } else {
            $data['slug'] = Newsletter::generateSlug($this->title);
            $data['author_id'] = auth()->id();
            $newsletter = Newsletter::create($data);
            AdminActivityLog::log('newsletter_created', "Created newsletter #$newsletter->issue_number", Newsletter::class, $newsletter->id);
            session()->flash('success', 'Infolettre créée avec succès!');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function togglePublish(int $id): void
    {
        $newsletter = Newsletter::findOrFail($id);
        $newsletter->update([
            'is_published' => !$newsletter->is_published,
            'published_at' => !$newsletter->is_published ? now() : $newsletter->published_at,
        ]);
        $status = $newsletter->is_published ? 'published' : 'unpublished';
        AdminActivityLog::log("newsletter_{$status}", "Toggled newsletter #$newsletter->issue_number → {$status}", Newsletter::class, $newsletter->id);
    }

    public function delete(int $id): void
    {
        $newsletter = Newsletter::findOrFail($id);
        AdminActivityLog::log('newsletter_deleted', "Deleted newsletter #$newsletter->issue_number", Newsletter::class, $newsletter->id);
        $newsletter->delete();
        session()->flash('success', 'Infolettre supprimée avec succès!');
    }

    protected function resetForm(): void
    {
        $this->reset(['newsletterId', 'title', 'title_ar', 'content', 'content_ar', 'featuredImage', 'newFeaturedImage', 'is_published', 'sent_at']);
        $this->editMode = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Newsletter::with('author');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter === 'published') {
            $query->where('is_published', true);
        } elseif ($this->statusFilter === 'draft') {
            $query->where('is_published', false);
        }

        $newsletters = $query->latest()->paginate(15);

        return view('livewire.admin.blog.newsletter-management', [
            'newsletters' => $newsletters,
            'totalNewsletters' => Newsletter::count(),
            'publishedNewsletters' => Newsletter::where('is_published', true)->count(),
            'sentNewsletters' => Newsletter::whereNotNull('sent_at')->count(),
        ])->layout('layouts.admin', ['header' => 'Admin Management']);;
    }
}
