<?php

declare(strict_types=1);


namespace LaravelCompany\Mail\Services\Agents\Requests;


use LaravelCompany\Mail\Services\Agents\Responses\CloudflareResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Http\Response;

use Saloon\Http\Request;
use Saloon\Enums\Method;

class CloudflareRequest extends Request implements HasBody
{
    protected Method $method = Method::POST;

    use HasJsonBody;


    public function __construct(protected string $prompt)
    {


    }

    public function resolveEndpoint(): string
    {
        return '/accounts/' . config('services.cloudflare.account_id') . '/ai/run/@hf/mistral/mistral-7b-instruct-v0.2';
    }

    protected function defaultBody(): array
    {
        return [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are a friendly email assistant.
                                You will always write a maximum of 100 words email in html format from the provided content.
                                You will always include contact details and a link to the website.",
                ],
                [
                    'role' => 'user',
                    'content' => "Here is the content of the email you will use: " . $this->prompt,
                ],
            ],
        ];

    }

    public function createDtoFromResponse(Response $response):mixed
    {
        $data = $response->json();

        return new CloudflareResponse(
            result: $data['result']['response'],
        );
    }
}
