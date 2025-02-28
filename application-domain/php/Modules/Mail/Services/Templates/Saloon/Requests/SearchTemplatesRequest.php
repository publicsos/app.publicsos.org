<?php
declare(strict_types=1);
namespace Modules\Mail\Services\Templates\Saloon\Requests;


use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Http\Auth\BasicAuthenticator;


/**
 * Get templates request
 */
class SearchTemplatesRequest extends Request  implements HasBody
{

    private string $type = 'email';

    use HasJsonBody;

    protected Method $method = Method::POST;


    public function resolveEndpoint(): string
    {
        return 'https://unlayer.com/templates/search';
    }

    protected function defaultBody(): array
    {
        return [
            'page' => 1,
            'perPage' => 10000,
            'filter' => [
                'premium' => '',
                'collection' => '',
                'name' => '',
                'sortBy' => 'recent',
                'type' => $this->type,
            ],
        ];
    }
}
