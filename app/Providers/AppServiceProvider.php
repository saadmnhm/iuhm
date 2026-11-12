<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\AssociationParameter;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectsList;
use App\Models\Role;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use function Livewire\on;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // @canModule('module_name') ... @endCanModule
        // Permissions are stored in role_permissions table. super_admin always has full access.
        Blade::if('canmodule', function (string $module) {
            if (! auth()->check()) {
                return false;
            }

            return Role::canAccess(auth()->user()->role, $module);
        });

        // Set locale from session or default to 'fr'
        $locale = session('locale', 'fr');
        app()->setLocale($locale);

        // Safety net for Livewire magic actions ($set, $commit, $refresh, $sync).
        // Livewire sends these as server method calls; SupportMagicActions should catch them,
        // but this listener ensures they are handled without a MethodNotFoundException.
        $this->app->booted(function () {
            on('call', function (...$args) {
                $magicActions = ['$set', '$commit', '$refresh', '$sync'];

                if (isset($args[1]) && in_array($args[1], $magicActions, true)) {
                    foreach ($args as $arg) {
                        if ($arg instanceof \Closure) {
                            $arg();
                            break;
                        }
                    }
                }
            });
        });

        // Share navigation and public pages with public views
        View::composer(['layouts.site', 'livewire.site.partials.header'], function ($view): void {
            $navigation = [];
            $navigationPages = collect();

            try {
                $navigation = NavigationItem::where('is_active', true)
                    ->where('is_visible', true)
                    ->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->with(['children' => fn ($q) => $q->where('is_visible', true)->orderBy('sort_order')])
                    ->get()
                    ->map(function ($item) {
                        $children = $item->children->map(function ($child) {
                            return [
                                'id' => $child->id,
                                'label' => $child->label,
                                'label_ar' => $child->label_ar,
                                'type' => $child->type,
                                'url' => $child->url,
                                'parent_id' => $child->parent_id,
                                'sort_order' => $child->sort_order,
                                'is_active' => (bool) $child->is_active,
                                'is_visible' => (bool) $child->is_visible,
                                'icon' => $child->icon,
                                'icon_color' => $child->icon_color,
                                'settings' => $child->settings ?? [],
                                'children' => [],
                                'has_children' => false,
                            ];
                        })->values()->toArray();

                        $url = $item->url ?? '#';
                        if ($item->type === 'page_link' && $item->page) {
                            $url = $item->page->slug ? '/'.$item->page->slug : '/';
                        }

                        return [
                            'id' => $item->id,
                            'label' => $item->label,
                            'label_ar' => $item->label_ar,
                            'type' => $item->type,
                            'page_id' => $item->page_id,
                            'url' => $url,
                            'parent_id' => $item->parent_id,
                            'sort_order' => $item->sort_order,
                            'is_active' => (bool) $item->is_active,
                            'is_visible' => (bool) $item->is_visible,
                            'icon' => $item->icon,
                            'icon_color' => $item->icon_color,
                            'settings' => $item->settings ?? [],
                            'children' => $children,
                            'has_children' => count($children) > 0,
                        ];
                    })->values()->toArray();

                $navigationPages = Page::published()
                    ->ordered()
                    ->get(['id', 'slug', 'title']);
            } catch (\Throwable) {
                // Ignore if tables don't exist yet
            }

            $view->with([
                'navigation' => $navigation,
                'navigationPages' => $navigationPages,
                'projects' => Project::published()->orderBy('sort_order')->orderBy('id', 'desc')->limit(20)->get(),
                'siteSettings' => (function () {
                    try {
                        return AssociationParameter::query()
                            ->whereNotNull('value')
                            ->where('value', '!=', '')
                            ->pluck('value', 'key')
                            ->all();
                    } catch (\Throwable) {
                        return [];
                    }
                })(),
                'footerNavLinks' => collect($navigation)
                    ->filter(fn ($item) => ! in_array($item['type'], ['divider', 'dropdown'], true) && filled($item['url'] ?? null))
                    ->map(fn ($item) => [
                        'label' => app()->getLocale() === 'ar' && filled($item['label_ar'] ?? null)
                            ? $item['label_ar']
                            : $item['label'],
                        'url' => $item['url'],
                    ])
                    ->values()
                    ->all(),
                'footerPages' => (function () {
                    try {
                        return Page::published()
                            ->ordered()
                            ->get(['slug', 'title'])
                            ->map(fn (Page $page) => [
                                'label' => $page->getLocalized('title'),
                                'url' => url($page->slug),
                            ])
                            ->filter(fn ($page) => filled($page['label']))
                            ->values()
                            ->all();
                    } catch (\Throwable) {
                        return [];
                    }
                })(),
            ]);
        });

        View::composer(['layouts.admin', 'layouts.app', 'livewire.candidat.dashboard.*'], function ($view) {
            $view->with('programe_list', ProjectsList::all());
            $view->with('addresses', Address::orderBy('city')->orderBy('address_line1')->get());
        });
    }
}
