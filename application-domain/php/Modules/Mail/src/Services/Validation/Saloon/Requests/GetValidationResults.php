<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Services\Validation\Saloon\Requests;


use LaravelCompany\Mail\Services\Validation\DTO\EmailValidationResult;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class GetValidationResults extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * Define the endpoint for the request.
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/v0/check_email';
    }

    /**
     * Constructor to pass $scanId to the request.
     *
     * @param string $to
     */
    public function __construct(
        protected readonly string $to,
    )
    {
    }

    protected function defaultBody(): array
    {
        return [
            'to_email' => $this->to,
            'from_email' => "marketing@laravelmail.com", //todo: get from config
            "hello_name" => "validation.laravelmail.com", //todo: get from config
        ];
    }


    /**
     * @throws \JsonException
     */
    public function createDtoFromResponse(Response $response): EmailValidationResult
    {
        $results = $response->json();

        return new EmailValidationResult($results['is_reachable']);
    }
}
