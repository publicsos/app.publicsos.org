<?php

namespace Modules\Mail\Providers;




use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;


use Modules\Mail\Providers\EventServiceProvider;
use Modules\Mail\Providers\FormServiceProvider;



use Modules\Mail\Repositories\Campaigns\MySqlCampaignTenantRepository;
use Modules\Mail\Services\LaravelMail;




use Modules\Mail\Interfaces\QuotaServiceInterface;
use Modules\Mail\Repositories\Campaigns\CampaignTenantRepositoryInterface;
use Modules\Mail\Repositories\Campaigns\PostgresCampaignTenantRepository;
use Modules\Mail\Repositories\Messages\MessageTenantRepositoryInterface;
use Modules\Mail\Repositories\Messages\MySqlMessageTenantRepository;
use Modules\Mail\Repositories\Messages\PostgresMessageTenantRepository;
use Modules\Mail\Repositories\Subscribers\MySqlSubscriberTenantRepository;
use Modules\Mail\Repositories\Subscribers\PostgresSubscriberTenantRepository;
use Modules\Mail\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use Modules\Mail\Services\QuotaService;

use Modules\Mail\Services\Validation\Adapters\GraphScoringAdapter;
use Modules\Mail\Services\Validation\Contracts\GraphScoreInterface;
use Modules\Mail\Services\Validation\Contracts\ValidationContract;
use Modules\Mail\Services\Validation\Strategies\LocalValidation;
use Modules\Mail\Traits\ResolvesDatabaseDriver;


use Modules\Mail\Services\Inbox\MailClient;
use Modules\Mail\Services\Inbox\MailContract;
use Modules\Mail\Services\Templates\TemplatesManager;

use Modules\Mail\Services\Templates\Contracts\TemplateContract;


use Modules\Mail\Services\ResolverService;

use Illuminate\Support\Facades\Blade;
use Modules\Mail\View\Components\CheckboxField;
use Modules\Mail\View\Components\FieldWrapper;
use Modules\Mail\View\Components\FileField;
use Modules\Mail\View\Components\Label;
use Modules\Mail\View\Components\SelectField;
use Modules\Mail\View\Components\SelectWithSearch;
use Modules\Mail\View\Components\SubmitButton;
use Modules\Mail\View\Components\TextareaField;
use Modules\Mail\View\Components\TextField;


class MailPackageServiceProvider extends ServiceProvider
{

    use ResolvesDatabaseDriver;

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


        // Facade.
        $this->app->bind('laravel-mail', static function (Application $app) {
            return $app->make(LaravelMail::class);
        });


        $this->app->singleton('laravel-mail.resolver', function () {
            return new ResolverService();
        });



        Blade::component(TextField::class, 'text-field');
        Blade::component(TextareaField::class, 'textarea-field');
        Blade::component(FileField::class, 'file-field');
        Blade::component(SelectField::class, 'select-field');
        Blade::component(CheckboxField::class, 'checkbox-field');
        Blade::component(Label::class, 'label');
        Blade::component(SubmitButton::class, 'submit-button');
        Blade::component(FieldWrapper::class, 'field-wrapper');
        Blade::component(SelectWithSearch::class, 'select-with-search');

    }

}

?>
