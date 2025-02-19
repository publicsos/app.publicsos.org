<?php

namespace Modules\Domain\Console\Commands;

use Illuminate\Console\Command;

use Modules\Domain\Contracts\MonitorulOficialContract;
use Modules\Domain\Models\Documents\Document;

class ImportDocuments extends Command
{

    protected $signature = 'app:import-documents';


    protected $description = 'Imports the Romanian government official documents Monitorul Oficial and checks for common data to be used in the system.';


    public function handle(MonitorulOficialContract $contract)
    {
        /**
         * Debugging Code
         */

        //$day = $this->ask("Please provide the date to use in format Y-m-d ");

        //$source = $this->ask("Please provide the html snippet to parse the links from");

        //$links = collect($contract->getDocumentsSources($source));

        $document = $contract->getDocumentSource();

        dd($document);

        $this->info('Done!');

    }

}
