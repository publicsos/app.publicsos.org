<?php

namespace Modules\Document\Services;

use Illuminate\Support\Facades\Storage;
use Modules\Document\Saloon\Connectors\MonitorulOficialConnector;
use Modules\Document\Saloon\Requests\Monitorul\GetPage;

class DocumentDownloadService
{
    protected MonitorulOficialConnector $connector;

    public function __construct(MonitorulOficialConnector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Downloads document pages and returns an array of absolute file paths.
     *
     * @param string $documentId
     * @param string $documentType
     * @param string $documentPath
     * @param int    $totalPages
     * @param string $directory
     * @return array
     */
    public function downloadDocumentPages(
        string $documentId,
        string $documentType,
        string $documentPath,
        string $directory
    ): array {
        $pdfFiles = [];

        for ($page = 1; $page <= 32; $page++) {
            $request = new GetPage($documentId, $documentType, $documentPath, $page);
            $response = $this->connector->send($request);

            if (!$response->successful() || empty($response->body())) {
                continue;
            }

            $filename = "{$directory}/document_page_{$page}.pdf";

            Storage::disk('public')->put($filename, $response->body());

            $pdfFiles[] = Storage::path($filename);
        }

        return $pdfFiles;
    }
}
