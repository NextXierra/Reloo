<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ModuleServiceProvider extends ServiceProvider
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
        $modulesPath = app_path('Modules');

        if (!File::isDirectory($modulesPath)) {
            return;
        }

        $modules = File::directories($modulesPath);

        foreach ($modules as $moduleDir) {
            $moduleName = basename($moduleDir);
            $namespaceSlug = Str::snake($moduleName);

            // 1. Load Web Routes
            $webRoutes = $moduleDir . '/Routes/web.php';
            if (File::exists($webRoutes)) {
                Route::middleware('web')
                    ->group($webRoutes);
            }

            // 2. Load API Routes
            $apiRoutes = $moduleDir . '/Routes/api.php';
            if (File::exists($apiRoutes)) {
                Route::prefix('api')
                    ->middleware('api')
                    ->group($apiRoutes);
            }

            // 3. Load Views with module slug namespace (e.g. view('catalog::index'), view('cms::render'))
            $viewsPath = $moduleDir . '/Views';
            if (File::isDirectory($viewsPath)) {
                $this->loadViewsFrom($viewsPath, $namespaceSlug);
            }

            // 4. Load Database Migrations
            $migrationsPath = $moduleDir . '/Database/Migrations';
            if (File::isDirectory($migrationsPath)) {
                $this->loadMigrationsFrom($migrationsPath);
            }
        }
    }
}
