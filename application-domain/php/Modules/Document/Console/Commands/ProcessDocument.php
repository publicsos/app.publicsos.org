<?php

namespace Modules\Document\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Enums\DocumentStatus;
use Modules\Document\Models\Document;
use Modules\Document\Services\DocumentProcessor;
use Jurosh\PDFMerge\PDFMerger;
use Modules\Document\Repositories\DocumentRepository;

class ProcessDocument extends Command
{
    protected $signature = 'documents:process';

    protected $description = 'Checks for un-processed downloaded documents and sends them to the service to be processed';

    protected DocumentProcessor $processor;

    protected DocumentRepository $repository;

    public function __construct(DocumentProcessor $processor, DocumentRepository $repository)
    {
        parent::__construct();

        $this->processor = $processor;

        $this->repository = $repository;
    }



    public function handle(): int
    {

        $this->info('Checking for un-processed downloaded documents...');

        $document = Document::where('status', DocumentStatus::Downloaded->value)
        ->where('title', "!=", null)
        ->orderBy('id', 'desc')
        ->first();

        if(!$document)
         {
            $this->info('No downloaded documents found to process exiting gracefully.');

            return 0;
        }

        $response = $this->processor->process($document);

        $this->info('Document processed successfully.');


        return 1;

    }


}
