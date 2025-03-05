<?php

namespace Modules\Document\Saloon\Requests\Monitorul;


use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\HasTimeout;
class GetPage extends Request
{
    use HasTimeout;

    protected int $connectTimeout = 60;

    protected int $requestTimeout = 120;

    protected Method $method = Method::GET;

    public function __construct(
        protected string $doc,
        protected string $subfolder,
        protected int $page
    ) {}


    public function defaultHeaders(): array
    {
        return [
            'referer' => 'https://monitoruloficial.ro/Monitorul-Oficial--PIV--949--2025.html',
            'content-type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        ];
    }

    public function resolveEndpoint(): string
    {
        $endpoint =  "/ramo_customs/emonitor/showmo/services/view.php?" . http_build_query([
            'doc' => $this->generateDocID(),
            'format' => "pdf",
            'subfolder' => $this->subfolder,
            'page' => $this->page,
        ]);

        //dd($endpoint);

        return $endpoint;
    }


    public function createDtoFromResponse(Response $response): mixed
    {
        //dd($response->body(), $this->generateDocID(), $this->subfolder, $this->page);
        return $response->body();
    }



    private function generateDocID()
    {
        $subfolderID = str_replace('/', "", $this->subfolder);

        if (strlen( $this->doc) <= 3) {
            $subfolderID = "{$subfolderID}0";
        }
        return "0{$subfolderID}" . $this->doc;
    }

}
