<?php

namespace Modules\Document\DTOs;


class EntityDto
{
    public function __construct(
        public readonly string $type,
        public readonly string $name
    ) {}
}
