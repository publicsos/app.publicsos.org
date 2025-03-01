<?php

declare(strict_types=1);

namespace Modules\Document\Services;


use Modules\Document\Saloon\Connectors\MonitorulOficialConnector;

use Symfony\Component\DomCrawler\Crawler;

class MonitorulOficialService
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
        throw new \Exception('Not implemented');
    }
}
