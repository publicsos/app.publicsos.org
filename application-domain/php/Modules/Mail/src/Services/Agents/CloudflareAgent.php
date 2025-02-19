<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Services\Agents;

use LaravelCompany\Mail\Services\Agents\Requests\CloudflareRequest;
use LaravelCompany\Mail\Services\Agents\Connectors\CloudflareConnector;
use LaravelCompany\Mail\Services\Agents\Responses\CloudflareResponse;

class CloudflareAgent
{
    public function run(string $content):?CloudflareResponse
    {
        $connector = new CloudflareConnector();

        $request = new CloudflareRequest($content);

        $response = $connector->send($request);

        return $response->dto();
    }

}
