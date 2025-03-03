<?php
namespace Modules\Domain\Console\Commands;

use Illuminate\Console\Command;
use Modules\Category\Models\Category;
use Modules\Category\Enums\CategoryStatus;
use Illuminate\Support\Str;

use Modules\Domain\Models\Buildings\Building;
use Modules\Domain\Services\BuildingsService;

class ImportBuildingAddresses extends Command
{

    protected $signature = 'import:building-addresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uses our provider to seed the location details into the database for our building';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    $builds = Building::get();
        foreach ($builds as $building) {



            $service = new BuildingsService();
            $details = $service->getBuildingDetails($building->remote_id);

            $this->info(json_encode($details));
        }
    }
}
