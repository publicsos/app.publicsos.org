<?php

namespace LaravelCompany\Mail\Services\Validation\DTO;

use Spatie\LaravelData\Data;

class EmailMiscData extends Data
{
    public function __construct(
        public bool $is_disposable,
        public bool $is_role_account,
        public ?string $gravatar_url,
        public ?string $haveibeenpwned
    ) {}
}
