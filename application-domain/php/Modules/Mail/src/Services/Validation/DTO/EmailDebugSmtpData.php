<?php

namespace LaravelCompany\Mail\Services\Validation\DTO;

use Spatie\LaravelData\Data;

class EmailDebugSmtpData extends Data
{
    public function __construct(
        public SmtpVerifMethodData $verif_method
    ) {}
}
