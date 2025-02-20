<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $this->createDefaultPermissions();
        $this->createEmergencyPermissions();
        $this->createOriginalRoles();
        $this->createEmergencyRoles();
        Artisan::call('cache:clear');
    }

    protected function createDefaultPermissions()
    {
        // Original default permissions
        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Generate CRUD permissions for specific modules
        $modules = ['posts', 'categories', 'tags', 'comments'];
        foreach ($modules as $module) {
            Artisan::call('auth:permissions', ['name' => $module]);
            echo "\n_{$module}_ Permissions Created.";
        }
    }

    protected function createEmergencyPermissions()
    {
        $emergencyPermissions = [
            'view_backend' => 'Access the backend system interface',
            'assign_teams' => 'Assign and manage response teams',
            'monitor_field_response' => 'Monitor and coordinate field response activities',
            'oversee_medical' => 'Manage medical response and resources',
            'control_fire_hazmat' => 'Manage fire and hazardous materials response',
            'assess_structures' => 'Assess and manage structural integrity',
        ];

        foreach ($emergencyPermissions as $name => $desc) {
            Permission::firstOrCreate(['name' => $name], []);
        }
    }

    protected function createOriginalRoles()
    {
        // Original roles with their permissions
        $superAdmin = Role::firstOrCreate(['id' => 1, 'name' => 'super admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::firstOrCreate(['id' => 2, 'name' => 'administrator']);
        $admin->givePermissionTo(['view_backend', 'edit_settings']);

        Role::firstOrCreate(['id' => 3, 'name' => 'manager'])->givePermissionTo('view_backend');
        Role::firstOrCreate(['id' => 4, 'name' => 'executive'])->givePermissionTo('view_backend');
        Role::firstOrCreate(['id' => 5, 'name' => 'user']);
    }

    protected function createEmergencyRoles()
    {
        $emergencyRoles = [
            [
                'id' => 6,
                'name' => 'Incident Commander',
                'permissions' => '*',
                'description' => 'Has full control over the system, can manage all users and settings.',
            ],
            [
                'id' => 7,
                'name' => 'Operations Chief',
                'permissions' => ['view_backend', 'view_users', 'view_roles', 'assign_teams', 'monitor_field_response'],
                'description' => 'Manages rescue operations, assigns teams, and monitors field response.',
            ],
            [
                'id' => 8,
                'name' => 'Medical Chief',
                'permissions' => ['view_backend', 'view_users', 'oversee_medical'],
                'description' => 'Oversees medical response, assigns paramedics, and manages field hospitals.',
            ],
            [
                'id' => 9,
                'name' => 'Fire & HAZMAT Chief',
                'permissions' => ['view_backend', 'view_users', 'control_fire_hazmat'],
                'description' => 'Controls firefighting and hazardous materials response teams.',
            ],
            [
                'id' => 10,
                'name' => 'Engineering Chief',
                'permissions' => ['view_backend', 'view_users', 'assess_structures'],
                'description' => 'Manages structural assessments and infrastructure repair teams.',
            ],
        ];

        foreach ($emergencyRoles as $roleData) {
            $role = Role::firstOrCreate(
                ['id' => $roleData['id']],
                ['name' => $roleData['name']],

            );

            if ($roleData['permissions'] === '*') {
                $role->syncPermissions(Permission::all());
            } else {
                $role->syncPermissions($roleData['permissions']);
            }
        }
    }
}
