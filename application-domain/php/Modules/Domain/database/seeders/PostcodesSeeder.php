<?php

declare(strict_types=1);

namespace Modules\Domain\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Domain\Models\Buildings\Building;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;



class PostcodesSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // Counties data with timestamps
        $counties = array_map(function($county) use ($now) {
            return array_merge($county, [
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }, [
            ['id' => 1, 'county' => 'Alba'],
            ['id' => 2, 'county' => 'Arad'],
            ['id' => 3, 'county' => 'Argeş'],
            ['id' => 4, 'county' => 'Bacău'],
            ['id' => 5, 'county' => 'Bihor'],
            ['id' => 6, 'county' => 'Bistriţa-Năsăud'],
            ['id' => 7, 'county' => 'Botoşani'],
            ['id' => 8, 'county' => 'Brăila'],
            ['id' => 9, 'county' => 'Braşov'],
            ['id' => 10, 'county' => 'Bucureşti'],
            ['id' => 11, 'county' => 'Buzău'],
            ['id' => 12, 'county' => 'Călăraşi'],
            ['id' => 13, 'county' => 'Caraş-Severin'],
            ['id' => 14, 'county' => 'Cluj'],
            ['id' => 15, 'county' => 'Constanţa'],
            ['id' => 16, 'county' => 'Covasna'],
            ['id' => 17, 'county' => 'Dâmboviţa'],
            ['id' => 18, 'county' => 'Dolj'],
            ['id' => 19, 'county' => 'Galaţi'],
            ['id' => 20, 'county' => 'Giurgiu'],
            ['id' => 21, 'county' => 'Gorj'],
            ['id' => 22, 'county' => 'Harghita'],
            ['id' => 23, 'county' => 'Hunedoara'],
            ['id' => 24, 'county' => 'Ialomiţa'],
            ['id' => 25, 'county' => 'Iaşi'],
            ['id' => 26, 'county' => 'Ilfov'],
            ['id' => 27, 'county' => 'Maramureş'],
            ['id' => 28, 'county' => 'Mehedinţi'],
            ['id' => 29, 'county' => 'Mureş'],
            ['id' => 30, 'county' => 'Neamţ'],
            ['id' => 31, 'county' => 'Olt'],
            ['id' => 32, 'county' => 'Prahova'],
            ['id' => 33, 'county' => 'Sălaj'],
            ['id' => 34, 'county' => 'Satu Mare'],
            ['id' => 35, 'county' => 'Sibiu'],
            ['id' => 36, 'county' => 'Suceava'],
            ['id' => 37, 'county' => 'Teleorman'],
            ['id' => 38, 'county' => 'Timiş'],
            ['id' => 39, 'county' => 'Tulcea'],
            ['id' => 40, 'county' => 'Vâlcea'],
            ['id' => 41, 'county' => 'Vaslui'],
            ['id' => 42, 'county' => 'Vrancea'],
        ]);

        // Places data with timestamps
        $places = array_map(function($place) use ($now) {
            return array_merge($place, [
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }, [
            ['id' => 1, 'county' => 'Bucureşti', 'place' => 'Bucureşti'],
            ['id' => 2, 'county' => 'Alba', 'place' => 'Bărăşti'],
            ['id' => 3, 'county' => 'Olt', 'place' => 'Bărăşti'],
            ['id' => 4, 'county' => 'Suceava', 'place' => 'Bărăşti'],
            ['id' => 5, 'county' => 'Buzău', 'place' => 'Bărăşti'],
            ['id' => 6, 'county' => 'Argeş', 'place' => 'Bărăşti'],
            ['id' => 7, 'county' => 'Harghita', 'place' => 'Miercurea-Ciuc'],
            ['id' => 8, 'county' => 'Călăraşi', 'place' => 'Dragoş Vodă'],
            ['id' => 9, 'county' => 'Botoşani', 'place' => 'Burla'],
            ['id' => 10, 'county' => 'Harghita', 'place' => 'Ditrău'],
            ['id' => 11, 'county' => 'Botoşani', 'place' => 'Lunca (Vârfu Câmpului)'],
            ['id' => 12, 'county' => 'Botoşani', 'place' => 'Sărata'],
            ['id' => 13, 'county' => 'Botoşani', 'place' => 'Cerviceşti-Deal'],
            ['id' => 14, 'county' => 'Botoşani', 'place' => 'Săveni'],
            ['id' => 15, 'county' => 'Botoşani', 'place' => 'Vorona-Teodoru'],
            ['id' => 16, 'county' => 'Botoşani', 'place' => 'Vorona Mare'],
        ]);

        // Insert data in chunks for better performance
        DB::table('counties')->insert($counties);
        DB::table('places_multicode')->insert($places);

         //let
        $sqlFile = __DIR__ . '/data/postcodes.sql';


        try {
            if (File::exists($sqlFile)) {
                $sql = File::get($sqlFile);

                // Use PDO to properly handle special characters
                $pdo = DB::connection()->getPdo();

                // Split the file into individual INSERT statements
                preg_match_all('/INSERT INTO.*?\((.*?)\)\s+VALUES\s*(\((.*?)\)(?:\s*,\s*\((.*?)\))*);/s', $sql, $matches);

                foreach ($matches[0] as $query) {
                    // Prepare and execute each statement using PDO
                    $stmt = $pdo->prepare($query);
                    $stmt->execute();
                }
            } else {
                throw new \Exception("SQL file not found at path: {$sqlFile}");
            }
        } catch (\Exception $e) {

            throw $e;
        }
    }

}


