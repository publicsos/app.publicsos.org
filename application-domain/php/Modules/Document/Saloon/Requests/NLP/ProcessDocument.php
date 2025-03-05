<?php
namespace Modules\Document\Saloon\Requests\NLP;



use Illuminate\Support\Facades\Storage;


use Saloon\Http\Request;

use Saloon\Data\MultipartValue;
use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasMultipartBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\HasTimeout;

use Modules\Document\DTOs\EntityDto;
use Modules\Document\DTOs\ProcessedDocumentData;


class ProcessDocument extends Request implements HasBody
{
    use HasTimeout;

    protected int $connectTimeout = 60;

    protected int $requestTimeout = 120;

    use HasMultipartBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected $filePath,
    ){}

    protected function defaultBody(): array
    {

        $fileContent = file_get_contents($this->filePath);

        $fileName = basename($this->filePath);

        return [
            'file' => new MultipartValue("file", $fileContent, $fileName),

        ];
    }

    public function resolveEndpoint(): string
    {
        return 'v1_0/nlp/pdf-reader/';
    }



    public function createDtoFromResponse(Response $response): mixed
    {
        try {
            return ProcessedDocumentData::from($response->json());
        } catch (\Spatie\LaravelData\Exceptions\CannotCreateData $e) {
            // Handle validation errors
            throw new \RuntimeException(
                "Invalid API response format: " . $e->getMessage(),
                previous: $e
            );
        }
    }

}
