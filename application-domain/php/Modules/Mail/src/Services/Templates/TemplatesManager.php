<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Services\Templates;

use LaravelCompany\Mail\Repositories\TemplateTenantRepository;

use LaravelCompany\Mail\Services\Templates\Saloon\Connectors\UnlayerConnector;
use LaravelCompany\Mail\Services\Templates\Saloon\Requests\GetTemplateRequest;
use LaravelCompany\Mail\Services\Templates\Saloon\Requests\SearchTemplatesRequest;
use Illuminate\Support\Collection;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use LaravelCompany\Mail\Services\Templates\Saloon\DTO\TemplateDTO;

use LaravelCompany\Mail\Services\Templates\Contracts\TemplateContract;

readonly class TemplatesManager implements TemplateContract
{
    public function __construct(
        private UnlayerConnector $connector,
    )
    {

    }

    /**
     * @throws FatalRequestException
     * @throws RequestException|JsonException
     */
    public function getTemplates(): Collection
    {
        $data = $this->connector->send(new SearchTemplatesRequest());

        return collect($data->json()['data'] ?? []);
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function getTemplate(string $templateName): string
    {
        $data = $this->connector->send(new GetTemplateRequest($templateName));
        
        return $data->body();
    }

    //todo: add content to template dto and save it
    /**
     * @throws \Exception
     */
    public function importTemplate(TemplateDTO $templateDTO):void
    {
       throw new \Exception('Not implemented');
    }
}
