<?php

namespace Modules\Mail\Services\Templates\Saloon\Connectors;

use Saloon\Http\Connector;

class UnlayerConnector extends Connector
{

    private string $base_url;

    public function __construct(string $base_url = 'https://unlayer.com/')
    {
        $this->base_url = $base_url;
    }

    public function resolveBaseUrl(): string
    {
        return $this->base_url;
    }

}
