<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Admin\Formulaire\FormulaireBuilder;
use App\Models\DynamicForm;
use App\Models\DynamicFormField;
use App\Models\DynamicFormStep;
use App\Models\DynamicFormTable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class FormulaireBuilderOrderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'dynamic_form_table_rows',
            'dynamic_form_table_columns',
            'dynamic_form_tables',
            'dynamic_form_fields',
            'dynamic_form_steps',
            'dynamic_forms',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::create('dynamic_forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->text('introduction')->nullable();
            $table->text('introduction_ar')->nullable();
            $table->string('slug')->unique();
            $table->string('icon')->default('ri-file-list-3-line');
            $table->string('color')->default('#2f5496');
            $table->string('bg_color')->default('#ffffff');
            $table->boolean('is_active')->default(true);
            $table->boolean('has_steps')->default(true);
            $table->integer('sort_order')->default(0);
            $table->boolean('has_introduction')->default(false);
            $table->string('introduction_title')->nullable();
            $table->string('introduction_title_ar')->nullable();
            $table->text('introduction_content')->nullable();
            $table->text('introduction_content_ar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dynamic_form_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_id');
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->text('description')->nullable();
            $table->integer('step_number');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('dynamic_form_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_step_id');
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

        Schema::create('dynamic_form_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_step_id');
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->string('table_key');
            $table->boolean('has_dynamic_rows')->default(false);
            $table->boolean('has_total_row')->default(false);
            $table->integer('min_rows')->default(1);
            $table->integer('max_rows')->default(20);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('dynamic_form_table_columns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_table_id');
            $table->string('header');
            $table->string('header_ar')->nullable();
            $table->string('column_key');
            $table->string('input_type')->default('text');
            $table->json('options')->nullable();
            $table->boolean('is_totaled')->default(false);
            $table->string('width')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('dynamic_form_table_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_table_id');
            $table->string('label');
            $table->string('label_ar')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    private function makeFormAndStep(): array
    {
        $form = DynamicForm::create([
            'title' => 'Formulaire de test',
            'slug' => 'formulaire-de-test',
        ]);

        $step = DynamicFormStep::create([
            'dynamic_form_id' => $form->id,
            'title' => 'Étape 1',
            'step_number' => 1,
            'sort_order' => 1,
        ]);

        return [$form, $step];
    }

    private function makeField(DynamicFormStep $step, string $label, int $sortOrder, ?string $createdAt = null): DynamicFormField
    {
        $field = DynamicFormField::create([
            'dynamic_form_step_id' => $step->id,
            'label' => $label,
            'field_key' => strtolower(str_replace(' ', '_', $label)).'_'.uniqid(),
            'type' => 'text',
            'sort_order' => $sortOrder,
        ]);

        if ($createdAt) {
            $field->created_at = $createdAt;
            $field->saveQuietly();
        }

        return $field;
    }

    private function makeTable(DynamicFormStep $step, string $title, int $sortOrder, ?string $createdAt = null): DynamicFormTable
    {
        $table = DynamicFormTable::create([
            'dynamic_form_step_id' => $step->id,
            'title' => $title,
            'table_key' => strtolower(str_replace(' ', '_', $title)).'_'.uniqid(),
            'sort_order' => $sortOrder,
        ]);

        if ($createdAt) {
            $table->created_at = $createdAt;
            $table->saveQuietly();
        }

        return $table;
    }

    private function elementKeys(DynamicFormStep $step): array
    {
        return $step->fresh()->elements
            ->map(fn ($element) => $element->element_type.':'.$element->id)
            ->all();
    }

    public function test_questions_and_tables_share_one_visual_order(): void
    {
        [, $step] = $this->makeFormAndStep();

        $questionOne = $this->makeField($step, 'Question 1', 1, now()->subMinutes(3)->toDateTimeString());
        $table = $this->makeTable($step, 'Tableau 1', 2, now()->subMinutes(2)->toDateTimeString());
        // Legacy rows can share the same sort_order: creation time breaks the tie.
        $questionTwo = $this->makeField($step, 'Question 2', 2, now()->subMinutes(1)->toDateTimeString());

        $this->assertSame([
            'field:'.$questionOne->id,
            'table:'.$table->id,
            'field:'.$questionTwo->id,
        ], $this->elementKeys($step));
    }

    public function test_a_table_can_be_moved_above_a_question(): void
    {
        [$form, $step] = $this->makeFormAndStep();

        $question = $this->makeField($step, 'Question 1', 1);
        $table = $this->makeTable($step, 'Tableau 1', 2);

        Livewire::test(FormulaireBuilder::class, ['id' => $form->id])
            ->call('moveElement', 'table', $table->id, 'up');

        $this->assertSame(1, (int) $table->fresh()->sort_order);
        $this->assertSame(2, (int) $question->fresh()->sort_order);
        $this->assertSame([
            'table:'.$table->id,
            'field:'.$question->id,
        ], $this->elementKeys($step));
    }

    public function test_a_question_can_be_moved_below_a_table(): void
    {
        [$form, $step] = $this->makeFormAndStep();

        $question = $this->makeField($step, 'Question 1', 1);
        $table = $this->makeTable($step, 'Tableau 1', 2);

        Livewire::test(FormulaireBuilder::class, ['id' => $form->id])
            ->call('moveField', $question->id, 'down');

        $this->assertSame(2, (int) $question->fresh()->sort_order);
        $this->assertSame(1, (int) $table->fresh()->sort_order);
        $this->assertSame([
            'table:'.$table->id,
            'field:'.$question->id,
        ], $this->elementKeys($step));
    }

    public function test_a_new_question_is_placed_after_the_last_table(): void
    {
        [$form, $step] = $this->makeFormAndStep();

        $this->makeField($step, 'Question 1', 1);
        $this->makeTable($step, 'Tableau 1', 2);

        $component = Livewire::test(FormulaireBuilder::class, ['id' => $form->id])
            ->call('openFieldModal', $step->id);

        $this->assertSame(3, (int) $component->get('fieldForm.sort_order'));
    }

    public function test_the_builder_exposes_the_shared_order_tab(): void
    {
        [$form, $step] = $this->makeFormAndStep();

        $this->makeField($step, 'Question 1', 1);
        $this->makeTable($step, 'Tableau 1', 2);

        Livewire::test(FormulaireBuilder::class, ['id' => $form->id])
            ->assertSee('Ordre des éléments')
            ->assertSee('Question 1')
            ->assertSee('Tableau 1');
    }
}
