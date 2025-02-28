<?php
declare(strict_types=1);
namespace Modules\Mail\Services\Agents\Connectors;


use Saloon\Http\Connector;

class CloudflareConnector extends Connector
{
    public function resolveBaseUrl():string
    {
        return 'https://api.cloudflare.com/client/v4';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . config('services.cloudflare.api_token'),
            'Content-Type'  => 'application/json',
        ];
    }
}
