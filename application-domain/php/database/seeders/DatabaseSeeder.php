<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Modules\Category\Models\Category;
use Modules\Domain\database\seeders\BuildingSeeder;
use Modules\Domain\database\seeders\PostcodesSeeder;
use Modules\Domain\database\seeders\PostcodesGeoSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Emergency response roles configuration
     */
    private const ROLES = [
        ['id' => 1, 'name' => 'super admin', 'permissions' => ['*'], 'system' => true],
        ['id' => 2, 'name' => 'administrator', 'permissions' => ['view_backend', 'edit_settings'], 'system' => true],
        ['id' => 3, 'name' => 'incident commander', 'permissions' => ['view_backend', 'edit_settings', 'manage_users', 'manage_roles']],
        ['id' => 4, 'name' => 'operations chief', 'permissions' => ['view_backend', 'manage_operations', 'manage_resources']],
        ['id' => 5, 'name' => 'medical chief', 'permissions' => ['view_backend', 'manage_operations', 'manage_resources']],
        ['id' => 6, 'name' => 'fire & hazmat chief', 'permissions' => ['view_backend', 'manage_operations', 'manage_resources']],
        ['id' => 7, 'name' => 'engineering chief', 'permissions' => ['view_backend', 'manage_operations', 'manage_resources']],
        ['id' => 8, 'name' => 'law enforcement chief', 'permissions' => ['view_backend', 'manage_operations', 'manage_resources']],
        ['id' => 9, 'name' => 'logistics coordinator', 'permissions' => ['view_backend', 'manage_resources']],
        ['id' => 10, 'name' => 'communication officer', 'permissions' => ['view_backend', 'manage_posts', 'manage_comments']],
        ['id' => 11, 'name' => 'shelter coordinator', 'permissions' => ['view_backend', 'manage_resources']],
        ['id' => 12, 'name' => 'field responder', 'permissions' => ['view_backend', 'create_reports']],
        ['id' => 13, 'name' => 'priest', 'permissions' => ['view_backend']],
    ];

    /**
     * Users configuration with corresponding role assignments
     */
    private const USERS = [
        [
            'id' => 1,
            'username' => '100001',
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'role' => 'super admin',
        ],
        [
            'id' => 2,
            'username' => '100002',
            'name' => 'Admin Istrator',
            'email' => 'admin@admin.com',
            'role' => 'administrator',
        ],
        [
            'id' => 3,
            'username' => 'IC001',
            'name' => 'John Commander',
            'email' => 'commander@publicsos.org',
            'role' => 'incident commander',
        ],
        [
            'id' => 4,
            'username' => 'OC001',
            'name' => 'Sarah Operations',
            'email' => 'operations@publicsos.org',
            'role' => 'operations chief',
        ],
        [
            'id' => 5,
            'username' => 'MC001',
            'name' => 'Dr. Michael Medical',
            'email' => 'medical@publicsos.org',
            'role' => 'medical chief',
        ],
        [
            'id' => 6,
            'username' => 'FC001',
            'name' => 'Robert Fire',
            'email' => 'fire@publicsos.org',
            'role' => 'fire & hazmat chief',
        ],
        [
            'id' => 7,
            'username' => 'EC001',
            'name' => 'Emma Engineering',
            'email' => 'engineering@publicsos.org',
            'role' => 'engineering chief',
        ],
        [
            'id' => 8,
            'username' => 'LC001',
            'name' => 'David Law',
            'email' => 'law@publicsos.org',
            'role' => 'law enforcement chief',
        ],
        [
            'id' => 9,
            'username' => 'LG001',
            'name' => 'Patricia Logistics',
            'email' => 'logistics@publicsos.org',
            'role' => 'logistics coordinator',
        ],
        [
            'id' => 10,
            'username' => 'CO001',
            'name' => 'James Communications',
            'email' => 'communications@publicsos.org',
            'role' => 'communication officer',
        ],
        [
            'id' => 11,
            'username' => 'SC001',
            'name' => 'Maria Shelter',
            'email' => 'shelter@publicsos.org',
            'role' => 'shelter coordinator',
        ],
        [
            'id' => 12,
            'username' => 'FR001',
            'name' => 'Alex Responder',
            'email' => 'responder@publicsos.org',
            'role' => 'field responder',
        ],
        [
            'id' => 13,
            'username' => 'FR002',
            'name' => 'Jonathan Responder',
            'email' => 'responder2@publicsos.org',
            'role' => 'field responder',
        ],
        [
            'id' => 14,
            'username' => 'FR003',
            'name' => 'Priest',
            'email' => 'priest@publicsos.org',
            'role' => 'priest',
        ],
    ];

    /**
     * Module permissions to be created
     */
    private const MODULES = [
        'posts', 'categories', 'tags', 'comments',
        'incidents', 'operations', 'resources', 'users'
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key constraints during seeding
        Schema::disableForeignKeyConstraints();

        // Create all data in the appropriate order
        $this->createCategories();
        $this->createPermissions();
        $this->createRolesWithPermissions();
        $this->createUsersWithRoles();

        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();

        // Clear cache to ensure all seeded data is fresh
        Artisan::call('cache:clear');

        // Seed additional data
        $this->seedAdditionalData();

        $this->command->info('Database seeded successfully!');
    }

    /**
     * Create categories for each role
     */
    private function createCategories(): void
    {
        $this->command->info('Creating categories...');

        Category::truncate();

        foreach (self::ROLES as $role) {
            $name = ucfirst($role['name']);

            Category::create([
                'name' => $name,
                'slug' => \Str::slug($name),
                'description' => "Category for {$name}",
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('Categories created successfully.');
    }

    /**
     * Create default and module-specific permissions
     */
    private function createPermissions(): void
    {
        $this->command->info('Creating permissions...');

        Permission::truncate();

        // Create default permissions
        $permissions = Permission::defaultPermissions();
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create module specific permissions
        foreach (self::MODULES as $module) {
            Artisan::call('auth:permissions', ['name' => $module]);
            $this->command->info("✓ {$module} permissions created");
        }

        $this->command->info('All permissions created successfully.');
    }

    /**
     * Create roles and assign permissions in a single method
     */
    private function createRolesWithPermissions(): void
    {
        $this->command->info('Creating roles with permissions...');

        Role::truncate();

        foreach (self::ROLES as $roleData) {
            $role = Role::create([
                'id' => $roleData['id'],
                'name' => $roleData['name'],
            ]);

            // Handle special case for super admin
            if (isset($roleData['permissions'])) {
                if ($roleData['permissions'][0] === '*') {
                    // Super admin gets all permissions
                    $role->givePermissionTo(Permission::all());
                } else {
                    // Other roles get specific permissions
                    $role->givePermissionTo($roleData['permissions']);
                }
            }

            $this->command->info("✓ Role '{$roleData['name']}' created with permissions");
        }

        $this->command->info('Roles and permissions assignment completed.');
    }

    /**
     * Create users and assign roles in a single method
     */
    private function createUsersWithRoles(): void
    {
        $this->command->info('Creating users with roles...');

        User::truncate();

        foreach (self::USERS as $userData) {
            $user = User::create([
                'id' => $userData['id'],
                'username' => $userData['username'],
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $user->assignRole($userData['role']);
            $this->command->info("✓ User '{$userData['name']}' created with role '{$userData['role']}'");
        }

        $this->command->info('Users creation and role assignment completed.');
    }

    /**
     * Seed additional data from other seeders
     */
    private function seedAdditionalData(): void
    {
        $this->command->info('Seeding additional data...');

        // Buildings data
        $this->call(BuildingSeeder::class);

        // Postcodes data
        $this->call(PostcodesSeeder::class);
        $this->call(PostcodesGeoSeeder::class);

        $this->command->info('Additional data seeded successfully.');
    }
}
