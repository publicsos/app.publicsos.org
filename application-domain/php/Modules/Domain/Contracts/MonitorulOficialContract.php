<?php

namespace Modules\Domain\Contracts;

use Carbon\Carbon;

interface MonitorulOficialContract
{

    public function getDocumentsSources(string $htmlSource): array;

    public function getDocumentSource():mixed;
}

