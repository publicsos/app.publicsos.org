<?php
declare(strict_types=1);
namespace Modules\Mail\Services\Validation\Saloon\Connectors;

use Saloon\Http\Connector;

class ValidationConnector extends Connector
{

    public function resolveBaseUrl(): string
    {
       return config('laravel-mail.validation-service-url') ?? 'https://validation.laravelmail.com';
    }
}
