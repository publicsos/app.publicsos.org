<?php

namespace Modules\Mail\Services\Validation\DTO;

use Spatie\LaravelData\Data;

class EmailSmtpData extends Data
{
    public function __construct(
        public bool $can_connect_smtp,
        public bool $has_full_inbox,
        public bool $is_catch_all,
        public bool $is_deliverable,
        public bool $is_disabled
    ) {}
}
