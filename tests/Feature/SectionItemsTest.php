<?php

namespace Tests\Feature;

use App\Livewire\Admin\Content\ClcForm;
use App\Livewire\Admin\Content\ClcManagement;
use App\Livewire\Admin\Content\ZettatForm;
use App\Models\SectionItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SectionItemsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Sections',
            'email' => 'sections-'.uniqid().'@example.test',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin);
    }

    private function makeItem(string $section = SectionItem::SECTION_CLC, array $overrides = []): SectionItem
    {
        return SectionItem::create(array_merge([
            'section' => $section,
            'title' => 'Contenu '.$section,
            'slug' => $section.'-contenu',
            'excerpt' => 'Résumé du contenu '.$section.'.',
            'content' => '<p>Contenu de la section '.$section.'</p>',
            'gallery' => ['uploads/sections/'.$section.'/gallery/a.jpg'],
            'image' => 'uploads/sections/'.$section.'/cover.jpg',
            'is_published' => true,
            'published_at' => now()->subHour(),
        ], $overrides));
    }

    public function test_carousel_images_put_the_cover_first_and_remove_duplicates(): void
    {
        $item = $this->makeItem(SectionItem::SECTION_CLC, [
            'gallery' => [
                'uploads/sections/clc/gallery/a.jpg',
                'uploads/sections/clc/cover.jpg',
                'uploads/sections/clc/gallery/b.jpg',
            ],
        ]);

        $images = $item->carousel_images;

        $this->assertCount(3, $images);
        $this->assertStringEndsWith('/uploads/sections/clc/cover.jpg', $images[0]);
        $this->assertStringEndsWith('/uploads/sections/clc/gallery/a.jpg', $images[1]);
        $this->assertStringEndsWith('/uploads/sections/clc/gallery/b.jpg', $images[2]);
        $this->assertTrue($item->hasCarouselImages());
    }

    public function test_public_clc_and_zettat_pages_list_only_published_items_from_their_section(): void
    {
        $this->makeItem(SectionItem::SECTION_CLC, ['title' => 'CLC publié']);
        $this->makeItem(SectionItem::SECTION_CLC, [
            'title' => 'CLC brouillon',
            'slug' => 'clc-brouillon',
            'is_published' => false,
            'published_at' => null,
        ]);
        $this->makeItem(SectionItem::SECTION_ZETTAT, [
            'title' => 'ZETTAT publié',
            'slug' => 'zettat-publie',
        ]);

        $this->get(route('clc.index'))
            ->assertOk()
            ->assertSee('CLC publié')
            ->assertDontSee('CLC brouillon')
            ->assertDontSee('ZETTAT publié');

        $this->get(route('zettat.index'))
            ->assertOk()
            ->assertSee('ZETTAT publié')
            ->assertDontSee('CLC publié');
    }

    public function test_public_detail_page_renders_the_carousel_and_increments_views(): void
    {
        $item = $this->makeItem(SectionItem::SECTION_CLC, [
            'title' => 'Détail CLC',
            'slug' => 'detail-clc',
            'gallery' => ['uploads/sections/clc/gallery/a.jpg', 'uploads/sections/clc/gallery/b.jpg'],
        ]);

        $this->get(route('clc.show', $item->slug))
            ->assertOk()
            ->assertSee('Détail CLC')
            ->assertSee('aria-roledescription="carousel"', false)
            ->assertSee('aria-label="Image précédente"', false);

        $this->assertSame(1, $item->fresh()->views_count);
    }

    public function test_draft_detail_page_is_not_publicly_accessible(): void
    {
        $item = $this->makeItem(SectionItem::SECTION_ZETTAT, [
            'slug' => 'zettat-brouillon',
            'is_published' => false,
            'published_at' => null,
        ]);

        $this->get(route('zettat.show', $item->slug))->assertNotFound();
    }

    public function test_clc_admin_form_creates_and_updates_an_item_inside_the_right_section(): void
    {
        Livewire::test(ClcForm::class)
            ->set('title', 'Nouveau contenu CLC')
            ->set('excerpt', 'Un résumé.')
            ->set('is_published', true)
            ->call('save')
            ->assertHasNoErrors();

        $item = SectionItem::firstOrFail();

        $this->assertSame(SectionItem::SECTION_CLC, $item->section);
        $this->assertSame('Nouveau contenu CLC', $item->title);
        $this->assertSame('nouveau-contenu-clc', $item->slug);
        $this->assertTrue($item->is_published);

        Livewire::test(ClcForm::class, ['id' => $item->id])
            ->set('title', 'Contenu CLC modifié')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(1, SectionItem::count());
        $this->assertSame('Contenu CLC modifié', $item->fresh()->title);
        $this->assertSame('nouveau-contenu-clc', $item->fresh()->slug);
    }

    public function test_zettat_admin_form_never_touches_clc_items(): void
    {
        $clc = $this->makeItem(SectionItem::SECTION_CLC, ['slug' => 'clc-existant']);

        Livewire::test(ZettatForm::class)
            ->set('title', 'Contenu ZETTAT')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(1, SectionItem::section(SectionItem::SECTION_CLC)->count());
        $this->assertSame(1, SectionItem::section(SectionItem::SECTION_ZETTAT)->count());
        $this->assertSame('Contenu ZETTAT', SectionItem::section(SectionItem::SECTION_ZETTAT)->firstOrFail()->title);
        $this->assertNotSame($clc->id, SectionItem::section(SectionItem::SECTION_ZETTAT)->firstOrFail()->id);
    }

    public function test_admin_management_only_lists_and_toggles_items_from_its_section(): void
    {
        $clc = $this->makeItem(SectionItem::SECTION_CLC, ['title' => 'CLC visible']);
        $this->makeItem(SectionItem::SECTION_ZETTAT, ['title' => 'ZETTAT visible']);

        Livewire::test(ClcManagement::class)
            ->assertSee('CLC visible')
            ->assertDontSee('ZETTAT visible')
            ->call('togglePublish', $clc->id)
            ->assertHasNoErrors();

        $this->assertFalse($clc->fresh()->is_published);
    }

    public function test_admin_management_deletes_only_the_selected_item(): void
    {
        $clc = $this->makeItem(SectionItem::SECTION_CLC);
        $this->makeItem(SectionItem::SECTION_ZETTAT);

        Livewire::test(ClcManagement::class)
            ->call('delete', $clc->id)
            ->assertHasNoErrors();

        $this->assertSoftDeleted('section_items', ['id' => $clc->id]);
        $this->assertSame(1, SectionItem::count());
    }

    public function test_navigation_contains_the_two_new_sections(): void
    {
        $this->assertDatabaseHas('navigation_items', ['url' => '/clc', 'label' => 'CLC']);
        $this->assertDatabaseHas('navigation_items', ['url' => '/zettat', 'label' => 'ZETTAT']);
    }

    public function test_sitemap_lists_published_section_items(): void
    {
        $this->makeItem(SectionItem::SECTION_CLC, ['slug' => 'clc-sitemap']);
        $this->makeItem(SectionItem::SECTION_ZETTAT, ['slug' => 'zettat-sitemap']);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertSee(route('clc.show', 'clc-sitemap'), false)
            ->assertSee(route('zettat.show', 'zettat-sitemap'), false);
    }
}
