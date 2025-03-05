<?php

namespace Modules\Document\Saloon\Connectors;
use Saloon\Http\Connector;


class MonitorulOficialConnector extends Connector
{

    public function resolveBaseUrl(): string
    {
        return 'https://monitoruloficial.ro/';
    }

    public function defaultHeaders(): array
    {
        return [
            'referer' => 'https://monitoruloficial.ro/e-monitor/',
            'content-type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        ];
    }
}
