<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Services\Agents\Connectors;

use Saloon\Http\Connector;

class SeoConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return "";
    }
}
