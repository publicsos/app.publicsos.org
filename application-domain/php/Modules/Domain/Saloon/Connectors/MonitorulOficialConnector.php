<?php

namespace Modules\Domain\Saloon\Connectors;


use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\AcceptsJson;




class MonitorulOficialConnector extends Connector
{

    protected string $sessionID;

    public function __construct(string $sessionID)
    {
        $this->sessionID = $sessionID;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Referer' => 'https://monitoruloficial.ro/Monitorul-Oficial--PIV--755--2025.html',
            'sec-ch-ua-platform' => '"Linux"',
            'Cookie' => 'PHPSESSID='.$this->sessionID,
        ];
    }

    public function resolveBaseUrl(): string
    {
        return 'https://monitoruloficial.ro';
    }
}
