<?php
declare(strict_types=1);
namespace Modules\Mail\DTO;

use Spatie\LaravelData\Data;

class ImportEmailDTO extends Data
{
    public string $email;
    public string $first_name;
    public string $last_name;
    public string $tags;
}
