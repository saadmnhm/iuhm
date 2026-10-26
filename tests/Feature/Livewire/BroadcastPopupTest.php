<?php

namespace Tests\Feature\Livewire;

use App\Models\AdminBroadcast;
use App\Models\Candidat;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class BroadcastPopupTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('broadcast_reads');
        Schema::dropIfExists('admin_broadcasts');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('programe_list');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('candidat');

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

        // Required by the layouts.app / candidat dashboard view composer.
        Schema::create('programe_list', function (Blueprint $table) {
            $table->id();
            $table->string('project_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('city')->nullable();
            $table->string('address_line1')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidat_id');
            $table->string('sender_type');
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::create('admin_broadcasts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('title');
            $table->text('message');
            $table->string('target_type')->default('all');
            $table->json('target_candidat_ids')->nullable();
            $table->unsignedBigInteger('target_candidat_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('broadcast_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('broadcast_id');
            $table->unsignedBigInteger('candidat_id');
            $table->timestamp('read_at')->useCurrent();
            $table->unique(['broadcast_id', 'candidat_id']);
        });
    }

    private function makeCandidat(): Candidat
    {
        return Candidat::create([
            'login' => 'candidat-broadcast',
            'password' => bcrypt('password'),
            'nom' => 'Doe',
            'prenom' => 'Jane',
            'email' => 'jane@example.com',
        ]);
    }

    private function makeBroadcast(array $overrides = []): AdminBroadcast
    {
        return AdminBroadcast::create(array_merge([
            'admin_id' => 1,
            'title' => 'Maintenance',
            'message' => 'The platform will be down tonight.',
            'target_type' => 'all',
            'target_candidat_ids' => null,
            'target_candidat_id' => null,
            'is_active' => true,
        ], $overrides));
    }

    public function test_active_broadcast_is_rendered_for_candidate(): void
    {
        $candidat = $this->makeCandidat();
        $this->makeBroadcast();

        $this->actingAs($candidat, 'candidat');

        Livewire::test('front.dashboard.broadcast-popup')
            ->assertSee('Maintenance')
            ->assertSee('The platform will be down tonight.')
            ->assertSet('current.title', 'Maintenance');
    }

    public function test_mark_read_persists_and_advances_to_next_broadcast(): void
    {
        $candidat = $this->makeCandidat();
        $first = $this->makeBroadcast(['title' => 'First']);
        $this->travel(1)->seconds();
        $second = $this->makeBroadcast(['title' => 'Second']);

        $this->actingAs($candidat, 'candidat');

        Livewire::test('front.dashboard.broadcast-popup')
            ->assertSet('current.title', 'Second')
            ->call('markRead')
            ->assertSet('current.title', 'First');

        $this->assertDatabaseHas('broadcast_reads', [
            'broadcast_id' => $second->id,
            'candidat_id' => $candidat->id,
        ]);
    }

    public function test_inactive_or_other_candidate_broadcast_is_not_rendered(): void
    {
        $candidat = $this->makeCandidat();
        $this->makeBroadcast(['title' => 'Inactive', 'is_active' => false]);
        $this->makeBroadcast([
            'title' => 'For someone else',
            'target_type' => 'single',
            'target_candidat_id' => 999,
        ]);

        $this->actingAs($candidat, 'candidat');

        Livewire::test('front.dashboard.broadcast-popup')
            ->assertSet('current', null);
    }
}
