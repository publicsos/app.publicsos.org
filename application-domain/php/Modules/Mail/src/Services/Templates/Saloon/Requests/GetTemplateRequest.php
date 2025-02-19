<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Services\Templates\Saloon\Requests;


use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Auth\BasicAuthenticator;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Get templates request
 */
class GetTemplateRequest extends Request
{


    protected Method $method = Method::GET;


    public function __construct(
        public readonly string $templateSlug
    )
    {

    }

    /**
     * Define the endpoint for the request.
     * code...stock/templates/labor-day-mattress-sale/html
 * @return string
     */
    public function resolveEndpoint(): string
    {
        #
        return 'https://api.unlayer.com/v2/stock/templates/' . $this->templateSlug . '/html';
    }

    protected function defaultBody(): array
    {
        return [

        ];
    }
}
