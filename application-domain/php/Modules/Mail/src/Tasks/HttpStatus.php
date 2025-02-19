<?php

namespace LaravelCompany\Mail\Tasks;

use Illuminate\Support\Facades\Http;


class HttpStatus extends Task
{
    public static array $fields = [
        'url' => 'url',
    ];

    public static array $output = [
        'HTTP Status' => 'http_status',
    ];

    public static $icon = '<i class="far fa-eye"></i>';

    public function execute(): void
    {

        $results = Http::get($this->getData('url', 'https://laravelmail.com'));

        if ($results->failed()) {
            $this->setData('http_status', 'failed');
            return;
        }

        info("The http status code" . $results->status());

        $this->setData('http_status', $results->status());

        $this->setData('response', $results->body());
    }
}
