<?php

namespace Modules\Document\Saloon\Connectors;


use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Traits\Plugins\AcceptsJson;




class NLPConnector extends Connector
{
    protected function defaultHeaders(): array
    {
        return [
            'sec-ch-ua-platform' => '"Linux"',
        ];
    }

    public function resolveBaseUrl(): string
    {
        return 'http://localhost:1121/';
    }
}
