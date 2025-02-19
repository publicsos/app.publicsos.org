<?php

namespace Modules\Domain\Saloon\Connectors;





use Saloon\Http\Connector;

class MonitorulOficialConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return 'https://monitoruloficial.ro/';
    }
}
