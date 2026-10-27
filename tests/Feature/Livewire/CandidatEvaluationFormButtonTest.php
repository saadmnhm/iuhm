<?php

namespace Tests\Feature\Livewire;

use App\Models\Candidat;
use App\Models\CandidatProjectAgreement;
use App\Models\DynamicForm;
use App\Models\DynamicFormSubmission;
use App\Models\ProjectsList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CandidatEvaluationFormButtonTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_attaches_the_evaluation_form_and_warns_the_candidate(): void
    {
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Test',
            'email' => 'admin@example.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $candidat = Candidat::create([
            'login' => 'candidat-test',
            'password' => bcrypt('password'),
            'nom' => 'Doe',
            'prenom' => 'John',
            'email' => 'candidat@example.test',
            'is_active' => true,
        ]);

        $project = ProjectsList::create([
            'project_name' => 'Projet test',
            'slug' => 'projet-test',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $firstForm = DynamicForm::create([
            'title' => 'Formulaire initial',
            'slug' => 'formulaire-initial',
            'is_active' => true,
        ]);

        $evaluationForm = DynamicForm::create([
            'title' => 'Evaluation d’idée de projet',
            'slug' => 'evaluation-didee-de-projet',
            'is_active' => true,
        ]);

        DB::table('programe_formulaire')->insert([
            'programe_id' => $project->id,
            'formulaire_id' => $firstForm->id,
            'order' => 1,
            'status' => 'active',
            'is_required' => true,
            'unlock_on_status' => 'approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $submission = DynamicFormSubmission::create([
            'dynamic_form_id' => $firstForm->id,
            'candidat_id' => $candidat->id,
            'programe_id' => $project->id,
            'status' => 'submitted',
            'is_submitted' => true,
            'submitted_at' => now(),
            'workflow_stages' => null,
        ]);

        CandidatProjectAgreement::create([
            'candidat_id' => $candidat->id,
            'project_id' => $project->id,
            'agreed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.candidats.evaluation-idee', $candidat->id));

        $response->assertRedirect(route('admin.candidats.show', $candidat->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('programe_formulaire', [
            'programe_id' => $project->id,
            'formulaire_id' => $evaluationForm->id,
            'order' => 2,
            'status' => 'active',
            'is_required' => true,
            'unlock_on_status' => 'submitted',
        ]);

        $submission->refresh();

        $this->assertTrue((bool) ($submission->workflow_stages['next_form_allowed'] ?? false));
    }

    public function test_it_does_not_duplicate_an_existing_evaluation_form_attachment(): void
    {
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Test',
            'email' => 'admin2@example.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $candidat = Candidat::create([
            'login' => 'candidat-test-2',
            'password' => bcrypt('password'),
            'nom' => 'Doe',
            'prenom' => 'Jane',
            'email' => 'candidat2@example.test',
            'is_active' => true,
        ]);

        $project = ProjectsList::create([
            'project_name' => 'Projet test 2',
            'slug' => 'projet-test-2',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $firstForm = DynamicForm::create([
            'title' => 'Formulaire initial 2',
            'slug' => 'formulaire-initial-2',
            'is_active' => true,
        ]);

        $evaluationForm = DynamicForm::create([
            'title' => 'Evaluation d’idée de projet',
            'slug' => 'evaluation-didee-de-projet',
            'is_active' => true,
        ]);

        DB::table('programe_formulaire')->insert([
            [
                'programe_id' => $project->id,
                'formulaire_id' => $firstForm->id,
                'order' => 1,
                'status' => 'active',
                'is_required' => true,
                'unlock_on_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'programe_id' => $project->id,
                'formulaire_id' => $evaluationForm->id,
                'order' => 2,
                'status' => 'inactive',
                'is_required' => true,
                'unlock_on_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DynamicFormSubmission::create([
            'dynamic_form_id' => $firstForm->id,
            'candidat_id' => $candidat->id,
            'programe_id' => $project->id,
            'status' => 'submitted',
            'is_submitted' => true,
            'submitted_at' => now(),
            'workflow_stages' => ['next_form_allowed' => true],
        ]);

        CandidatProjectAgreement::create([
            'candidat_id' => $candidat->id,
            'project_id' => $project->id,
            'agreed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.candidats.evaluation-idee', $candidat->id));

        $response->assertRedirect(route('admin.candidats.show', $candidat->id));
        $response->assertSessionHas('success');

        $this->assertSame(1, DB::table('programe_formulaire')
            ->where('programe_id', $project->id)
            ->where('formulaire_id', $evaluationForm->id)
            ->count());

        $this->assertDatabaseHas('programe_formulaire', [
            'programe_id' => $project->id,
            'formulaire_id' => $evaluationForm->id,
            'status' => 'active',
            'unlock_on_status' => 'submitted',
        ]);
    }

    public function test_the_candidate_dashboard_shows_a_warning_for_a_pending_evaluation_form(): void
    {
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Test',
            'email' => 'admin3@example.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $candidat = Candidat::create([
            'login' => 'candidat-test-3',
            'password' => bcrypt('password'),
            'nom' => 'Doe',
            'prenom' => 'John',
            'email' => 'candidat3@example.test',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $project = ProjectsList::create([
            'project_name' => 'Projet test 3',
            'slug' => 'projet-test-3',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $firstForm = DynamicForm::create([
            'title' => 'Formulaire initial 3',
            'slug' => 'formulaire-initial-3',
            'is_active' => true,
        ]);

        $evaluationForm = DynamicForm::create([
            'title' => 'Evaluation d’idée de projet',
            'slug' => 'evaluation-didee-de-projet',
            'is_active' => true,
        ]);

        DB::table('programe_formulaire')->insert([
            [
                'programe_id' => $project->id,
                'formulaire_id' => $firstForm->id,
                'order' => 1,
                'status' => 'active',
                'is_required' => true,
                'unlock_on_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'programe_id' => $project->id,
                'formulaire_id' => $evaluationForm->id,
                'order' => 2,
                'status' => 'active',
                'is_required' => true,
                'unlock_on_status' => 'submitted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DynamicFormSubmission::create([
            'dynamic_form_id' => $firstForm->id,
            'candidat_id' => $candidat->id,
            'programe_id' => $project->id,
            'status' => 'submitted',
            'is_submitted' => true,
            'submitted_at' => now(),
            'workflow_stages' => ['next_form_allowed' => true],
        ]);

        CandidatProjectAgreement::create([
            'candidat_id' => $candidat->id,
            'project_id' => $project->id,
            'agreed_at' => now(),
        ]);

        $response = $this->actingAs($candidat, 'candidat')->get(route('user.dashboard'));

        $response->assertOk();
        $response->assertSee('Action requise', false);
        $response->assertSee('évaluation d’idée', false);
    }
}
