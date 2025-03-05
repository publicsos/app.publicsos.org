<?php

namespace Modules\Document\DTOs;


use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ProcessedDocumentData extends Data
{
    public function __construct(
        #[StringType]
        public string $message,

        #[StringType]
        public string $markdown,

        public array $entities
    ) {}
}
