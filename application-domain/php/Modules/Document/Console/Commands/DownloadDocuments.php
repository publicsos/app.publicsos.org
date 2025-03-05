<?php

namespace Modules\Document\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Enums\DocumentStatus;
use Modules\Document\Models\Document;
use Modules\Document\Services\DocumentProcessor;
use Jurosh\PDFMerge\PDFMerger;
use Modules\Document\Repositories\DocumentRepository;

class DownloadDocuments extends Command
{
    protected $signature = 'documents:download';
    protected $description = 'Checks for new documents and downloads them';

    public function __construct(
        protected DocumentProcessor $processor,
        protected DocumentRepository $repository
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Checking for unprocessed documents...');

        $document = Document::where('status', DocumentStatus::Unprocessed->value)
            ->whereNotNull('title')
            ->latest('id')
            ->first();

        if (!$document) {
            $this->info('No unprocessed documents found.');
            return 0;
        }

        $sessionID = config('services.mo.session_id', '078g68gt75heqni5csaib9ko5k');

        $pages = $this->processor->download($sessionID, $document->title);

        if ($pages->isEmpty()) {

            return $this->failDocumentProcessing($document, 'No pages downloaded.');
        }

        $filePath = Storage::disk('public')->path("/monitorul-oficial/{$document->id}.pdf");

        if (!$this->merge($pages->toArray(), $filePath)) {


            $this->repository->update($document, [
                'status' => DocumentStatus::Failed->value
            ]);

            return $this->failDocumentProcessing($document, 'Failed to merge PDFs.');
        }

        $this->repository->update($document, [
            'status' => DocumentStatus::Downloaded->value,
            'source' => $filePath,
        ]);

        $this->info("Successfully imported document: {$document->title}");
        return 0;
    }

    private function failDocumentProcessing(Document $document, string $message): int
    {
        $this->repository->update($document, [
            'status' => DocumentStatus::Failed->value,

        ]);

        $this->error("Failed to import document: {$document->title}. {$message}");
        return 0;
    }

    private function merge(array $pdfPaths, string $outputPath): bool
    {
        try {
            $merger = new PDFMerger();
            foreach ($pdfPaths as $path) {
                $merger->addPDF($path, 'all');
            }
            $merger->merge('file', $outputPath);
            return true;
        } catch (\Exception $e) {
            $this->error('PDF merging failed: ' . $e->getMessage());
            return false;
        }
    }
}
