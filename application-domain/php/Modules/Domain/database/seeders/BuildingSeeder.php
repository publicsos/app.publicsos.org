<?php

declare(strict_types=1);

namespace Modules\Domain\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Domain\Enums\BuildingType;
use Modules\Domain\Enums\BuildingRiskType;
use Modules\Domain\Enums\BuildingAgeGroup;

class BuildingSeeder extends Seeder
{
    public function run()
    {
        // Read CSV file
        $csv = file_get_contents(__DIR__ . "/data/buildings.csv");

        $rows = array_map('str_getcsv', explode("\n", trim($csv)));

        // Extract headers
        $headers = array_map('trim', $rows[0]);
        unset($rows[0]); // Remove header row

        $dataToInsert = []; // Array to store data for bulk insertion

        foreach ($rows as $row) {

            $data = array_combine($headers, $row);

            $dataToInsert[] = [
                'type' => BuildingType::APARTMENTS->value,
                'longitude' => $data['longitude'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'title' => $data['title'] ?? null,
                'remote_id' => $data['remote_id'] ?? null,
                'postcode' => @$data['postcode'] ?? null,
                'address' => @$data['address'] ?? null,
                'risk' => @$data['risk'] ?? BuildingRiskType::UNKNOWN->value,
                'age_group' =>  BuildingAgeGroup::AGE_1977_1990->value,

                'height' => 10,
                'apartments' => @$data['apartments'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Bulk insert using DB facade
        DB::table('buildings')->insert($dataToInsert);
    }



    private function calculateHeight(int $numberOfApartments): int
    {
        return $numberOfApartments * 10 ?? 10;
    }
}
