<?php

namespace Modules\Document\Saloon\Requests\Monitorul;


use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;


class ViewDocument extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $doc,
        protected string $format,
        protected string $subfolder,
        protected int $page,
        protected string $callback
    ) {}

    public function resolveEndpoint(): string
    {
        return "ramo_customs/emonitor/view.php?doc={$this->doc}&format={$this->format}&subfolder={$this->subfolder}&page={$this->page}&callback={$this->callback}";
    }


    protected function defaultHeaders(): array
    {

        $cookie = 'sbjs_migrations=1418474375998=1;sbjs_first_add=fd=2025-02-15 15:30:37|||ep=https://monitoruloficial.ro/|||rf=https://www.google.com/;sbjs_current=typ=organic|||src=google|||mdm=organic|||cmp=(none)|||cnt=(none)|||trm=(none)|||id=(none)|||plt=(none)|||fmt=(none)|||tct=(none);sbjs_first=typ=organic|||src=google|||mdm=organic|||cmp=(none)|||cnt=(none)|||trm=(none)|||id=(none)|||plt=(none)|||fmt=(none)|||tct=(none);sbjs_current_add=fd=2025-02-16 12:56:47|||ep=https://monitoruloficial.ro/en/produs/exemplare-pdf-partea-a-iii-si-partea-a-iv-a/|||rf=https://www.google.com/;PHPSESSID=078g68gt75heqni5csaib9ko5k;sbjs_udata=vst=7|||uip=(none)|||uag=Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36';
        return [
            'Accept' => 'text/javascript, application/javascript, application/ecmascript, application/x-ecmascript, */*; q=0.01',
            'Accept-Language' => 'en-GB,en;q=0.9,es;q=0.8,ro-RO;q=0.7,ro;q=0.6,en-US;q=0.5,uk;q=0.4',
            'Cookie' => $cookie, // Include only necessary cookies
            'DNT' => '1',
            'Priority' => 'u=1, i',
            'Referer' => 'https://monitoruloficial.ro/Monitorul-Oficial--PIV--702--2025.html',
            'Sec-CH-UA' => '"Not A(Brand";v="8", "Chromium";v="132", "Google Chrome";v="132"',
            'Sec-CH-UA-Mobile' => '?0',
            'Sec-CH-UA-Platform' => '"Windows"',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'same-origin',
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36',
            'X-Requested-With' => 'XMLHttpRequest',
        ];
    }


    protected function defaultBody(): array
    {
        return [
            'doc' => $this->doc,
            'format' => $this->format,
            'subfolder' => $this->subfolder,
            'page' => $this->page,
            'callback' => $this->callback,
        ];
    }


    public function createDtoFromResponse(Response $response): mixed
    {
        return $response->body();
    }

}
