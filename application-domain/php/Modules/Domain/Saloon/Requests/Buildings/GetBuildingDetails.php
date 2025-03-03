<?php
namespace Modules\Domain\Saloon\Requests\Buildings;



use Illuminate\Support\Facades\Storage;


use Saloon\Http\Request;


use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\HasTimeout;



class GetBuildingDetails extends Request
{
    use HasTimeout;

    protected int $connectTimeout = 60;

    protected int $requestTimeout = 120;

    protected Method $method = Method::GET;

    public function __construct(
        protected string $building_id,
    ){}


    public function resolveEndpoint(): string
    {
        $url = "locations/{$this->building_id}";



        return $url;
    }


    public function createDtoFromResponse(Response $response): mixed
    {
        $content =  $response->json();

       return $content;
    }

}
