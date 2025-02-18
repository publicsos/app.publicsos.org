<?php

namespace Modules\Domain\Saloon\Requests\Amanet;


use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Symfony\Component\DomCrawler\Crawler;


class GetCategoryProducts extends Request
{
    protected Method $method = Method::GET;


    public string $category = "";

    public function __construct(string $category)
    {

        $this->category = $category;
    }

    public function resolveEndpoint(): string
    {
        return '/categorie/' . $this->category;
    }


    public function createDtoFromResponse(Response $response): mixed
    {
        $data = $response->body();

        if($response->status() !== 200)
        {
            throw new \RuntimeException("Response code from api was " . $response->status());
        }

        $crawler =  new Crawler($data);

        $results =  $crawler->filter('.product')->each(function (Crawler $node) {

            $link = $node->filter('div.woocommerce-loop-product__title');

            $price = $node->filter("span.price");

            return [
                "title" => $link->filter('a')->innerText(),
                "source" => $link->filter('a')->attr('href'),
                "price" => $price->text()
            ];

        });


        return $results;

    }
}
