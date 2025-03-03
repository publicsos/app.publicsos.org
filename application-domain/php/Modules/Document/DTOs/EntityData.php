<?php

namespace Modules\Document\DTOs;


use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\StringType;

class EntityData extends Data
{
    public function __construct(
        #[StringType,
         In(['ORG', 'PERSON', 'GPE', 'LOC', 'DATE', 'OTHER'])]
        public string $type,

        #[StringType]
        public string $name
    ) {}



}
