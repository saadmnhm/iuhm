<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Expose the two new content sections in the database-driven navigation.
     *
     * The migration is idempotent so re-running it (or a site that already
     * configured these links manually) never creates duplicates.
     *
     * @var list<array{label: string, label_ar: string, url: string, icon: string}>
     */
    private array $items = [
        ['label' => 'CLC', 'label_ar' => 'CLC', 'url' => '/clc', 'icon' => 'ri-community-line'],
        ['label' => 'ZETTAT', 'label_ar' => 'ZETTAT', 'url' => '/zettat', 'icon' => 'ri-map-pin-2-line'],
    ];

    public function up(): void
    {
        if (! Schema::hasTable('navigation_items')) {
            return;
        }

        $sortOrder = (int) DB::table('navigation_items')->max('sort_order');

        foreach ($this->items as $item) {
            $exists = DB::table('navigation_items')
                ->where('url', $item['url'])
                ->whereNull('deleted_at')
                ->exists();

            if ($exists) {
                continue;
            }

            $sortOrder++;

            DB::table('navigation_items')->insert([
                'label' => $item['label'],
                'label_ar' => $item['label_ar'],
                'type' => 'external_link',
                'page_id' => null,
                'url' => $item['url'],
                'parent_id' => null,
                'sort_order' => $sortOrder,
                'is_active' => true,
                'is_visible' => true,
                'icon' => $item['icon'],
                'icon_color' => null,
                'settings' => json_encode(['target_blank' => false]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('navigation_items')) {
            return;
        }

        DB::table('navigation_items')
            ->whereIn('url', array_column($this->items, 'url'))
            ->delete();
    }
};
