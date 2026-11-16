<?php

namespace App\Livewire\Admin\Content;

use App\Models\AdminActivityLog;
use App\Models\SectionItem;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Shared list screen for the CLC and ZETTAT content sections.
 *
 * Concrete components only declare which section they manage, so both
 * modules reuse one implementation and one Blade view.
 */
abstract class SectionItemManagement extends Component
{
    use WithPagination;

    /** Section key managed by the concrete component. */
    protected string $section;

    public string $search = '';

    public string $statusFilter = 'all';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function togglePublish(int $id): void
    {
        $item = $this->findItem($id);

        $item->update([
            'is_published' => ! $item->is_published,
            'published_at' => $item->is_published
                ? $item->published_at
                : ($item->published_at ?? now()),
        ]);

        $status = $item->is_published ? 'published' : 'unpublished';
        AdminActivityLog::log(
            "{$this->section}_{$status}",
            "Toggled {$this->section} item: {$item->title} → {$status}",
            SectionItem::class,
            $item->id
        );
    }

    public function delete(int $id): void
    {
        $item = $this->findItem($id);

        foreach ((array) ($item->gallery ?? []) as $path) {
            $item->deleteStoredMedia($path);
        }

        $item->deleteStoredMedia($item->image);

        AdminActivityLog::log(
            "{$this->section}_deleted",
            "Deleted {$this->section} item: {$item->title}",
            SectionItem::class,
            $item->id
        );

        $item->delete();

        session()->flash('success', $item->title.' supprimé avec succès!');
    }

    protected function findItem(int $id): SectionItem
    {
        return SectionItem::query()
            ->section($this->section)
            ->findOrFail($id);
    }

    public function render()
    {
        $meta = SectionItem::sectionMeta($this->section);

        $query = SectionItem::query()
            ->section($this->section)
            ->with('author');

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('title_ar', 'like', "%{$this->search}%")
                    ->orWhere('excerpt', 'like', "%{$this->search}%")
                    ->orWhere('content', 'like', "%{$this->search}%")
                    ->orWhere('category', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter === 'published') {
            $query->where('is_published', true);
        } elseif ($this->statusFilter === 'draft') {
            $query->where('is_published', false);
        }

        $items = $query->ordered()->paginate(12);

        $stats = [
            'total' => [
                'label' => 'Total '.$meta['label'],
                'icon' => $meta['icon'],
                'data' => SectionItem::section($this->section)->count(),
            ],
            'published' => [
                'label' => 'Publiés',
                'icon' => 'ri-check-line',
                'data' => SectionItem::section($this->section)->where('is_published', true)->count(),
            ],
            'drafts' => [
                'label' => 'Brouillons',
                'icon' => 'ri-file-line',
                'data' => SectionItem::section($this->section)->where('is_published', false)->count(),
            ],
            'views' => [
                'label' => 'Vues',
                'icon' => 'ri-eye-line',
                'data' => SectionItem::section($this->section)->sum('views_count'),
            ],
        ];

        return view('livewire.admin.content.section-management', [
            'items' => $items,
            'stats' => $stats,
            'section' => $this->section,
            'meta' => $meta,
            'sectionLabel' => $meta['label_text'],
        ])->layout('layouts.admin', ['header' => 'Gestion '.$meta['label_text']]);
    }
}
