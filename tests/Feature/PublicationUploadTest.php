<?php

namespace Tests\Feature;

use App\Livewire\Admin\Publication\PublicationForm;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PublicationUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_file_is_stored_when_saving_a_deliverable(): void
    {
        Storage::fake('uploads');

        $user = User::create([
            'nom' => 'Test',
            'prenom' => 'Admin',
            'email' => 'admin@example.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);
        $this->actingAs($user);

        Livewire::test(PublicationForm::class)
            ->set('title', 'Rapport de test')
            ->set('newFile', UploadedFile::fake()->create('rapport.pdf', 500, 'application/pdf'))
            ->call('save')
            ->assertHasNoErrors();

        $publication = Publication::first();

        $this->assertNotNull($publication, 'Publication was not created.');
        fwrite(STDERR, "\nfile_url=" . var_export($publication->file_url, true) . "\n");
        fwrite(STDERR, 'download_url=' . var_export($publication->file_download_url, true) . "\n");
        fwrite(STDERR, 'disk files=' . json_encode(Storage::disk('uploads')->allFiles()) . "\n");

        $this->assertNotNull($publication->file_url);
    }
}
