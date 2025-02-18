<?php

namespace Modules\Domain\Saloon\Requests;


use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class DocumentsSourcesRequest extends Request
{

    protected Method $method = Method::POST;

    public function __construct(
        protected string $today,
        protected string $rand
    ) {}

    public function resolveEndpoint(): string
    {
        return 'ramo_customs/emonitor/get_mo.php';
    }

    protected function defaultHeaders(): array
    {
        return [
            'accept' => '*/*',
            'content-type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            'origin' => 'https://monitoruloficial.ro',
            'referer' => 'https://monitoruloficial.ro/e-monitor/',
            'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36',
            'x-requested-with' => 'XMLHttpRequest',
        ];
    }

    protected function defaultBody(): array
    {
        return [
            'today' => $this->today,
            'rand' => $this->rand,
        ];
    }



    public function createDtoFromResponse(Response $response): mixed
    {
        $content =  $response->body();

        dd($content);
    }
}
