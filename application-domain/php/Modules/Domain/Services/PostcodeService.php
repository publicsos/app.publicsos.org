<?php

declare(strict_types=1);

namespace Modules\Domain\Services;


use Modules\Domain\Contracts\Postcodes;


class PostcodeService implements Postcodes
{
    public function import(): void
    {
        throw new \Exception('Not implemented');
    }
}
