<?php

namespace Modules\Domain\Saloon\Requests\Amanet;


use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Symfony\Component\DomCrawler\Crawler;


class GetContent extends Request
{
    protected Method $method = Method::GET;



    public function createDtoFromResponse(Response $response): mixed
    {

        $json = $response->json();
        dd($json);

    }
}
