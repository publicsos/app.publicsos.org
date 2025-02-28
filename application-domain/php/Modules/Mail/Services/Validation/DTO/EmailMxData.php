<?php

namespace Modules\Mail\Services\Validation\DTO;

use Spatie\LaravelData\Data;

class EmailMxData extends Data
{
    public function __construct(
        public bool $accepts_mail,
        public array $records
    ) {}
}
