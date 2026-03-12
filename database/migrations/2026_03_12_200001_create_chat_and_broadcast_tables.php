<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Chat messages between admin(s) and a candidat
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidat_id');
            $table->enum('sender_type', ['admin', 'candidat']);
            $table->unsignedBigInteger('sender_id')->nullable(); // admin user id when sender_type=admin
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('candidat_id')->references('id')->on('candidat')->onDelete('cascade');
        });

        // Broadcast / popup messages sent by admin to candidats
        Schema::create('admin_broadcasts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('title');
            $table->text('message');
            $table->enum('target_type', ['all', 'selected', 'single'])->default('all');
            $table->json('target_candidat_ids')->nullable();          // for "selected"
            $table->unsignedBigInteger('target_candidat_id')->nullable(); // for "single"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Track which candidats have dismissed / read each broadcast
        Schema::create('broadcast_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_id')->constrained('admin_broadcasts')->onDelete('cascade');
            $table->unsignedBigInteger('candidat_id');
            $table->timestamp('read_at')->useCurrent();

            $table->unique(['broadcast_id', 'candidat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broadcast_reads');
        Schema::dropIfExists('admin_broadcasts');
        Schema::dropIfExists('chat_messages');
    }
};
