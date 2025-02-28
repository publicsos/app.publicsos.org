<?php


declare(strict_types=1);

namespace Modules\Domain\DTO;

use Spatie\LaravelData\Data;

class PoliceItemDTO extends Data
{
    public string $title;
    public string $content;
    public string $image;
    public string $category;
    public string $type;
    public string $status;

}
