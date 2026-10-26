<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Paragraph fields store their full content in the label columns,
     * so they need more room than a regular question label.
     */
    public function up(): void
    {
        Schema::table('dynamic_form_fields', function (Blueprint $table) {
            $table->text('label')->change();
            $table->text('label_ar')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('dynamic_form_fields', function (Blueprint $table) {
            $table->string('label')->change();
            $table->string('label_ar')->nullable()->change();
        });
    }
};
