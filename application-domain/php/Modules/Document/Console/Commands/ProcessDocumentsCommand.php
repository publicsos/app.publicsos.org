<?php
namespace Modules\Document\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Document;
use Modules\Document\Saloon\Connectors\NLPConnector;
use Modules\Document\Saloon\Requests\NLP\ProcessDocument;

/**
 * Class ProcessDocumentsCommand
 */
class ProcessDocumentsCommand extends Command
{

    protected $signature = 'document:process';

    protected $description = 'Processes a downloaded document';

    public function handle()
    {
        $this->info("Starting process");

        $documents = Document::get()->first();

        $this->processDocument($documents);

    }

    private function processDocument(Document $document)
    {

        $file = Storage::disk('public')->get($document->source);

        $connector = new NLPConnector();

        $request = new ProcessDocument($document->source);

        $response = $connector->send($request);

        dd($response->dtoOrFail(), );

    }
}
