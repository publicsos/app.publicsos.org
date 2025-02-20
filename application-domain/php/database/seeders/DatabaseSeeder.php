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
class DatabaseSeeder extends Seeder
{


    private array $roles = [
        [
            'id' => 3,
            'name' => 'Incident Commander',
        ],
        [
            'id' => 4,
            'name' => 'Operations Chief',
        ],
        [
            'id' => 5,
            'name' => 'Medical Chief',
        ],
        [
            'id' => 6,
            'name' => 'Fire & HAZMAT Chief',
        ],
        [
            'id' => 7,
            'name' => 'Engineering Chief',
        ],
        [
            'id' => 8,
            'name' => 'Law Enforcement Chief',
        ],
        [
            'id' => 9,
            'name' => 'Logistics Coordinator',
        ],
        [
            'id' => 10,
            'name' => 'Communication Officer',
        ],
        [
            'id' => 11,
            'name' => 'Shelter Coordinator',
        ],
        [
            'id' => 12,
            'name' => 'Field Responder',
        ],
        [
            'id' => 13,
            'name' => 'Priest',
        ]
    ];
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();


        // Create categories
        $this->createCategories();
        // Create default permissions
        $this->createDefaultPermissions();

        // Create roles
        $this->createRoles();

        // Create users
        $this->createUsers();

        // Assign roles to users
        $this->assignUserRoles();



        Schema::enableForeignKeyConstraints();

        Artisan::call('cache:clear');
    }

    /**
     * Create default permissions and additional module permissions
     */
    private function createDefaultPermissions()
    {
        // Create default permissions
        $permissions = Permission::defaultPermissions();
        foreach ($permissions as $permission) {
            $permission = Permission::make(['name' => $permission]);
            $permission->saveOrFail();
        }

        // Create module specific permissions
        $modules = ['posts', 'categories', 'tags', 'comments', 'incidents', 'operations', 'resources', 'users'];
        foreach ($modules as $module) {
            Artisan::call('auth:permissions', [
                'name' => $module,
            ]);
            echo "\n *" . ucfirst($module) . "* Permissions Created.";
        }
        echo "\n\n";
    }

    /**
     * Create roles and assign permissions
     */
    private function createRoles()
    {
        // Create system roles first
        $super = Role::create(['id' => 1, 'name' => 'super admin']);

        $admin = Role::create(['id' => 2, 'name' => 'administrator']);
        $admin->givePermissionTo(['view_backend', 'edit_settings']);

        //transform roles names to lowercase
        $lowercaseRoles = [];
        foreach ($this->roles as $role) {
            $lowercaseRoles[] = [
                'id' => $role['id'],
                'name' => strtolower($role['name']),
            ];
        }


        foreach ($lowercaseRoles as $role_data) {
            $role = Role::create($role_data);

            $name = $role->name;
            // Assign permissions based on role
            switch ($role->name) {
                case 'incident commander':
                    $role->givePermissionTo(['view_backend', 'edit_settings', 'manage_users', 'manage_roles']);
                    break;
                case 'operations chief':
                case 'medical chief':
                case 'fire & hazmat chief':
                case 'engineering chief':
                case 'law enforcement chief':
                    $role->givePermissionTo(['view_backend', 'manage_operations', 'manage_resources']);
                    break;
                case 'logistics coordinator':
                    $role->givePermissionTo(['view_backend', 'manage_resources']);
                    break;
                case 'communication officer':
                    $role->givePermissionTo(['view_backend', 'manage_posts', 'manage_comments']);
                    break;
                case 'shelter coordinator':
                    $role->givePermissionTo(['view_backend', 'manage_resources']);
                    break;
                case 'field responder':
                    $role->givePermissionTo(['view_backend', 'create_reports']);
                case 'priest':
                        $role->givePermissionTo(['view_backend']);
                break;
            }
        }
    }

    /**
     * Create default users
     */
    private function createUsers()
    {
        $users = [
            [
                'id' => 1,
                'username' => '100001',
                'name' => 'Super Admin',
                'email' => 'super@admin.com',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'username' => '100002',
                'name' => 'Admin Istrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'username' => 'IC001',
                'name' => 'John Commander',
                'email' => 'commander@publicsos.org',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'username' => 'OC001',
                'name' => 'Sarah Operations',
                'email' => 'operations@publicsos.org',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'username' => 'MC001',
                'name' => 'Dr. Michael Medical',
                'email' => 'medical@publicsos.org',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'username' => 'FC001',
                'name' => 'Robert Fire',
                'email' => 'fire@publicsos.org',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'username' => 'EC001',
                'name' => 'Emma Engineering',
                'email' => 'engineering@publicsos.org',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 8,
                'username' => 'LC001',
                'name' => 'David Law',
                'email' => 'law@publicsos.org',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 9,
                'username' => 'LG001',
                'name' => 'Patricia Logistics',
                'email' => 'logistics@publicsos.org',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 10,
                'username' => 'CO001',
                'name' => 'James Communications',
                'email' => 'communications@publicsos.org',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 11,
                'username' => 'SC001',
                'name' => 'Maria Shelter',
                'email' => 'shelter@publicsos.org',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 12,
                'username' => 'FR001',
                'name' => 'Alex Responder',
                'email' => 'responder@publicsos.org',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 13,
                'username' => 'FR002',
                'name' => 'Jonathan Responder',
                'email' => 'responder2@publicsos.org',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 14,
                'username' => 'FR003',
                'name' => 'Priest',
                'email' => 'priest@publicsos.org',
                'password' => Hash::make('secret'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $user_data) {
            $user = User::create($user_data);
            //event(new UserCreated($user));
        }
    }

    /**
     * Assign roles to users
     */
    private function assignUserRoles()
    {
        // Assign system roles
        User::findOrFail(1)->assignRole('super admin');
        User::findOrFail(2)->assignRole('administrator');

        // Assign emergency response roles
        User::findOrFail(3)->assignRole('Incident Commander'); // council
        User::findOrFail(4)->assignRole('Operations Chief'); // OP
        User::findOrFail(5)->assignRole('Medical Chief'); // hospitals area points coordonation
        User::findOrFail(6)->assignRole('Fire & HAZMAT Chief'); //fire statins
        User::findOrFail(7)->assignRole('Engineering Chief');
        User::findOrFail(8)->assignRole('Law Enforcement Chief');
        User::findOrFail(9)->assignRole('Logistics Coordinator');
        User::findOrFail(10)->assignRole('Communication Officer');
        User::findOrFail(11)->assignRole('Shelter Coordinator');
        User::findOrFail(12)->assignRole('Field Responder');
        User::findOrFail(13)->assignRole('Field Responder');
        User::findOrFail(14)->assignRole('Priest');
    }



    public function createCategories()
    {
        foreach ($this->roles as $role) {


            Category::create([
                'name' => ucfirst($role['name']),
                'slug' => \Str::slug($role['name']),
                'description' => "Category for " . ucfirst($role['name']),
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
