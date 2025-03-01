<?php

namespace Modules\Document\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Services\DocumentService;
use Modules\Document\Services\PdfMergerService;
use Modules\Document\Saloon\Connectors\MonitorulOficialConnector;
use Modules\Document\Repositories\DocumentRepository;

class ImportCommand extends Command
{
    protected $signature = 'document:import
                            {--session_id= : The session ID for Monitorul Oficial}
                            {--document_id= : The document ID to process}
                            {--document_type=pdf : The document type (default: pdf)}
                            {--document_path= : The document path}
                            {--total_pages= : Total number of pages to process}
                            {--upload_endpoint= : Endpoint URL for uploading the merged PDF}
                            {--skip_upload : Skip uploading the merged PDF}';

    protected $description = 'Imports, merges documents, and posts the result';

    public function handle(): int
    {
        // Get parameters from command line options, falling back to env variables
        $sessionID = $this->option('session_id') ?: env('MONITORUL_OFICIAL_SESSION_ID', '4qatdpbt9obgdfqdpqlp7dr7i0');
        $documentId = $this->option('document_id') ?: env('MONITORUL_OFICIAL_DOCUMENT_ID', '0520250811');
        $documentType = $this->option('document_type') ?: env('MONITORUL_OFICIAL_DOCUMENT_TYPE', 'pdf');
        $documentPath = $this->option('document_path') ?: env('MONITORUL_OFICIAL_DOCUMENT_PATH', '5/2025/');
        $totalPages = (int)($this->option('total_pages') ?: env('MONITORUL_OFICIAL_TOTAL_PAGES', 32));
        $uploadEndpoint = $this->option('upload_endpoint') ?: env('PDF_UPLOAD_ENDPOINT', 'http://localhost:1602/v1_0/nlp/pdf-reader/');
        $skipUpload = $this->option('skip_upload');



        // Create directory for today
        $today = now()->format('d-m');
        $directory = "documents/{$today}/";
        Storage::disk("public")->makeDirectory($directory);

        // Initialize the service
        $connector = new MonitorulOficialConnector($sessionID);
        $repository = new DocumentRepository();
        $pdfMerger = new PdfMergerService();

        $documentService = new DocumentService($connector, $repository, $pdfMerger);

        $this->info("Starting download of {$totalPages} pages...");
        $progressBar = $this->output->createProgressBar($totalPages);
        $progressBar->start();

        // Download pages
        $pdfFiles = $documentService->downloadDocumentPages(
            $documentId,
            $documentType,
            $documentPath,
            $totalPages,
            $directory
        );

        $progressBar->finish();
        $this->newLine();

        if (empty($pdfFiles)) {
            $this->warn('No PDFs downloaded. Exiting.');
            return 0;
        }

        // Merge PDFs
        $mergedFilename = ("{$directory}{$documentId}.pdf");

        $mergedFilePath = Storage::disk("public")->path($mergedFilename);

        if (!$documentService->mergeDocumentPages($pdfFiles, $mergedFilePath)) {
            $this->error('Failed to merge PDFs.');
            return 1;
        }

        $this->info("Merged PDF saved to: {$mergedFilename}");

        // Save document metadata
        $documentService->saveDocumentMetadata(basename($mergedFilename), $mergedFilename);

        // Upload the merged PDF if not skipped
        if (!$skipUpload) {
            $this->info("Posting merged PDF to endpoint: {$uploadEndpoint}");
            if ($documentService->uploadDocument($mergedFilePath, $uploadEndpoint)) {
                $this->info("Successfully uploaded the merged PDF.");
            } else {
                $this->error("Failed to upload the merged PDF.");
                return 1;
            }
        } else {
            $this->info("Upload skipped as requested.");
        }

        return 0;
    }
}
