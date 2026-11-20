<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('editor-harness')]
class EditorHarness extends Component
{
    public string $content = '<p>Premier paragraphe de test.</p><p>Deuxième paragraphe de test.</p><p>Troisième paragraphe de test.</p>';

    public function render()
    {
        return view('livewire.editor-harness');
    }
}
