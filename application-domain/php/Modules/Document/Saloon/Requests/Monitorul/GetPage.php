<?php

namespace Modules\Document\Saloon\Requests\Monitorul;


use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;


class GetPage extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $doc,
        protected string $format,
        protected string $subfolder,
        protected int $page
    ) {}

    public function resolveEndpoint(): string
    {
        return "/ramo_customs/emonitor/showmo/services/view.php?" . http_build_query([
            'doc' => $this->doc,
            'format' => $this->format,
            'subfolder' => $this->subfolder,
            'page' => $this->page,
        ]);
    }
}
