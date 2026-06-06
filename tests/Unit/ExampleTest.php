<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $content = file_get_contents('c:/xampp/htdocs/iuhm/resources/views/livewire/admin/formulaire/formulaire-builder-new.blade.php');
        $lines = explode("\n", $content);
        $this->assertTrue(false, "LINES 260-290:\n" . implode("\n", array_slice($lines, 259, 31)));
    }
}
