<?php

namespace Modules\Document\Console\Commands;

use Illuminate\Console\Command;

use Modules\Domain\Saloon\Connectors\MonitorulOficialConnector;
use Modules\Domain\Saloon\Requests\Monitorul\GetPage;
use Illuminate\Support\Facades\Storage;

class ProcesDocumentsCommand extends Command
{

    protected $signature = 'document:process';

    protected $description = 'Processes a downloaded document';

    public function handle()
    {
        $this->info("Starting process");
    }
}
