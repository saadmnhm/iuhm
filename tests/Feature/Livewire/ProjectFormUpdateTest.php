<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Admin\Project\ProjectForm;
use App\Livewire\Admin\Project\ProjectManagement;
use App\Models\Project;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectFormUpdateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('admin_activity_logs');
        Schema::dropIfExists('projects');

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->json('short_description')->nullable();
            $table->string('slug')->unique();
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('planned');
            $table->string('location')->nullable();
            $table->string('partner')->nullable();
            $table->json('tags')->nullable();
            $table->string('external_link')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->text('description');
            $table->json('properties')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    private function makeProject(): Project
    {
        return Project::create([
            'title' => ['fr' => 'Projet test', 'ar' => null],
            'description' => ['fr' => 'Description', 'ar' => null],
            'short_description' => ['fr' => 'Court', 'ar' => null],
            'slug' => 'projet-test',
            'status' => 'planned',
            'sort_order' => 0,
            'is_published' => false,
        ]);
    }

    public function test_updating_an_existing_project_updates_it_instead_of_creating_a_new_one(): void
    {
        $project = $this->makeProject();

        Livewire::test(ProjectForm::class, ['id' => $project->id])
            ->set('title', 'Titre modifié')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(1, Project::withTrashed()->count());
        $this->assertSame('Titre modifié', $project->fresh()->getLocalized('title'));
    }

    public function test_mount_accepts_a_slug_as_well_as_an_id(): void
    {
        $project = $this->makeProject();

        Livewire::test(ProjectForm::class, ['id' => 'projet-test'])
            ->assertSet('projectId', $project->id)
            ->assertSet('editMode', true);
    }

    public function test_creating_a_project_redirects_to_its_edit_page(): void
    {
        Livewire::test(ProjectForm::class)
            ->set('title', 'Nouveau projet')
            ->set('description', 'Une description')
            ->call('save')
            ->assertRedirect(route('admin.project.form', ['id' => 1]));

        $this->assertSame(1, Project::count());
    }

    public function test_deleting_a_project_logs_without_array_to_string_error(): void
    {
        $project = $this->makeProject();

        Livewire::test(ProjectManagement::class)
            ->call('delete', $project->id)
            ->assertHasNoErrors();

        $this->assertSoftDeleted('projects', ['id' => $project->id]);
        $this->assertDatabaseHas('admin_activity_logs', [
            'action' => 'project_deleted',
            'description' => 'Deleted project: Projet test',
        ]);
    }

    public function test_toggling_publication_logs_without_array_to_string_error(): void
    {
        $project = $this->makeProject();

        Livewire::test(ProjectManagement::class)
            ->call('togglePublish', $project->id)
            ->assertHasNoErrors();

        $this->assertTrue($project->fresh()->is_published);
    }
}
