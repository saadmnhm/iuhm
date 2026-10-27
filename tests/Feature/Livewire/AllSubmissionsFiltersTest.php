<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Admin\Submissions\AllSubmissions;
use App\Models\Candidat;
use App\Models\DynamicForm;
use App\Models\DynamicFormSubmission;
use App\Models\ProjectsList;
use App\Models\ProjectsSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AllSubmissionsFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_formulaire_tab_filters_by_search_and_status(): void
    {
        $data = $this->makeContext();

        $this->actingAs($data['admin']);

        $component = Livewire::test(AllSubmissions::class);

        $this->assertFormIds($component, [
            $data['aliceFormAlpha']->id,
            $data['bobFormBeta']->id,
            $data['bobFormAlpha']->id,
        ]);

        $component->set('search', 'Alice');
        $this->assertFormIds($component, [$data['aliceFormAlpha']->id]);

        $component->set('search', '');
        $component->set('statusFilter', 'approved');
        $this->assertFormIds($component, [$data['bobFormBeta']->id]);

        $component->set('statusFilter', 'submitted');
        $this->assertFormIds($component, [
            $data['aliceFormAlpha']->id,
            $data['bobFormAlpha']->id,
        ]);
    }

    public function test_the_formulaire_tab_filters_by_project_form_responsible_candidate_and_dates(): void
    {
        $data = $this->makeContext();

        $this->actingAs($data['admin']);

        $component = Livewire::test(AllSubmissions::class);

        $component->set('programeFilter', $data['projectAlpha']->id);
        $this->assertFormIds($component, [
            $data['aliceFormAlpha']->id,
            $data['bobFormBeta']->id,
        ]);

        $component->set('programeFilter', 'all');
        $component->set('formulaireFilter', $data['formBeta']->id);
        $this->assertFormIds($component, [$data['bobFormBeta']->id]);

        $component->set('formulaireFilter', 'all');
        $component->set('responsableFilter', (string) $data['admin']->id);
        $this->assertFormIds($component, [$data['bobFormBeta']->id]);

        $component->set('responsableFilter', 'none');
        $this->assertFormIds($component, [
            $data['aliceFormAlpha']->id,
            $data['bobFormAlpha']->id,
        ]);

        $component->set('responsableFilter', 'all');
        $component->set('genderFilter', 'femme');
        $this->assertFormIds($component, [$data['aliceFormAlpha']->id]);

        $component->set('genderFilter', 'all');
        $component->set('addressFilter', 'Berrechid');
        $this->assertFormIds($component, [$data['aliceFormAlpha']->id]);

        $component->set('addressFilter', 'all');
        $component->set('dateFrom', '2026-02-01');
        $this->assertFormIds($component, [
            $data['bobFormBeta']->id,
            $data['bobFormAlpha']->id,
        ]);

        $component->set('dateFrom', '');
        $component->set('dateTo', '2026-01-31');
        $this->assertFormIds($component, [$data['aliceFormAlpha']->id]);
    }

    public function test_the_project_tab_filters_by_form_status_candidate_and_dates(): void
    {
        $data = $this->makeContext();

        $this->actingAs($data['admin']);

        $component = Livewire::test(AllSubmissions::class)
            ->set('tab', 'project');

        $this->assertProjectIds($component, [
            $data['aliceProjectAlpha']->id,
            $data['bobProjectAlpha']->id,
            $data['bobProjectBeta']->id,
        ]);

        $component->set('formulaireFilter', $data['formBeta']->id);
        $this->assertProjectIds($component, [$data['bobProjectAlpha']->id]);

        $component->set('formulaireFilter', 'all');
        $component->set('statusFilter', 'approved');
        $this->assertProjectIds($component, [$data['bobProjectAlpha']->id]);

        $component->set('statusFilter', 'all');
        $component->set('genderFilter', 'femme');
        $this->assertProjectIds($component, [$data['aliceProjectAlpha']->id]);

        $component->set('genderFilter', 'all');
        $component->set('addressFilter', 'Aïn Sebaâ');
        $this->assertProjectIds($component, [
            $data['bobProjectAlpha']->id,
            $data['bobProjectBeta']->id,
        ]);

        $component->set('addressFilter', 'all');
        $component->set('search', 'Projet Beta');
        $this->assertProjectIds($component, [$data['bobProjectBeta']->id]);

        $component->set('search', '');
        $component->set('dateFrom', '2026-02-10');
        $this->assertProjectIds($component, [
            $data['bobProjectAlpha']->id,
            $data['bobProjectBeta']->id,
        ]);

        $component->set('dateFrom', '');
        $component->set('dateTo', '2026-01-31');
        $this->assertProjectIds($component, [$data['aliceProjectAlpha']->id]);
    }

    private function assertFormIds($component, array $expectedIds): void
    {
        $actualIds = collect($component->viewData('submissions')->items())
            ->pluck('id')
            ->sort()
            ->values()
            ->all();

        sort($expectedIds);

        $this->assertSame($expectedIds, $actualIds);
    }

    private function assertProjectIds($component, array $expectedIds): void
    {
        $actualIds = collect($component->viewData('ProjectsSubmissions')->items())
            ->pluck('id')
            ->sort()
            ->values()
            ->all();

        sort($expectedIds);

        $this->assertSame($expectedIds, $actualIds);
    }

    private function makeContext(): array
    {
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Test',
            'email' => 'admin@example.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $alice = Candidat::create([
            'login' => 'alice',
            'password' => bcrypt('password'),
            'nom' => 'Alpha',
            'prenom' => 'Alice',
            'email' => 'alice@example.test',
            'gender' => 'femme',
            'address' => 'Berrechid',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $bob = Candidat::create([
            'login' => 'bob',
            'password' => bcrypt('password'),
            'nom' => 'Beta',
            'prenom' => 'Bob',
            'email' => 'bob@example.test',
            'gender' => 'homme',
            'address' => 'Aïn Sebaâ',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $projectAlpha = ProjectsList::create([
            'project_name' => 'Projet Alpha',
            'slug' => 'projet-alpha',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $projectBeta = ProjectsList::create([
            'project_name' => 'Projet Beta',
            'slug' => 'projet-beta',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $formAlpha = DynamicForm::create([
            'title' => 'Formulaire Alpha',
            'slug' => 'formulaire-alpha',
            'is_active' => true,
        ]);

        $formBeta = DynamicForm::create([
            'title' => 'Formulaire Beta',
            'slug' => 'formulaire-beta',
            'is_active' => true,
        ]);

        $aliceProjectAlpha = ProjectsSubmission::create([
            'candidat_id' => $alice->id,
            'programe_id' => $projectAlpha->id,
            'is_finished' => true,
            'review_status' => 'pending',
        ]);

        $bobProjectAlpha = ProjectsSubmission::create([
            'candidat_id' => $bob->id,
            'programe_id' => $projectAlpha->id,
            'is_finished' => true,
            'review_status' => 'approved',
            'reviewed_by' => $admin->id,
        ]);

        $bobProjectBeta = ProjectsSubmission::create([
            'candidat_id' => $bob->id,
            'programe_id' => $projectBeta->id,
            'is_finished' => true,
            'review_status' => 'pending',
        ]);

        $aliceFormAlpha = DynamicFormSubmission::create([
            'dynamic_form_id' => $formAlpha->id,
            'candidat_id' => $alice->id,
            'programe_id' => $projectAlpha->id,
            'project_submission_id' => $aliceProjectAlpha->id,
            'status' => 'submitted',
            'is_submitted' => true,
        ]);

        $bobFormBeta = DynamicFormSubmission::create([
            'dynamic_form_id' => $formBeta->id,
            'candidat_id' => $bob->id,
            'programe_id' => $projectAlpha->id,
            'project_submission_id' => $bobProjectAlpha->id,
            'status' => 'approved',
            'is_submitted' => true,
            'reviewed_by' => $admin->id,
        ]);

        $bobFormAlpha = DynamicFormSubmission::create([
            'dynamic_form_id' => $formAlpha->id,
            'candidat_id' => $bob->id,
            'programe_id' => $projectBeta->id,
            'project_submission_id' => $bobProjectBeta->id,
            'status' => 'submitted',
            'is_submitted' => true,
        ]);

        $this->setCreatedAt($aliceProjectAlpha, '2026-01-10 10:00:00');
        $this->setCreatedAt($bobProjectAlpha, '2026-02-10 10:00:00');
        $this->setCreatedAt($bobProjectBeta, '2026-02-11 10:00:00');
        $this->setCreatedAt($aliceFormAlpha, '2026-01-10 10:00:00');
        $this->setCreatedAt($bobFormBeta, '2026-02-10 10:00:00');
        $this->setCreatedAt($bobFormAlpha, '2026-02-11 10:00:00');

        return compact(
            'admin',
            'alice',
            'bob',
            'projectAlpha',
            'projectBeta',
            'formAlpha',
            'formBeta',
            'aliceProjectAlpha',
            'bobProjectAlpha',
            'bobProjectBeta',
            'aliceFormAlpha',
            'bobFormBeta',
            'bobFormAlpha',
        );
    }

    private function setCreatedAt($model, string $date): void
    {
        $model->forceFill(['created_at' => $date])->save();
    }
}
