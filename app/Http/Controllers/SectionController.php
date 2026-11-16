<?php

namespace App\Http\Controllers;

use App\Models\SectionItem;
use App\Models\SeoRedirect;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Public listing + detail pages for the CLC and ZETTAT content sections.
 *
 * Both sections share the same views; only the section key and its labels
 * change, which keeps the implementation identical to the other website
 * rubriques (Actualité, Article, Projet).
 */
class SectionController extends Controller
{
    public function clcIndex(Request $request): View
    {
        return $this->index($request, SectionItem::SECTION_CLC);
    }

    public function clcShow(string $slug): View|RedirectResponse
    {
        return $this->show(SectionItem::SECTION_CLC, $slug);
    }

    public function zettatIndex(Request $request): View
    {
        return $this->index($request, SectionItem::SECTION_ZETTAT);
    }

    public function zettatShow(string $slug): View|RedirectResponse
    {
        return $this->show(SectionItem::SECTION_ZETTAT, $slug);
    }

    protected function index(Request $request, string $section): View
    {
        $meta = SectionItem::sectionMeta($section);
        $search = trim((string) $request->query('search', ''));
        $category = trim((string) $request->query('category', ''));

        $query = SectionItem::query()
            ->section($section)
            ->published()
            ->latest('published_at');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_ar', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('excerpt_ar', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        $items = $query->paginate(9)->withQueryString();

        $categories = SectionItem::query()
            ->section($section)
            ->published()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        $latestItems = SectionItem::query()
            ->section($section)
            ->published()
            ->latest('published_at')
            ->limit(4)
            ->get();

        return view('livewire.site.sections.index', [
            'section' => $section,
            'meta' => $meta,
            'items' => $items,
            'categories' => $categories,
            'latestItems' => $latestItems,
            'search' => $search,
            'pageSlug' => $category,
        ]);
    }

    protected function show(string $section, string $slug): View|RedirectResponse
    {
        $item = SectionItem::with('author')
            ->where('section', $section)
            ->where('slug', $slug)
            ->published()
            ->first();

        if (! $item) {
            $redirect = SeoRedirect::targetFor($section, $slug);

            if ($redirect && $redirect->new_slug !== $slug) {
                return redirect()->to(
                    route($section.'.show', $redirect->new_slug),
                    $redirect->status_code
                );
            }

            abort(404);
        }

        $item->increment('views_count');
        $item->refresh()->load('author');

        $relatedItems = $item->relatedItems(3);

        return view('livewire.site.sections.show', [
            'section' => $section,
            'meta' => SectionItem::sectionMeta($section),
            'item' => $item,
            'relatedItems' => $relatedItems,
        ]);
    }
}
