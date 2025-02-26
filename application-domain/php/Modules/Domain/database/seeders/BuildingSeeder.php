<?php

declare(strict_types=1);

namespace Modules\Domain\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Domain\Models\Buildings\Building;

class BuildingSeeder extends Seeder
{
    public function run()
    {
        // Read CSV file
        $csv = file_get_contents(__DIR__ ."/data/export.csv");

        $rows = array_map('str_getcsv', explode("\n", trim($csv)));

        // Extract headers
        $headers = array_map('trim', $rows[0]);
        unset($rows[0]); // Remove header row

        foreach ($rows as $row) {
            if (count($row) !== count($headers)) {
                continue;
            }

            $data = array_combine($headers, $row);

            // Map CSV headers to database fields
            Building::create([
                'type' => $data['type'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'title' => $data['title'] ?? null,
                'blocuri_id' => $data['blocuri_id'] ?? null,
                'description' => $data['description'] ?? null,
                'properties_face' => $data['faces'] ?? null,
                'properties_icon_url' => $data['url'] ?? null,
                'properties_icon_scaledSize_width' => $data['width'] ?? null,
                'properties_icon_scaledSize_height' => $data['height'] ?? null,
                'properties_icon_origin_x' => $data['originx'] ?? null,
                'properties_icon_origin_y' => $data['originy'] ?? null,
                'properties_icon_anchor_x' => $data['anchorx'] ?? null,
                'properties_icon_anchor_y' => $data['anchory'] ?? null,
                'distance_miles' => $data['distance_miles'] ?? null,
            ]);
        }
    }
}
