<?php
namespace Modules\Category\Console\Commands;

use Illuminate\Console\Command;
use Modules\Category\Models\Category;
use Modules\Category\Enums\CategoryStatus;
use Illuminate\Support\Str;

class CategoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:seed';

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
        $roles = [
            ['name' => 'Incident Commander', 'description' => 'Has full control over the system, can manage all users and settings.'],
            ['name' => 'Operations Chief', 'description' => 'Manages rescue operations, assigns teams, and monitors field response.'],
            ['name' => 'Medical Chief', 'description' => 'Oversees medical response, assigns paramedics, and manages field hospitals.'],
            ['name' => 'Fire & HAZMAT Chief', 'description' => 'Controls firefighting and hazardous materials response teams.'],
            ['name' => 'Engineering Chief', 'description' => 'Manages structural assessments and infrastructure repair teams.'],
            ['name' => 'Law Enforcement Chief', 'description' => 'Handles security, police, and military coordination.'],
            ['name' => 'Logistics Coordinator', 'description' => 'Manages emergency supplies, fuel, and transportation.'],
            ['name' => 'Communication Officer', 'description' => 'Controls emergency broadcasts, GIS, and public updates.'],
            ['name' => 'Shelter Coordinator', 'description' => 'Manages evacuation centers, food distribution, and survivor care.'],
            ['name' => 'Field Responder', 'description' => 'Has limited access to update reports, mark areas as cleared, and report new incidents.']
        ];

        foreach ($roles as $role) {
            Category::create([
                'name' => $role['name'],
                'description' => $role['description'],
                'status' => CategoryStatus::Active // Assuming there's an ACTIVE status in your enum
            ]);
        }

        $this->info('Categories have been seeded successfully.');
        return Command::SUCCESS;
    }
}
