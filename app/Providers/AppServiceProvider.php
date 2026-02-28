<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ProgrameList;
use App\Models\Address;     

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
        \Illuminate\Support\Facades\Blade::if('canmodule', function (string $module) {
            if (!auth()->check()) return false;
            return \App\Models\Role::canAccess(auth()->user()->role, $module);
        });

        // Set locale from session or default to 'ar'
        $locale = session('locale', 'fr');
        app()->setLocale($locale);
        View::composer(['layouts.admin', 'layouts.app', 'livewire.front.dashboard.*'], function ($view) {
            $view->with('programe_list', ProgrameList::all());
            $view->with('addresses', Address::orderBy('city')->orderBy('address_line1')->get());
        });
    }
}
