<?php

namespace Modules\Document\Saloon\Requests\Monitorul;

use Arcanedev\LogViewer\Entities\Log;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Carbon\Carbon;


class GetDocuments extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $day,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/ramo_customs/emonitor/get_mo.php";
    }



    public function defaultHeaders(): array
    {
        return [
            'referer' => 'https://monitoruloficial.ro/e-monitor/',
            'content-type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            'accept' => 'text/html; charset=UTF-8',
            'pragma' => 'no-cache',
            'cache-control' => 'no-store, no-cache, must-revalidate',

        ];
    }


    protected function defaultBody(): array
    {
        return [
            'today' => $this->day,
        ];
    }

    public function createDtoFromResponse(Response $response): array
    {
        $status =  $response->status();

        $content = $response->body();

        $isRedirect = $response->redirect();

        $headers = $response->headers();

        $body = $response->body();

        info('DocumentProcessor: '. $status);
        info('DocumentBody: '. $body);

        return [$status, $content, $isRedirect, $headers, $body];
    }

}
