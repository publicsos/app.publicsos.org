<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Services\Agents\Connectors;

use Saloon\Http\Connector;

class ArchiveConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return "";
    }
}
