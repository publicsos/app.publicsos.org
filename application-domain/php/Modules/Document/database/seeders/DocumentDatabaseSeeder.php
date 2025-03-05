<?php

namespace Modules\Document\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Document\Models\Document;

class DocumentDatabaseSeeder extends Seeder
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
         * Documents Seed
         * ------------------
         */

        // DB::table('documents')->truncate();
        // echo "Truncate: documents \n";

        Document::factory()->count(20)->create();
        $rows = Document::all();
        echo " Insert: documents \n\n";

        // Enable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
