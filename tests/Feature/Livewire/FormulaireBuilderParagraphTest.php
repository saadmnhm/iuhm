<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Admin\Formulaire\FormulaireBuilder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class FormulaireBuilderParagraphTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('dynamic_form_fields');

        Schema::create('dynamic_form_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_step_id')->default(1);
            $table->text('label');
            $table->text('label_ar')->nullable();
            $table->string('field_key');
            $table->string('type')->default('text');
            $table->text('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->json('options')->nullable();
            $table->boolean('allow_multiple_files')->default(false);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_full_width')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function test_a_long_paragraph_is_saved_in_french_and_arabic(): void
    {
        $french = str_repeat('Ceci est un grand paragraphe de démonstration. ', 40);
        $arabic = str_repeat('هذه فقرة تجريبية طويلة. ', 40);

        Livewire::test(FormulaireBuilder::class)
            ->set('activeStepId', 1)
            ->set('fieldForm.type', 'paragraph')
            ->set('fieldForm.label', $french)
            ->set('fieldForm.label_ar', $arabic)
            ->set('fieldForm.field_key', 'grand_paragraphe')
            ->call('saveField')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('dynamic_form_fields', [
            'type' => 'paragraph',
            'field_key' => 'grand_paragraphe',
            'label' => $french,
            'label_ar' => $arabic,
        ]);
    }

    public function test_the_generated_key_stays_short_for_long_paragraphs(): void
    {
        $component = Livewire::test(FormulaireBuilder::class)
            ->set('fieldForm.type', 'paragraph')
            ->set('fieldForm.label', str_repeat('Très long paragraphe ', 40));

        $key = $component->get('fieldForm.field_key');

        $this->assertNotSame('', $key);
        $this->assertLessThanOrEqual(80, strlen($key));
    }

    public function test_an_arabic_only_paragraph_still_gets_a_key(): void
    {
        $component = Livewire::test(FormulaireBuilder::class)
            ->set('fieldForm.type', 'paragraph')
            ->set('fieldForm.label', 'فقرة باللغة العربية');

        $key = $component->get('fieldForm.field_key');

        $this->assertNotSame('', $key);
        $this->assertLessThanOrEqual(80, strlen($key));
    }
}
