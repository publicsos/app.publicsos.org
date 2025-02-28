<?php
declare(strict_types=1);

namespace Modules\Domain\Contracts;

use Illuminate\Support\Collection;

interface PoliceContract
{

    public function refreshItems(int $pages = 1): Collection;

    public function refreshItemDetails(string $url, string $category): array;
}
