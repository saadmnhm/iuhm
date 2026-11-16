<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Shared storage for the CLC and ZETTAT public content sections.
     *
     * Both sections have the exact same shape, so a `section` discriminator
     * keeps the CRUD architecture in one place instead of duplicating two
     * near-identical tables, models and administration screens.
     */
    public function up(): void
    {
        Schema::create('section_items', function (Blueprint $table) {
            $table->id();
            $table->string('section', 30)->index();
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->text('excerpt_ar')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_ar')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('views_count')->default(0);
            $table->integer('sort_order')->default(0);

            // SEO & social sharing (mirrors add_seo_fields_to_website_content).
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('canonical_url', 2048)->nullable();
            $table->string('focus_keyword')->nullable();
            $table->boolean('robots_index')->default(true);
            $table->boolean('robots_follow')->default(true);
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image', 2048)->nullable();
            $table->string('og_type', 30)->nullable();
            $table->string('twitter_card', 40)->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image', 2048)->nullable();
            $table->string('seo_image', 2048)->nullable();
            $table->string('schema_type', 60)->nullable();
            $table->json('schema_data')->nullable();
            $table->string('seo_author_name')->nullable();
            $table->string('seo_publisher_name')->nullable();
            $table->timestamp('schema_published_at')->nullable();
            $table->timestamp('schema_modified_at')->nullable();
            $table->string('breadcrumb_label')->nullable();
            $table->boolean('breadcrumb_enabled')->default(true);

            $table->softDeletes();
            $table->timestamps();

            $table->index(['section', 'is_published', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_items');
    }
};
