<?php

declare(strict_types=1);

namespace Modules\Domain\Services;

use Carbon\Carbon;
use Modules\Domain\Contracts\MonitorulOficialContract;

use Modules\Domain\Saloon\Connectors\MonitorulOficialConnector;

use Modules\Domain\Saloon\Requests\ViewDocumentRequest;
use Symfony\Component\DomCrawler\Crawler;

class MonitorulOficialService implements MonitorulOficialContract
{
    protected MonitorulOficialConnector $connector;


    public function __construct(MonitorulOficialConnector $connector)
    {
        $this->connector = $connector;
    }



    public function getDocumentsSources(string $htmlSource): array
    {
        $crawler = new Crawler($htmlSource);

        $baseUrl = rtrim($this->connector->resolveBaseUrl(), '/'); // Ensure no trailing slash

        $links = $crawler->filter('a.btn.btn-outline-primary')->each(function (Crawler $node) use ($baseUrl) {
            $relativeUrl = $node->attr('href');

            return [
                'url' => $baseUrl . '/' . ltrim($relativeUrl, '/'), // Ensure correct URL format
                'text' => trim($node->text()), // Remove extra spaces/newlines

            ];
        });

        return $links;
    }


    public function getDocumentSource(): mixed
    {

        $request = new ViewDocumentRequest(
            '0520250702',
            'jsonp',
            '5/2025/',
            10,
            'jQuery17204921201570208007_1739733634955'
        );

        $response = $this->connector->send($request);

        return ($response->dtoOrFail());
    }
}
