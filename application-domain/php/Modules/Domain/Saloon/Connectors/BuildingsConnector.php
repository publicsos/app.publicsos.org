<?php

namespace Modules\Domain\Saloon\Connectors;


use Saloon\Http\Connector;


class BuildingsConnector extends Connector
{

    public function resolveBaseUrl(): string
    {
        return 'https://map.byteremix.com/maps/5/';
    }


    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
