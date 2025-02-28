<?php

namespace Modules\Workflow\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Workflow\Models\Workflow;

class WorkflowDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        /*
         * Workflows Seed
         * ------------------
         */

        // DB::table('workflows')->truncate();
        // echo "Truncate: workflows \n";

        Workflow::factory()->count(20)->create();
        $rows = Workflow::all();
        echo " Insert: workflows \n\n";

        // Enable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
