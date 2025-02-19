<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Adapters;

use Illuminate\Support\Arr;
use LaravelCompany\Mail\Services\Messages\MessageTrackingOptions;

class WhatsupAdapter extends BaseMailAdapter
{
    /** @var Client */
    protected $client;

    protected $urls = [
        'Default' => 'api.mailjet.com',
        'US' => 'api.us.mailjet.com'
    ];

    protected array $config;

    public function send(string $fromEmail, string $fromName, string $toEmail, string $subject, MessageTrackingOptions $trackingOptions, string $content): string
    {

    }

    protected function resolveClient(): Client
    {
        if ($this->client) {
            return $this->client;
        }

        $this->client = new Client(
            Arr::get($this->config, 'key'),
            Arr::get($this->config, 'secret'),
            app()->environment() !== 'testing',
            [
                'version' => 'v3.1',
                'url' => $this->resolveUrl()
            ]
        );

        return $this->client;
    }

    protected function resolveUrl(): string
    {
        return $this->urls[Arr::get($this->config, 'zone', 'Default')];
    }

}
