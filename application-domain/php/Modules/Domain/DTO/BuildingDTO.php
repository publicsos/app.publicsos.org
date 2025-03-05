<?php

namespace Modules\Domain\DTO;

use Modules\Domain\Enums\BuildingType;
use Spatie\LaravelData\Data;

class BuildingDTO extends Data
{
    public function __construct(
        public string $name,
        public BuildingType $type,
        public ?int $floors = null,
        public ?float $height = null,
        public ?string $material = null,
        public ?int $year_built = null
    ) {}
}
