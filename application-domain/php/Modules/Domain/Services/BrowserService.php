<?php

declare(strict_types=1);

namespace Modules\Domain\Services;

use Carbon\Carbon;
use Modules\Domain\Contracts\HeadlessBrowserContract;
use Modules\Domain\Contracts\MonitorulOficialContract;
use Modules\Domain\Saloon\Connectors\HeadlessBrowserConnector;
use Modules\Domain\Saloon\Connectors\MonitorulOficialConnector;

use Symfony\Component\DomCrawler\Crawler;

class PublicDocumentService implements HeadlessBrowserContract
{
    protected MonitorulOficialConnector $connector;


    public function __construct(HeadlessBrowserConnector $connector)
    {
        $this->connector = $connector;
    }



    public function getTodayDocuments(): array
    {
        return $this->connector->getTodayDocuments();
    }
}
