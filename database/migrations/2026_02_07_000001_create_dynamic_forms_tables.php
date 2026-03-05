<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Main forms table
        Schema::create('dynamic_forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->text('introduction')->nullable();
            $table->text('introduction_ar')->nullable();
            $table->string('slug')->unique();
            $table->string('icon')->default('ri-file-list-3-line');
            $table->string('color')->default('#2f5496');
            $table->string('bg_color')->default('#ffffff');
            $table->boolean('is_active')->default(true);
            $table->boolean('has_steps')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Steps within a form
        Schema::create('dynamic_form_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_form_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->text('description')->nullable();
            $table->integer('step_number');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['dynamic_form_id', 'step_number']);
        });

        // Questions/fields within a step
        Schema::create('dynamic_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_form_step_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('label_ar')->nullable();
            $table->string('field_key'); // unique key for storage
            $table->enum('type', [
                'text', 'textarea', 'number', 'email', 'date', 'select',
                'radio', 'checkbox', 'file', 'table', 'heading', 'paragraph'
            ])->default('text');
            $table->text('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->json('options')->nullable(); // for select/radio/checkbox choices
            $table->boolean('is_required')->default(false);
            $table->boolean('is_full_width')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Tables within a step (separate entity for complex tables)
        Schema::create('dynamic_form_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_form_step_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->string('table_key'); // unique key for storage
            $table->boolean('has_dynamic_rows')->default(false);
            $table->boolean('has_total_row')->default(false);
            $table->integer('min_rows')->default(1);
            $table->integer('max_rows')->default(20);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Columns for tables
        Schema::create('dynamic_form_table_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_form_table_id')->constrained()->cascadeOnDelete();
            $table->string('header');
            $table->string('header_ar')->nullable();
            $table->string('column_key');
            $table->enum('input_type', ['text', 'number', 'checkbox', 'select', 'readonly', 'label', 'radio'])->default('text');
            $table->json('options')->nullable(); // for select/checkbox choices
            $table->boolean('is_totaled')->default(false); // whether this column shows a total
            $table->string('width')->nullable(); // e.g. '200px' or '30%'
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Fixed rows for non-dynamic tables
        Schema::create('dynamic_form_table_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_form_table_id')->constrained()->cascadeOnDelete();
            $table->string('label'); // Row label for fixed rows
            $table->string('label_ar')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Candidat form submissions
        Schema::create('dynamic_form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_form_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidat_id')->constrained('candidat')->cascadeOnDelete();
            $table->enum('status', ['draft', 'submitted', 'in_review', 'approved', 'rejected'])->default('draft');
            $table->integer('current_step')->default(1);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['dynamic_form_id', 'candidat_id']);
        });

        // Store field answers
        Schema::create('dynamic_form_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_form_submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dynamic_form_field_id')->nullable()->constrained()->nullOnDelete();
            $table->string('field_key');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->index(['dynamic_form_submission_id', 'field_key'], 'dfa_submission_field_idx');
        });

        // Store table data answers
        Schema::create('dynamic_form_table_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_form_submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dynamic_form_table_id')->nullable()->constrained()->nullOnDelete();
            $table->string('table_key');
            $table->integer('row_index');
            $table->string('column_key');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->index(['dynamic_form_submission_id', 'table_key', 'row_index'], 'dfta_submission_table_row_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dynamic_form_table_answers');
        Schema::dropIfExists('dynamic_form_answers');
        Schema::dropIfExists('dynamic_form_submissions');
        Schema::dropIfExists('dynamic_form_table_rows');
        Schema::dropIfExists('dynamic_form_table_columns');
        Schema::dropIfExists('dynamic_form_tables');
        Schema::dropIfExists('dynamic_form_fields');
        Schema::dropIfExists('dynamic_form_steps');
        Schema::dropIfExists('dynamic_forms');
    }
};
