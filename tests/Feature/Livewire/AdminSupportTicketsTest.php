<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Admin\Support\SupportTickets;
use App\Models\Candidat;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class AdminSupportTicketsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('admin_activity_logs');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('candidat');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('admin');
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

        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidat_id')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('image')->nullable();
            $table->text('admin_response')->nullable();
            $table->string('status')->default('open');
            $table->string('priority')->default('medium');
            $table->string('category')->default('general');
            $table->timestamp('responded_at')->nullable();
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

    private function makeAdmin(): User
    {
        return User::create([
            'nom' => 'Admin',
            'prenom' => 'Test',
            'email' => 'admin-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);
    }

    private function makeCandidat(): Candidat
    {
        return Candidat::create([
            'login' => 'candidat-' . uniqid(),
            'password' => bcrypt('password'),
            'nom' => 'Doe',
            'prenom' => 'Jane',
            'email' => 'jane-' . uniqid() . '@example.com',
        ]);
    }

    private function makeTicket(array $overrides = []): SupportTicket
    {
        return SupportTicket::create(array_merge([
            'candidat_id' => null,
            'assigned_to' => null,
            'subject' => 'Ticket sujet',
            'message' => 'Description du ticket.',
            'image' => null,
            'admin_response' => null,
            'status' => 'open',
            'priority' => 'medium',
            'category' => 'general',
        ], $overrides));
    }

    public function test_commit_magic_action_is_available(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin);

        $component = Livewire::test(SupportTickets::class);
        $component->call('$commit');

        $this->assertInstanceOf(SupportTickets::class, $component->instance());
    }

    public function test_candidate_and_admin_tickets_are_separated_into_tabs(): void
    {
        $admin = $this->makeAdmin();
        $candidat = $this->makeCandidat();
        $this->actingAs($admin);

        $candidateTicket = $this->makeTicket([
            'candidat_id' => $candidat->id,
            'subject' => 'Demande candidat',
        ]);
        $adminTicket = $this->makeTicket([
            'candidat_id' => null,
            'subject' => 'Ticket interne admin',
        ]);

        $component = new SupportTickets();

        $component->tab = 'candidats';
        $candidateData = $component->render()->getData();
        $this->assertSame(1, $candidateData['tickets']->total());
        $this->assertSame($candidateTicket->id, $candidateData['tickets']->first()->id);
        $this->assertSame(1, $candidateData['candidateCount']);
        $this->assertSame(1, $candidateData['adminCount']);

        $component->tab = 'admin';
        $adminData = $component->render()->getData();
        $this->assertSame(1, $adminData['tickets']->total());
        $this->assertSame($adminTicket->id, $adminData['tickets']->first()->id);
    }

    public function test_ticket_modal_updates_status_and_saves_a_reply(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin);

        $ticket = $this->makeTicket([
            'assigned_to' => $admin->id,
            'image' => 'support_tickets/example.jpg',
        ]);

        $component = new SupportTickets();
        $component->openTicketModal($ticket->id);

        $this->assertTrue($component->showTicketModal);
        $this->assertSame($ticket->id, $component->selectedTicketId);
        $this->assertSame('open', $component->newStatus);
        $this->assertSame('support_tickets/example.jpg', $component->selectedTicket->image);

        $component->newStatus = 'resolved';
        $component->updateStatus();

        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'status' => 'resolved',
        ]);

        $component->adminResponse = 'Voici la réponse de l’administration.';
        $component->respondToTicket();

        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'admin_response' => 'Voici la réponse de l’administration.',
        ]);
        $this->assertNotNull($ticket->fresh()->responded_at);
        $this->assertTrue($component->showTicketModal);
        $this->assertSame('', $component->adminResponse);
    }

    public function test_filters_and_reset_scope_the_ticket_query(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin);

        $openTicket = $this->makeTicket([
            'subject' => 'Problème de connexion',
            'status' => 'open',
            'category' => 'technical',
        ]);
        $resolvedTicket = $this->makeTicket([
            'subject' => 'Demande traitée',
            'status' => 'resolved',
            'category' => 'account',
        ]);

        $component = new SupportTickets();
        $component->tab = 'admin';

        $component->statusFilter = 'open';
        $data = $component->render()->getData();
        $this->assertSame([$openTicket->id], $data['tickets']->pluck('id')->all());

        $component->statusFilter = 'all';
        $component->categoryFilter = 'account';
        $data = $component->render()->getData();
        $this->assertSame([$resolvedTicket->id], $data['tickets']->pluck('id')->all());

        $component->categoryFilter = 'all';
        $component->search = 'connexion';
        $data = $component->render()->getData();
        $this->assertSame([$openTicket->id], $data['tickets']->pluck('id')->all());

        $component->resetFilters();
        $this->assertSame('', $component->search);
        $this->assertSame('all', $component->statusFilter);
        $this->assertSame('all', $component->priorityFilter);
        $this->assertSame('all', $component->categoryFilter);
        $this->assertSame('all', $component->assignedFilter);
    }

    public function test_ticket_can_be_deleted_from_the_confirmation_modal(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin);

        $ticket = $this->makeTicket();

        $component = new SupportTickets();
        $component->openDeleteModal($ticket->id);

        $this->assertTrue($component->showDeleteModal);

        $component->deleteTicket();

        $this->assertSoftDeleted('support_tickets', ['id' => $ticket->id]);
        $this->assertFalse($component->showDeleteModal);
    }
}
