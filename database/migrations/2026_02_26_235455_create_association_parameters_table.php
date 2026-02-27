<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('association_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('category');       // e.g. general, finance, rh, seo, contact
            $table->string('key')->unique();
            $table->string('label');
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, number, email, url, image, select, boolean
            $table->json('options')->nullable();      // for select type
            $table->integer('sort_order')->default(0);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('association_parameters');
    }
};
