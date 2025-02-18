<?php

namespace Modules\Domain\Saloon\Connectors;





use Saloon\Http\Connector;

class PolitiaRomana extends Connector
{
    public function resolveBaseUrl(): string
    {
        return 'https://politiaromana.ro/';
    }
}
