<?php

namespace App\Livewire\Admin\Tools;

use App\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class DevTools extends Component
{
    public string $activeTab = 'cache';
    public string $clearMessage = '';
    public string $accessMessage = '';
    public bool $devAccessEnabled = false;
    public array $devAccessRoles = [];
    public array $availableAccessRoles = [];

    public function mount(): void
    {
        $this->loadDevAccessSettings();
    }

    protected function loadDevAccessSettings(): void
    {
        $this->availableAccessRoles = Role::orderBy('label')
            ->get(['name', 'label', 'is_system'])
            ->map(fn (Role $role) => [
                'name' => $role->name,
                'label' => $role->label,
                'is_system' => (bool) $role->is_system,
            ])
            ->toArray();

        $this->devAccessEnabled = Role::isDevelopmentAccessLocked();
        $this->devAccessRoles = Role::developmentAccessAllowedRoles();
    }

    public function toggleDevAccessLock(): void
    {
        $this->devAccessEnabled = ! $this->devAccessEnabled;
        $this->saveDevAccessSettings();
    }

    public function saveDevAccessSettings(): void
    {
        Role::setDevelopmentAccessSettings($this->devAccessEnabled, $this->devAccessRoles);
        $this->accessMessage = $this->devAccessEnabled
            ? 'Development access lock enabled.'
            : 'Development access lock disabled.';

        $this->loadDevAccessSettings();
    }

    public function clearCache(string $type): void
    {
        try {
            match ($type) {
                'config' => Artisan::call('config:clear'),
                'route'  => Artisan::call('route:clear'),
                'view'   => Artisan::call('view:clear'),
                'all'    => $this->clearAll(),
                default  => null,
            };
            $this->clearMessage = ucfirst($type) . ' cache cleared successfully!';
        } catch (\Exception $e) {
            $this->clearMessage = 'Error: ' . $e->getMessage();
        }
    }

    protected function clearAll(): void
    {
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
    }

    public function getModulesProperty(): array
    {
        $modules = [];

        // Livewire components
        $livewirePath = app_path('Livewire');
        if (File::isDirectory($livewirePath)) {
            $files = File::allFiles($livewirePath);
            foreach ($files as $file) {
                $relative = str_replace(app_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relative = str_replace('.php', '', $relative);
                $modules[] = [
                    'type' => 'Livewire',
                    'name' => str_replace(DIRECTORY_SEPARATOR, '\\', $relative),
                    'path' => $file->getRelativePathname(),
                    'size' => round($file->getSize() / 1024, 1) . ' KB',
                ];
            }
        }

        return $modules;
    }

    public function getRoutesProperty(): array
    {
        $routes = [];
        foreach (Route::getRoutes() as $route) {
            $middleware = implode(', ', $route->middleware());
            if (str_contains($middleware, 'admin') || str_starts_with($route->uri(), 'admin')) {
                $routes[] = [
                    'method'     => implode('|', $route->methods()),
                    'uri'        => $route->uri(),
                    'name'       => $route->getName() ?? '—',
                    'action'     => $route->getActionName(),
                    'middleware'  => $middleware,
                ];
            }
        }
        return $routes;
    }

    public function getCacheStatusProperty(): array
    {
        return [
            'config_cached' => File::exists(base_path('bootstrap/cache/config.php')),
            'routes_cached' => File::exists(base_path('bootstrap/cache/routes-v7.php')),
            'views_cached'  => count(File::glob(storage_path('framework/views/*.php'))) > 0,
            'views_count'   => count(File::glob(storage_path('framework/views/*.php'))),
            'sessions_count' => count(File::glob(storage_path('framework/sessions/*'))),
            'log_size'       => File::exists(storage_path('logs/laravel.log'))
                ? round(File::size(storage_path('logs/laravel.log')) / 1024, 1) . ' KB'
                : '0 KB',
        ];
    }

    public function getSystemInfoProperty(): array
    {
        return [
            'php_version'     => phpversion(),
            'laravel_version' => app()->version(),
            'environment'     => app()->environment(),
            'debug_mode'      => config('app.debug') ? 'ON' : 'OFF',
            'timezone'        => config('app.timezone'),
            'locale'          => config('app.locale'),
            'db_driver'       => config('database.default'),
            'cache_driver'    => config('cache.default'),
            'session_driver'  => config('session.driver'),
            'queue_driver'    => config('queue.default'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.tools.dev-tools')
            ->layout('layouts.admin', ['header' => 'Dev Tools']);
    }
}
