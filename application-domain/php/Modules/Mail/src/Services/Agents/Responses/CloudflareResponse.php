<?php


namespace LaravelCompany\Mail\Services\Agents\Responses;

use Spatie\LaravelData\Data;


class CloudflareResponse extends Data
{
    public function __construct(
        public string $result,
    ) {
    }
}
