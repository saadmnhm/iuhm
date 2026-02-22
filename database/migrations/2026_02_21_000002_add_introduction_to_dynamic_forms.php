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
        Schema::table('dynamic_forms', function (Blueprint $table) {
            $table->boolean('has_introduction')->default(false)->after('has_steps');
            $table->text('introduction_title')->nullable()->after('has_introduction');
            $table->text('introduction_title_ar')->nullable()->after('introduction_title');
            $table->longText('introduction_content')->nullable()->after('introduction_title_ar');
            $table->longText('introduction_content_ar')->nullable()->after('introduction_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_forms', function (Blueprint $table) {
            $table->dropColumn([
                'has_introduction',
                'introduction_title',
                'introduction_title_ar',
                'introduction_content',
                'introduction_content_ar'
            ]);
        });
    }
};
