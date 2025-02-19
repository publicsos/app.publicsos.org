<?php

namespace LaravelCompany\Mail\Services\Validation\DTO;


use Spatie\LaravelData\Data;

class EmailSyntaxData extends Data
{
    public function __construct(
        public string $address,
        public string $domain,
        public bool $is_valid_syntax,
        public string $username,
        public string $normalized_email,
        public ?string $suggestion
    ) {}
}
