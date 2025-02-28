<?php

namespace Modules\Document\Console\Commands;

use Illuminate\Console\Command;

class DocumentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DocumentCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Document Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Command::SUCCESS;
    }
}
