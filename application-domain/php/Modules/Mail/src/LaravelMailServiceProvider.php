<?php
declare(strict_types=1);
namespace LaravelCompany\Mail;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LaravelCompany\Mail\Console\Commands\CampaignDispatchCommand;
use LaravelCompany\Mail\Console\Commands\ImportTemplates;
use LaravelCompany\Mail\Console\Commands\ValidateSubscribers;
use LaravelCompany\Mail\Console\Commands\CreateTodayCampaign;
use LaravelCompany\Mail\Http\Livewire\Inbox;
use LaravelCompany\Mail\Providers\EventServiceProvider;
use LaravelCompany\Mail\Providers\FormServiceProvider;

use LaravelCompany\Mail\Providers\ResolverProvider;
use LaravelCompany\Mail\Providers\RouteServiceProvider;
use LaravelCompany\Mail\Repositories\Campaigns\MySqlCampaignTenantRepository;
use LaravelCompany\Mail\Services\LaravelMail;


use Livewire\Livewire;


use LaravelCompany\Mail\Interfaces\QuotaServiceInterface;
use LaravelCompany\Mail\Repositories\Campaigns\CampaignTenantRepositoryInterface;
use LaravelCompany\Mail\Repositories\Campaigns\PostgresCampaignTenantRepository;
use LaravelCompany\Mail\Repositories\Messages\MessageTenantRepositoryInterface;
use LaravelCompany\Mail\Repositories\Messages\MySqlMessageTenantRepository;
use LaravelCompany\Mail\Repositories\Messages\PostgresMessageTenantRepository;
use LaravelCompany\Mail\Repositories\Subscribers\MySqlSubscriberTenantRepository;
use LaravelCompany\Mail\Repositories\Subscribers\PostgresSubscriberTenantRepository;
use LaravelCompany\Mail\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use LaravelCompany\Mail\Services\QuotaService;

use LaravelCompany\Mail\Services\Validation\Adapters\GraphScoringAdapter;
use LaravelCompany\Mail\Services\Validation\Contracts\GraphScoreInterface;
use LaravelCompany\Mail\Services\Validation\Contracts\ValidationContract;
use LaravelCompany\Mail\Services\Validation\Strategies\LocalValidation;
use LaravelCompany\Mail\Traits\ResolvesDatabaseDriver;


use LaravelCompany\Mail\Services\Inbox\MailClient;
use LaravelCompany\Mail\Services\Inbox\MailContract;
use LaravelCompany\Mail\Services\Templates\TemplatesManager;

use LaravelCompany\Mail\Services\Templates\Contracts\TemplateContract;


class LaravelMailServiceProvider extends ServiceProvider
{

    use ResolvesDatabaseDriver;
    /**
     * Bootstrap the application services.
     */
    public function boot():void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-mail.php'),
            ], 'laravel-mail-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-mail'),
            ], 'laravel-mail-views');


            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-mail'),
            ], 'laravel-mail-assets');

            $this->commands([
                CampaignDispatchCommand::class,
                ImportTemplates::class,
                ValidateSubscribers::class,
                CreateTodayCampaign::class,
            ]);

            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command(CampaignDispatchCommand::class)->everyMinute()->withoutOverlapping();
            });
        }

        //$this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-mail');
        $this->loadJsonTranslationsFrom(resource_path('lang/vendor/laravel-mail'));
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-mail');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');


    }

    /**
     * Register the application services.
     */
    public function register(): void
    {

          // Campaign repository.
        $this->app->bind(CampaignTenantRepositoryInterface::class, function (Application $app) {
            if ($this->usingPostgres()) {
                return $app->make(PostgresCampaignTenantRepository::class);
            }

            return $app->make(MySqlCampaignTenantRepository::class);
        });

        // Message repository.
        $this->app->bind(MessageTenantRepositoryInterface::class, function (Application $app) {
            if ($this->usingPostgres()) {
                return $app->make(PostgresMessageTenantRepository::class);
            }

            return $app->make(MySqlMessageTenantRepository::class);
        });

        // Subscriber repository.
        $this->app->bind(SubscriberTenantRepositoryInterface::class, function (Application $app) {
            if ($this->usingPostgres()) {
                return $app->make(PostgresSubscriberTenantRepository::class);
            }

            return $app->make(MySqlSubscriberTenantRepository::class);
        });

        // Quota Service
        $this->app->bind(QuotaServiceInterface::class, QuotaService::class);

        //Templates Service
        $this->app->bind(TemplateContract::class,TemplatesManager::class);


        //Validation Service
        $this->app->bind(ValidationContract::class, LocalValidation::class);

        //scoring adapter

        $this->app->bind(GraphScoreInterface::class, GraphScoringAdapter::class);

        $this->app->bind(MailContract::class, MailClient::class);

        // Providers.
        $this->app->register(EventServiceProvider::class);
        $this->app->register(FormServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ResolverProvider::class);

        Livewire::component('inbox', Inbox::class);


        // Facade.
        $this->app->bind('laravel-mail', static function (Application $app) {
            return $app->make(LaravelMail::class);
        });

        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-mail');
        // Automatically apply the package configuration //TODO: ADD LARAVEL_MAIL
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'workflows');

    }
}
