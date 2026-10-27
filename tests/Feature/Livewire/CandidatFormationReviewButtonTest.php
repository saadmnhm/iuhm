<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Front\Programe\ProjectFormationReview;
use App\Models\Candidat;
use App\Models\CandidatProjectAgreement;
use App\Models\ProjectsList;
use App\Models\ProjectsSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CandidatFormationReviewButtonTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_requests_the_formation_review_and_warns_the_candidate(): void
    {
        $admin = $this->createAdmin('admin@example.test');
        $candidat = $this->createCandidat('candidat@example.test');
        $project = $this->createProject($admin, 'Projet test', 'projet-test');

        CandidatProjectAgreement::create([
            'candidat_id' => $candidat->id,
            'project_id' => $project->id,
            'agreed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.candidats.formation-review', $candidat->id));

        $response->assertRedirect(route('admin.candidats.show', $candidat->id));
        $response->assertSessionHas('success');

        $submission = ProjectsSubmission::where('candidat_id', $candidat->id)
            ->where('programe_id', $project->id)
            ->first();

        $this->assertNotNull($submission);
        $this->assertTrue((bool) $submission->require_formation_review);
    }

    public function test_it_does_not_duplicate_an_existing_formation_review_request(): void
    {
        $admin = $this->createAdmin('admin2@example.test');
        $candidat = $this->createCandidat('candidat2@example.test');
        $project = $this->createProject($admin, 'Projet test 2', 'projet-test-2');

        CandidatProjectAgreement::create([
            'candidat_id' => $candidat->id,
            'project_id' => $project->id,
            'agreed_at' => now(),
        ]);

        ProjectsSubmission::create([
            'candidat_id' => $candidat->id,
            'programe_id' => $project->id,
            'review_status' => 'in_review',
            'require_formation_review' => false,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.candidats.formation-review', $candidat->id));

        $response->assertRedirect(route('admin.candidats.show', $candidat->id));
        $response->assertSessionHas('success');

        $this->assertSame(1, ProjectsSubmission::where('candidat_id', $candidat->id)
            ->where('programe_id', $project->id)
            ->count());

        $this->assertTrue((bool) ProjectsSubmission::where('candidat_id', $candidat->id)
            ->where('programe_id', $project->id)
            ->first()
            ->require_formation_review);
    }

    public function test_the_candidate_dashboard_shows_a_warning_for_a_pending_formation_review(): void
    {
        $admin = $this->createAdmin('admin3@example.test');
        $candidat = $this->createCandidat('candidat3@example.test');
        $project = $this->createProject($admin, 'Projet test 3', 'projet-test-3');

        ProjectsSubmission::create([
            'candidat_id' => $candidat->id,
            'programe_id' => $project->id,
            'review_status' => 'in_review',
            'require_formation_review' => true,
            'formation_review_rating' => null,
        ]);

        $response = $this->actingAs($candidat, 'candidat')->get(route('user.dashboard'));

        $response->assertOk();
        $response->assertSee('Action requise', false);
        $response->assertSee('avis de formation', false);
        $response->assertSee('Projet test 3', false);
        $response->assertSee('Donner mon avis', false);
    }

    public function test_the_candidate_dashboard_hides_the_warning_once_the_review_is_done(): void
    {
        $admin = $this->createAdmin('admin4@example.test');
        $candidat = $this->createCandidat('candidat4@example.test');
        $project = $this->createProject($admin, 'Projet test 4', 'projet-test-4');

        ProjectsSubmission::create([
            'candidat_id' => $candidat->id,
            'programe_id' => $project->id,
            'review_status' => 'in_review',
            'require_formation_review' => true,
            'formation_review_rating' => 4,
        ]);

        $response = $this->actingAs($candidat, 'candidat')->get(route('user.dashboard'));

        $response->assertOk();
        $response->assertDontSee('Action requise', false);
    }

    public function test_the_formation_review_page_renders_for_the_candidate(): void
    {
        $admin = $this->createAdmin('admin5@example.test');
        $candidat = $this->createCandidat('candidat5@example.test');
        $project = $this->createProject($admin, 'Projet test 5', 'projet-test-5');

        ProjectsSubmission::create([
            'candidat_id' => $candidat->id,
            'programe_id' => $project->id,
            'review_status' => 'in_review',
            'require_formation_review' => true,
        ]);

        $this->actingAs($candidat, 'candidat');

        Livewire::test(ProjectFormationReview::class, ['id' => $project->id])
            ->assertOk()
            ->assertSee('Avis de formation');
    }

    private function createAdmin(string $email): User
    {
        return User::create([
            'nom' => 'Admin',
            'prenom' => 'Test',
            'email' => $email,
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    private function createCandidat(string $email): Candidat
    {
        return Candidat::create([
            'login' => 'candidat-'.md5($email),
            'password' => bcrypt('password'),
            'nom' => 'Doe',
            'prenom' => 'John',
            'email' => $email,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
    }

    private function createProject(User $admin, string $name, string $slug): ProjectsList
    {
        return ProjectsList::create([
            'project_name' => $name,
            'slug' => $slug,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);
    }
}
