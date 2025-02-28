<?php

namespace Modules\Domain\Saloon\Requests\Police;

use Modules\Domain\DTO\PoliceItemDTO;
use Modules\Post\Enums\PostStatus;
use Modules\Post\Enums\PostType;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Symfony\Component\DomCrawler\Crawler;

class GetItemDetails extends Request
{

    protected string $url;

    protected string $category;


    protected Method $method = Method::GET;

    public function __construct(string $url, string $category)
    {
        $this->url = $url;
        $this->category = $category;
    }

    public function resolveEndpoint(): string
    {

        $title = str_replace("https://politiaromana.ro/ro/obiecte-furate/", "", $this->url);

        return 'https://politiaromana.ro/ro/print?modul=obiectefurate&link=' . $title;

    }

    public function createDtoFromResponse(Response $response): PoliceItemDTO
    {
        $data = $response->body();

        $crawler =  new Crawler($data);

        $title = $crawler->filter('h1')->innerText();

        $image = $crawler->filter('img.left')->attr('src');

        $content = $crawler->filter('div')->text();

        $status = PostStatus::Stolen->value;

        $type = PostType::Object->value;

        $category = $this->category;

        $bodyData = [
            "title" => $title,
            "content" => $content,
            "image" => $image,
            "category" => $category,
            "type" => $type,
            "status" => $status
        ];
        return PoliceItemDTO::from($bodyData);

    }
}
