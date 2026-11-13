<?php

namespace Tests\Unit;

use App\Support\Seo\SeoAnalyzer;
use Tests\TestCase;

class SeoAnalyzerTest extends TestCase
{
    public function test_a_well_optimised_item_reaches_a_good_score(): void
    {
        $content = '<p>Urbanisme durable : la ville de Casablanca accélère sa transition. '
            .str_repeat('Ce projet améliore le cadre de vie des habitants. ', 60)
            .'<a href="/projets">Voir les projets</a> '
            .'<a href="https://example.org/etude">Étude externe</a>'
            .'<img src="/img.jpg" alt="Quartier durable de Casablanca"></p>';

        $result = (new SeoAnalyzer)->analyze([
            'seo_title' => 'Urbanisme durable à Casablanca : le guide complet',
            'description' => 'Découvrez comment Casablanca développe un urbanisme durable au service des habitants et des quartiers.',
            'keyword' => 'urbanisme durable',
            'slug' => 'urbanisme-durable-casablanca',
            'content' => $content,
            'image' => 'https://example.test/image.jpg',
            'canonical_url' => 'https://example.test/articles/urbanisme-durable-casablanca',
        ]);

        $this->assertGreaterThanOrEqual(80, $result['score']);
        $this->assertSame('good', $result['status']);
        $this->assertNotEmpty($result['checks']);
    }

    public function test_missing_keyword_produces_errors_and_recommendations(): void
    {
        $result = (new SeoAnalyzer)->analyze([
            'seo_title' => 'Titre',
            'description' => 'Court',
            'keyword' => '',
            'slug' => 'titre',
            'content' => '<p>Texte</p>',
        ]);

        $checks = collect($result['checks'])->keyBy('id');

        $this->assertSame('error', $checks['focus_keyword']['status']);
        $this->assertSame('error', $checks['keyword_in_title']['status']);
        $this->assertSame('improvement', $checks['content_length']['status']);
        $this->assertLessThan(50, $result['score']);
    }
}
