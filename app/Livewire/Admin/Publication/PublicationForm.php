<?php

namespace App\Livewire\Admin\Publication;

use App\Models\Publication;
use App\Models\AdminActivityLog;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class PublicationForm extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $statusFilter = 'all';
    public bool $showModal = false;
    public bool $editMode = false;
    public ?int $publicationId = null;

    public $title, $title_ar, $description, $description_ar, $file, $newFile;
    public $category, $status = 'pending', $due_date, $is_published = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'title'          => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'newFile'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:30720',
            'category'       => 'nullable|string|max:100',
            'status'         => 'in:pending,completed,overdue',
            'due_date'       => 'nullable|date',
            'is_published'   => 'boolean',
        ];
    }

    public function mount($id = 'new')
    {
        if ($id && $id !== 'new') {
            $this->editMode = true;
            $this->publicationId = $id;

            $publication = Publication::findOrFail($id);
            $this->title = $publication->title;
            $this->title_ar = $publication->title_ar;
            $this->description = $publication->description;
            $this->description_ar = $publication->description_ar;
            $this->file = $publication->file_url;
            $this->category = $publication->category;
            $this->status = $publication->status;
            $this->due_date = $publication->due_date?->format('Y-m-d');
            $this->is_published = $publication->is_published;
        } else {
            $this->editMode = false;
        }
    }

    /**
     * Validate the freshly selected file as soon as Livewire finishes the
     * temporary upload, so the author sees a problem before hitting save.
     */
    public function updatedNewFile(): void
    {
        $this->resetErrorBag('newFile');

        $this->validateOnly('newFile');
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title'          => $this->title,
            'title_ar'       => $this->title_ar ?: null,
            'description'    => $this->description ?: null,
            'description_ar' => $this->description_ar ?: null,
            'category'       => $this->category ?: null,
            'status'         => $this->status,
            'due_date'       => $this->due_date ? now()->parse($this->due_date) : null,
            'is_published'   => $this->is_published,
            'published_at'   => $this->is_published ? now() : null,
        ];

        if ($this->newFile) {
            $extension = strtolower($this->newFile->getClientOriginalExtension() ?: $this->newFile->extension());
            $filename = 'deliverable_' . Str::random(24) . '.' . $extension;

            // `uploads` disk is configured with throw=false: a failed write
            // returns false instead of raising, which used to save a record
            // pointing to a file that does not exist.
            $stored = $this->newFile->storeAs('deliverables', $filename, 'uploads');

            if (! $stored) {
                $this->addError('newFile', "Le fichier n'a pas pu être enregistré sur le serveur. Vérifiez que le dossier uploads/deliverables est accessible en écriture.");

                return null;
            }

            $data['file_url'] = 'uploads/deliverables/' . $filename;
            $data['file_type'] = $extension;
        }

        if ($this->editMode) {
            $publication = Publication::findOrFail($this->publicationId);
            $previousFile = $publication->file_url;

            $publication->update($data);

            if (isset($data['file_url']) && $previousFile && $previousFile !== $data['file_url']) {
                $publication->deleteStoredMedia($previousFile);
            }

            AdminActivityLog::log('publication_updated', "Updated publication: {$publication->title}", Publication::class, $publication->id);
            $message = 'Livrable mis à jour avec succès!';
        } else {
            $data['slug'] = Publication::generateSlug($this->title);
            $data['author_id'] = auth()->id();
            $publication = Publication::create($data);

            AdminActivityLog::log('publication_created', "Created publication: {$publication->title}", Publication::class, $publication->id);
            $message = 'Livrable ajouté avec succès!';
        }

        // Clear the temporary upload so a second save cannot create a duplicate
        // record (or store the same file twice).
        $this->newFile = null;
        $this->file = $publication->file_url;

        session()->flash('success', $message);

        return redirect()->route('admin.publication.index');
    }

    public function resetForm()
    {
        return redirect()->route('admin.publication.index');
    }

    public function render()
    {
        return view('livewire.admin.publication.publication-form', [
        ])->layout('layouts.admin', ['header' => 'Publication Management']);
    }
}
