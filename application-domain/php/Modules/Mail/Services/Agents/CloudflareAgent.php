<?php

declare(strict_types=1);

namespace Modules\Mail\Services\Agents;

use Modules\Mail\Services\Agents\Requests\CloudflareRequest;
use Modules\Mail\Services\Agents\Connectors\CloudflareConnector;
use Modules\Mail\Services\Agents\Responses\CloudflareResponse;

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
