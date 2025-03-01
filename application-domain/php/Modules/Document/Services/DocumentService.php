<?php

namespace Modules\Document\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Jurosh\PDFMerge\PDFMerger;
use Modules\Document\Repositories\DocumentRepository;
use Modules\Document\Saloon\Connectors\MonitorulOficialConnector;
use Modules\Document\Saloon\Requests\Monitorul\GetPage;


class DocumentService
{
    public function __construct(
        private readonly MonitorulOficialConnector $connector,
        private readonly DocumentRepository $repository,
        private readonly PdfMergerService $pdfMergerService
    ) {}

    public function downloadDocumentPages(
        string $documentId,
        string $documentType,
        string $documentPath,
        int $totalPages,
        string $directory
    ): array {
        $pdfFiles = [];

        for ($page = 1; $page <= $totalPages; $page++) {
            $request = new GetPage($documentId, $documentType, $documentPath, $page);
            $response = $this->connector->send($request);

            if ($response->successful() && !empty($response->body())) {
                $filename = "{$directory}document_page_{$page}.pdf";
                Storage::disk('public')->put($filename, $response->body());
                $pdfFiles[] = Storage::disk('public')->path($filename);
            }
        }

        return $pdfFiles;
    }

    public function mergeDocumentPages(array $pdfFiles, string $mergedFilePath): bool
    {
        $success = $this->pdfMergerService->merge($pdfFiles, $mergedFilePath);

        if ($success) {
            array_walk($pdfFiles, function ($file) {
                @unlink($file);
            });
        }

        return $success;
    }


    public function uploadDocument(string $filePath, string $endpoint): bool
    {
        try {
            $response = Http::withHeaders(['accept' => 'application/json'])
                ->attach(
                    'file',
                    file_get_contents($filePath),
                    basename($filePath),
                    ['Content-Type' => 'application/pdf']
                )->post($endpoint);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function saveDocumentMetadata(string $name, string $path): void
    {

        $this->repository->saveOrUpdate([
            'name' => $name,
            'source' => $path,
            'status' => 0,
            'date' => now()->toDateString(),
        ]);
    }
}
