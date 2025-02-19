<?php

namespace LaravelCompany\Mail\Services\Validation\DTO;



use Spatie\LaravelData\Data;

class EmailDebugDurationData extends Data
{
    public function __construct(
        public int $secs,
        public int $nanos
    ) {}
}
