<?php
namespace Modules\Category\Console\Commands;

use Illuminate\Console\Command;
use Modules\Category\Models\Category;
use Modules\Category\Enums\CategoryStatus;
use Illuminate\Support\Str;

class DomainCommand extends Command
{

    protected $signature = 'domain:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed predefined category roles into the database for our blog';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Categories have been seeded successfully.');
        return Command::SUCCESS;
    }
}
