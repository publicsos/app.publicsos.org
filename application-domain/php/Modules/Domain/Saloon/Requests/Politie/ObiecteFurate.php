<?php

namespace Modules\Domain\Saloon\Requests\Politie;


use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Symfony\Component\DomCrawler\Crawler;

class ObiecteFurate extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/ro/obiecte-furate';
    }



    public function createDtoFromResponse(Response $response): mixed
    {
        $data = $response->body();


        $crawler =  new Crawler($data);

        $results =  $crawler->filter('.listBoxRight')->each(function (Crawler $node) {

            $categorie = $node->filter('.listBoxCategorie')->text() ?? null;
            $item = $node->filter('.listBoxItem')->text() ?? null;

            return  [
                'categorie' => $categorie,
                'item' => $item,
                'url' => $node->filter('.listBoxItem')->attr('href')
            ];

        });

        dd($results);
    }
}
