<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables holding public website content that gets its own SEO metadata.
     *
     * @var list<string>
     */
    private array $tables = ['article', 'actualite', 'projects'];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('seo_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->string('canonical_url', 2048)->nullable();
                $table->string('focus_keyword')->nullable();
                $table->boolean('robots_index')->default(true);
                $table->boolean('robots_follow')->default(true);

                // Open Graph / social sharing.
                $table->string('og_title')->nullable();
                $table->text('og_description')->nullable();
                $table->string('og_image', 2048)->nullable();
                $table->string('og_type', 30)->nullable();
                $table->string('twitter_card', 40)->nullable();
                $table->string('twitter_title')->nullable();
                $table->text('twitter_description')->nullable();
                $table->string('twitter_image', 2048)->nullable();

                // Additional SEO settings.
                $table->string('seo_image', 2048)->nullable();
                $table->string('schema_type', 60)->nullable();
                $table->json('schema_data')->nullable();
                $table->string('seo_author_name')->nullable();
                $table->string('seo_publisher_name')->nullable();
                $table->timestamp('schema_published_at')->nullable();
                $table->timestamp('schema_modified_at')->nullable();
                $table->string('breadcrumb_label')->nullable();
                $table->boolean('breadcrumb_enabled')->default(true);
            });
        }

        Schema::create('seo_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('model_type', 40);
            $table->unsignedBigInteger('model_id');
            $table->string('old_slug');
            $table->string('new_slug');
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->timestamps();

            $table->unique(['model_type', 'old_slug']);
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_redirects');

        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn([
                    'seo_title',
                    'meta_description',
                    'meta_keywords',
                    'canonical_url',
                    'focus_keyword',
                    'robots_index',
                    'robots_follow',
                    'og_title',
                    'og_description',
                    'og_image',
                    'og_type',
                    'twitter_card',
                    'twitter_title',
                    'twitter_description',
                    'twitter_image',
                    'seo_image',
                    'schema_type',
                    'schema_data',
                    'seo_author_name',
                    'seo_publisher_name',
                    'schema_published_at',
                    'schema_modified_at',
                    'breadcrumb_label',
                    'breadcrumb_enabled',
                ]);
            });
        }
    }
};
