<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Admin\Formulaire\FormulaireSubmissionDetail;
use App\Models\Candidat;
use App\Models\DynamicForm;
use App\Models\DynamicFormAnswer;
use App\Models\DynamicFormSubmission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FormulaireSubmissionDetailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('dynamic_form_answers');
        Schema::dropIfExists('dynamic_form_submissions');
        Schema::dropIfExists('candidat');
        Schema::dropIfExists('dynamic_forms');

        Schema::create('dynamic_forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('candidat', function (Blueprint $table) {
            $table->id();
            $table->string('login')->unique();
            $table->string('password');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dynamic_form_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_id');
            $table->unsignedBigInteger('candidat_id');
            $table->string('status')->default('draft');
            $table->boolean('is_submitted')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dynamic_form_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dynamic_form_submission_id');
            $table->string('field_key');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function test_it_updates_a_field_answer_from_the_modal(): void
    {
        $form = DynamicForm::create([
            'title' => 'Test form',
            'slug' => 'test-form',
            'is_active' => true,
        ]);

        $candidat = Candidat::create([
            'login' => 'candidat-test',
            'password' => bcrypt('password'),
            'nom' => 'Doe',
            'prenom' => 'Jane',
            'email' => 'jane@example.com',
        ]);

        $submission = DynamicFormSubmission::create([
            'dynamic_form_id' => $form->id,
            'candidat_id' => $candidat->id,
            'status' => 'submitted',
            'is_submitted' => true,
        ]);

        DynamicFormAnswer::create([
            'dynamic_form_submission_id' => $submission->id,
            'field_key' => 'first_name',
            'value' => 'old value',
        ]);

        $component = new FormulaireSubmissionDetail();
        $component->mount($submission->id);
        $component->openEditAnswerModal('field', 'first_name', null, null, null, 'old value');
        $component->editAnswerValue = 'updated value';
        $component->saveEditedAnswer();

        $this->assertDatabaseHas('dynamic_form_answers', [
            'dynamic_form_submission_id' => $submission->id,
            'field_key' => 'first_name',
            'value' => 'updated value',
        ]);
    }
}
