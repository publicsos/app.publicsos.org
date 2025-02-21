<?php

namespace Modules\Domain\Console\Commands;

use Illuminate\Console\Command;

use Modules\Domain\Contracts\MonitorulOficialContract;
use Modules\Domain\Models\Buildings\Building;

class ImportBuildingsCommand extends Command
{

    protected $signature = 'app:import-buildings';


    protected $description = 'Imports the the town buildings from the public domain.';

    public function handle()
    {
        // Define CSV file path
        $filePath = storage_path('app/public/barlad.csv');

        if (!file_exists($filePath)) {
            $this->error("CSV file not found at: $filePath");
            return;
        }

        // Read CSV file
        $csv = file_get_contents($filePath);
        $rows = array_map('str_getcsv', explode("\n", trim($csv)));

        // Ensure CSV is not empty and has headers
        if (empty($rows) || count($rows) < 2) {
            $this->error("CSV file is empty or has no valid data.");
            return;
        }

        // Extract headers
        $headers = array_map('trim', $rows[0]);
        unset($rows[0]); // Remove header row

        foreach ($rows as $row) {
            if (count($row) !== count($headers)) {
                $this->warn("Skipping invalid row: " . implode(', ', $row));
                continue;
            }

            $data = array_combine($headers, $row);

            Building::create([
                'title' => $data['title'] ?? null,
                'address' => $data['address'] ?? null,
                'type' => $data['type'] ?? null,
                'geometry_type' => $data['geometry_type'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'properties_id' => $data['properties_id'] ?? null,
                'properties_title' => $data['properties_title'] ?? null,
                'properties_description' => $data['properties_description'] ?? null,
                'properties_face' => $data['properties_face'] ?? null,
                'properties_icon_scaledSize_width' => $data['properties_icon_scaledSize_width'] ?? null,
                'properties_icon_scaledSize_height' => $data['properties_icon_scaledSize_height'] ?? null,
                'properties_icon_origin_x' => $data['properties_icon_origin_x'] ?? null,
                'properties_icon_origin_y' => $data['properties_icon_origin_y'] ?? null,
                'properties_icon_anchor_x' => $data['properties_icon_anchor_x'] ?? null,
                'properties_icon_anchor_y' => $data['properties_icon_anchor_y'] ?? null,
                'distance_miles' => $data['distance_miles'] ?? null,
                'date' => $data['date'] ?? null,
                'contributor' => $data['contributor'] ?? null,
                'comment' => $data['comment'] ?? null,
            ]);
        }

        $this->info("Buildings imported successfully.");
    }
}
