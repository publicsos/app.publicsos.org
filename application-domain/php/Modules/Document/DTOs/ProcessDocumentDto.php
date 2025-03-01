<?php

namespace Modules\Document\DTOs;


class ProcessedDocumentDto
{
    public function __construct(
        public readonly string $message,
        public readonly string $markdown,
        /** @var EntityDto[] */
        public readonly array $entities
    ) {}
}
