<?php

namespace Modules\Workflow\Console\Commands;

use Illuminate\Console\Command;

class WorkflowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:WorkflowCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Workflow Command description';

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
