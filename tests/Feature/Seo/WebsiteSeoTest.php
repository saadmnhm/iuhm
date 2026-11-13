<?php

namespace Tests\Feature\Seo;

use App\Livewire\Admin\Article\ArticleForm;
use App\Models\Actualite;
use App\Models\Article;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WebsiteSeoTest extends TestCase
{
    use RefreshDatabase;

    protected User $author;

    protected function setUp(): void
    {
        parent::setUp();

        $this->author = User::create([
            'nom' => 'Admin',
            'prenom' => 'SEO',
            'email' => 'seo-test-'.uniqid().'@example.test',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function makeArticle(array $overrides = []): Article
    {
        return Article::create(array_merge([
            'title' => 'Urbanisme durable à Casablanca',
            'slug' => 'urbanisme-durable-casablanca',
            'excerpt' => 'Un projet urbain innovant pour Casablanca.',
            'content' => '<p>Contenu de l’article sur l’urbanisme durable à Casablanca.</p>',
            'is_published' => true,
            'published_at' => now()->subHour(),
            'author_id' => $this->author->id,
        ], $overrides));
    }

    private function makeActualite(array $overrides = []): Actualite
    {
        return Actualite::create(array_merge([
            'title' => 'Nouvelle actualité',
            'slug' => 'nouvelle-actualite',
            'excerpt' => 'Résumé de l’actualité.',
            'content' => '<p>Contenu de l’actualité.</p>',
            'is_published' => true,
            'published_at' => now()->subHour(),
            'author_id' => $this->author->id,
        ], $overrides));
    }

    private function makeProject(array $overrides = []): Project
    {
        return Project::create(array_merge([
            'title' => ['fr' => 'Projet durable', 'ar' => null],
            'description' => ['fr' => '<p>Description du projet durable.</p>', 'ar' => null],
            'short_description' => ['fr' => 'Résumé du projet.', 'ar' => null],
            'slug' => 'projet-durable',
            'status' => 'in_progress',
            'sort_order' => 0,
            'is_published' => true,
            'published_at' => now()->subHour(),
            'author_id' => $this->author->id,
        ], $overrides));
    }

    public function test_an_article_renders_its_custom_seo_metadata_and_structured_data(): void
    {
        $article = $this->makeArticle([
            'seo_title' => 'Titre SEO personnalisé',
            'meta_description' => 'Description personnalisée pour Google.',
            'meta_keywords' => 'urbanisme, casablanca',
            'canonical_url' => 'https://example.test/articles/canonical',
            'robots_index' => false,
            'robots_follow' => false,
            'og_title' => 'Titre Open Graph',
            'og_description' => 'Description Open Graph.',
            'og_image' => 'uploads/seo/og.jpg',
            'twitter_card' => 'summary',
            'twitter_title' => 'Titre Twitter',
            'schema_type' => 'NewsArticle',
            'focus_keyword' => 'urbanisme',
        ]);

        $response = $this->get(route('articles.show', $article->slug));

        $response->assertOk();
        $response->assertSee('<title>Titre SEO personnalisé</title>', false);
        $response->assertSee('<meta name="description" content="Description personnalisée pour Google.">', false);
        $response->assertSee('<meta name="keywords" content="urbanisme, casablanca">', false);
        $response->assertSee('<meta name="robots" content="noindex, nofollow">', false);
        $response->assertSee('<link rel="canonical" href="https://example.test/articles/canonical">', false);
        $response->assertSee('<meta property="og:title" content="Titre Open Graph">', false);
        $response->assertSee('<meta name="twitter:card" content="summary">', false);
        $response->assertSee('<meta name="twitter:title" content="Titre Twitter">', false);
        $response->assertSee('"@type":"NewsArticle"', false);
        $response->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_actualite_and_project_pages_render_their_own_metadata(): void
    {
        $actualite = $this->makeActualite(['seo_title' => 'SEO Actualité']);
        $project = $this->makeProject(['seo_title' => 'SEO Projet']);

        $this->get(route('actualite.show', $actualite->slug))
            ->assertOk()
            ->assertSee('<title>SEO Actualité</title>', false)
            ->assertSee('<meta name="robots" content="index, follow">', false)
            ->assertSee('"@type":"NewsArticle"', false);

        $this->get(route('projects.show', $project->slug))
            ->assertOk()
            ->assertSee('<title>SEO Projet</title>', false)
            ->assertSee('"@type":"Project"', false);
    }

    public function test_changing_a_slug_creates_a_301_redirect_from_the_old_url(): void
    {
        $article = $this->makeArticle(['slug' => 'ancien-slug']);

        $article->update(['slug' => 'nouveau-slug']);

        $this->assertDatabaseHas('seo_redirects', [
            'model_type' => 'article',
            'old_slug' => 'ancien-slug',
            'new_slug' => 'nouveau-slug',
            'status_code' => 301,
        ]);

        $this->get('/articles/ancien-slug')
            ->assertStatus(301)
            ->assertRedirect('/articles/nouveau-slug');
    }

    public function test_a_second_slug_change_repoints_older_redirects(): void
    {
        $article = $this->makeArticle(['slug' => 'slug-initial']);

        $article->update(['slug' => 'slug-intermediaire']);
        $article->update(['slug' => 'slug-final']);

        $this->get('/articles/slug-initial')
            ->assertStatus(301)
            ->assertRedirect('/articles/slug-final');

        $this->get('/articles/slug-intermediaire')
            ->assertStatus(301)
            ->assertRedirect('/articles/slug-final');
    }

    public function test_the_sitemap_only_contains_published_content(): void
    {
        $this->makeArticle(['slug' => 'article-publie']);
        $this->makeArticle(['slug' => 'article-brouillon', 'is_published' => false, 'published_at' => null]);
        $this->makeActualite(['slug' => 'actualite-publiee']);
        $this->makeActualite(['slug' => 'actualite-brouillon', 'is_published' => false, 'published_at' => null]);
        $this->makeProject(['slug' => 'projet-publie']);
        $this->makeProject(['slug' => 'projet-brouillon', 'is_published' => false]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $body = $response->getContent();

        $this->assertStringContainsString('<loc>'.route('articles.show', 'article-publie').'</loc>', $body);
        $this->assertStringContainsString('<loc>'.route('actualite.show', 'actualite-publiee').'</loc>', $body);
        $this->assertStringContainsString('<loc>'.route('projects.show', 'projet-publie').'</loc>', $body);
        $this->assertStringNotContainsString('article-brouillon', $body);
        $this->assertStringNotContainsString('actualite-brouillon', $body);
        $this->assertStringNotContainsString('projet-brouillon', $body);
        $this->assertStringContainsString('<lastmod>', $body);
    }

    public function test_robots_txt_points_to_the_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertSee('Sitemap: '.route('sitemap'), false);
        $response->assertSee('Disallow: /admin', false);
    }

    public function test_the_admin_form_loads_and_saves_every_seo_field(): void
    {
        $this->actingAs($this->author);

        $article = $this->makeArticle([
            'seo_title' => 'Ancien titre SEO',
            'meta_description' => 'Ancienne description.',
            'focus_keyword' => 'urbanisme',
        ]);

        Livewire::test(ArticleForm::class, ['id' => $article->id])
            ->assertSet('seo_title', 'Ancien titre SEO')
            ->assertSet('focus_keyword', 'urbanisme')
            ->set('seo_title', 'Nouveau titre SEO')
            ->set('meta_description', 'Nouvelle description optimisée pour les moteurs de recherche.')
            ->set('meta_keywords', 'urbanisme, durable')
            ->set('focus_keyword', 'urbanisme durable')
            ->set('canonical_url', 'https://example.test/articles/urbanisme')
            ->set('robots_index', false)
            ->set('robots_follow', false)
            ->set('og_title', 'Titre OG')
            ->set('og_description', 'Description OG')
            ->set('og_type', 'article')
            ->set('twitter_card', 'summary_large_image')
            ->set('twitter_title', 'Titre Twitter')
            ->set('schema_type', 'NewsArticle')
            ->set('seo_author_name', 'Auteur SEO')
            ->set('seo_publisher_name', 'Éditeur SEO')
            ->set('breadcrumb_label', 'Fil personnalisé')
            ->call('save')
            ->assertHasNoErrors();

        $article->refresh();

        $this->assertSame('Nouveau titre SEO', $article->seo_title);
        $this->assertSame('Nouvelle description optimisée pour les moteurs de recherche.', $article->meta_description);
        $this->assertSame('urbanisme, durable', $article->meta_keywords);
        $this->assertSame('urbanisme durable', $article->focus_keyword);
        $this->assertSame('https://example.test/articles/urbanisme', $article->canonical_url);
        $this->assertFalse($article->robots_index);
        $this->assertFalse($article->robots_follow);
        $this->assertSame('Titre OG', $article->og_title);
        $this->assertSame('NewsArticle', $article->schema_type);
        $this->assertSame('Auteur SEO', $article->seo_author_name);
        $this->assertSame('Fil personnalisé', $article->breadcrumb_label);
    }

    public function test_creating_content_generates_a_unique_slug_from_the_title(): void
    {
        $this->actingAs($this->author);

        Livewire::test(ArticleForm::class)
            ->set('title', 'Mon article SEO')
            ->set('content', '<p>Un contenu suffisant.</p>')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('article', [
            'title' => 'Mon article SEO',
            'slug' => 'mon-article-seo',
        ]);

        Livewire::test(ArticleForm::class)
            ->set('title', 'Mon article SEO')
            ->set('content', '<p>Un second contenu.</p>')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('article', [
            'title' => 'Mon article SEO',
            'slug' => 'mon-article-seo-2',
        ]);
    }
}
