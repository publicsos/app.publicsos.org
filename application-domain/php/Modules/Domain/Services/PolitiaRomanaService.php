<?php

declare(strict_types=1);

namespace Modules\Domain\Services;

use Modules\Domain\Saloon\Requests\ObiecteFurate;
use Modules\Domain\Contracts\PolitiaRomanaContract;
use Modules\Domain\Saloon\Connectors\PolitiaRomana;

class PolitiaRomanaService implements PolitiaRomanaContract
{

    /**
    * @var PolitiaRomana
    */
    private $connector;

    public function __construct(PolitiaRomana $connector)
    {
        $this->connector = $connector;
    }

    public function get(): array
    {
        $request  = new ObiecteFurate();

        $results = $this->connector->send($request);

        $dto = $results->dtoOrFail();

        return [];
    }
}
