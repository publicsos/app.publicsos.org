<?php

declare(strict_types=1);

namespace Modules\Domain\Services;

use Modules\Domain\Saloon\Requests\Buildings\GetBuildingDetails;
use Modules\Domain\Saloon\Connectors\BuildingsConnector;
use Saloon\Http\Response;

class BuildingsService
{
    public function getBuildingDetails(string $building_id): mixed
    {

        $forge = new BuildingsConnector;

        $response = $forge->send(new GetBuildingDetails($building_id));

        return $response->json();
    }
}
