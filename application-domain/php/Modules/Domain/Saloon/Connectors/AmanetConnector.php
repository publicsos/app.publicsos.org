<?php

namespace Modules\Domain\Saloon\Connectors;





use Saloon\Http\Connector;

class AmanetConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return 'https://www.amanetonline.com/';
    }
}
