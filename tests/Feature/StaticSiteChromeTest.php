<?php

namespace Tests\Feature;

use Tests\TestCase;

class StaticSiteChromeTest extends TestCase
{
    public function test_header_renders_static_navigation_without_database(): void
    {
        $html = view('livewire.site.partials.header')->render();

        foreach ([
            'actualite.index',
            'clc.index',
            'zettat.index',
            'services.index',
            'articles.index',
            'resources.index',
            'history.index',
            'projects.index',
            'contact.index',
        ] as $route) {
            $this->assertStringContainsString(route($route), $html, "Header is missing the {$route} rubrique.");
        }

        $this->assertStringNotContainsString('ui.contact', $html);
        $this->assertStringNotContainsString('ui.nav_', $html);
    }

    public function test_footer_renders_static_content_without_database(): void
    {
        $html = view('livewire.site.partials.footer')->render();

        $this->assertStringContainsString('Initiative Urbaine', $html);
        $this->assertStringContainsString('contact@initiative-urbaine.ma', $html);
        $this->assertStringContainsString('+212 522 00 00 00', $html);
        $this->assertStringContainsString('Hay Mohammadi, Casablanca', $html);
        $this->assertStringContainsString(route('actualite.index'), $html);
        $this->assertStringContainsString(route('projects.index'), $html);
        $this->assertStringContainsString(route('contact.index'), $html);

        $this->assertStringNotContainsString('ui.footer_', $html);
    }
}
