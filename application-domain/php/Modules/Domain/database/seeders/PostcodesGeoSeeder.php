<?php

declare(strict_types=1);

namespace Modules\Domain\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Domain\Models\Postcodes\PostcodeGeo;

class PostcodesGeoSeeder extends Seeder
{
    public function run()
    {
        $filePath = __DIR__ . "/data/postcodes-geolocation.csv";
        $file = fopen($filePath, 'r');

        if ($file === false) {
            $this->command->error("Failed to open CSV file: {$filePath}");
            return;
        }

        // Read and process headers
        $headers = array_map('trim', fgetcsv($file));

        $batchSize = 1000; // Adjust based on your database's parameter limit
        $batch = [];

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($file)) !== false) {
                $row = array_map('trim', $row);

                // Skip rows that don't match the header count
                if (count($row) !== count($headers)) {
                    continue;
                }

                $data = array_combine($headers, $row);

                $mappedData = [
                    'country_code'   => $data['country_code'] ?? null,
                    'zipcode'        => $data['zipcode'] ?? null,
                    'place'          => $data['place'] ?? null,
                    'state'          => $data['state'] ?? null,
                    'state_code'     => $data['state_code'] ?? null,
                    'province'       => $data['province'] ?? null,
                    'province_code'  => $data['province_code'] ?? null,
                    'community'      => $data['community'] ?? null,
                    'community_code' => $data['community_code'] ?? null,
                    'latitude'       => $data['latitude'] ?? null,
                    'longitude'      => $data['longitude'] ?? null,
                    // Include timestamps if your model uses them
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];

                $batch[] = $mappedData;

                // Insert in batches
                if (count($batch) >= $batchSize) {
                    PostcodeGeo::insert($batch);
                    $batch = [];
                }
            }

            // Insert remaining records
            if (!empty($batch)) {
                PostcodeGeo::insert($batch);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error seeding postcodes: " . $e->getMessage());
            throw $e;
        } finally {
            fclose($file);
        }
    }
}
