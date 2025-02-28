<?php

declare(strict_types=1);

namespace Modules\Mail\Services\Validation\DTO;

use Spatie\LaravelData\Data;

use Spatie\LaravelData\Attributes\MapInputName;

use Modules\Mail\Services\Validation\DTO\Enums\ReachabilityStatus;

class EmailValidationResult extends Data
{
    public function __construct(
        #[MapInputName('is_reachable')]
        public ReachabilityStatus $isReachable
    ) {}

    public static function fromString(string $status): self
    {
        return new self(
            ReachabilityStatus::from($status)
        );
    }
}
