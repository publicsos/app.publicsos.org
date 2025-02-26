<?php

namespace Modules\Domain\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Domain\Contracts\AmanetContract;
use Modules\Domain\Contracts\MonitorulOficialContract;
use Modules\Domain\Contracts\PolitiaRomanaContract;
use Modules\Domain\Services\AmanetService;
use Symfony\Component\Finder\Finder;

use Modules\Domain\Services\PolitiaRomanaService;
use Modules\Domain\Services\MonitorulOficialService;

class DomainServiceProvider extends ServiceProvider
{

    protected $moduleName = 'Domain';


    protected $moduleNameLower = 'domain';


    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(base_path('Modules/Domain/database/migrations'));

        // register commands
        $this->registerCommands('\Modules\Domain\Console\Commands');
    }


    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        // Event Service Provider
        $this->app->register(EventServiceProvider::class);

    }


    protected function registerConfig()
    {
        $this->publishes([
            base_path('Modules/Domain/Config/config.php') => config_path($this->moduleNameLower.'.php'),
        ], 'config');
        $this->mergeConfigFrom(
            base_path('Modules/Domain/Config/config.php'), $this->moduleNameLower
        );
    }


    public function registerViews()
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);

        $sourcePath = base_path('Modules/Domain/Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    public function registerTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'domain');
    }


    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (Config::get('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }


    protected function registerCommands($namespace = '')
    {
        $finder = new Finder(); // from Symfony\Component\Finder;
        $finder->files()->name('*.php')->in(__DIR__.'/../Console');

        $classes = [];
        foreach ($finder as $file) {
            $class = $namespace.'\\'.$file->getBasename('.php');
            array_push($classes, $class);
        }

        $this->commands($classes);
    }
}
