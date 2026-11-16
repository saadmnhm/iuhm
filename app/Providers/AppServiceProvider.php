<?php

namespace App\Providers;

use App\Models\Address;
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

        View::composer(['layouts.admin', 'layouts.app', 'livewire.candidat.dashboard.*'], function ($view) {
            $view->with('programe_list', ProjectsList::all());
            $view->with('addresses', Address::orderBy('city')->orderBy('address_line1')->get());
        });
    }
}
