<?php

namespace Modules\Mail\Services\Validation\DTO;



use Spatie\LaravelData\Data;

class EmailDebugData extends Data
{
    public function __construct(
        public string $server_name,
        public string $start_time,
        public string $end_time,
        public EmailDebugDurationData $duration,
        public EmailDebugSmtpData $smtp
    ) {}
}
