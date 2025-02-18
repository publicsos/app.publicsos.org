<?php

namespace Modules\Domain\Saloon\Requests\Amanet;


use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Symfony\Component\DomCrawler\Crawler;


class GetProduct extends Request
{
    protected Method $method = Method::GET;

    public string $url = "";

    public function __construct(string $url)
    {
        $this->url = $url;

    }

    public function resolveEndpoint(): string
    {
        return $this->url;
    }


    public function createDtoFromResponse(Response $response): mixed
    {
        $data = $response->body();

        $crawler =  new Crawler($data);

        $title = $crawler->filter("h1")->text();

        $description = $crawler->filter("div#tab-additional_information")->text();

        $picture = $crawler->filter("a.swiper-slide-imglink")->attr("href");

        $price = $crawler->filter(".woocommerce-Price-amount")->text();

        return [
            "title" => $title,
            "description" => $description,
            "picture" => $picture,
            "price" => $price
        ];

    }
}
